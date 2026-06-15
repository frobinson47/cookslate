<?php

/**
 * Convert an internal recipe array into a schema.org/Recipe JSON-LD document.
 *
 * Output conforms to https://schema.org/Recipe and is accepted by Google
 * Search structured data tooling, Mealie, Tandoor, and most recipe importers.
 */
class JsonLdRecipeExporter {

    /**
     * Build a JSON-LD document for one recipe.
     *
     * @param array $recipe Recipe row as returned by Recipe::findById() or exportAll().
     *                      Expected keys: title, description, prep_time, cook_time,
     *                      servings, source_url, image_path, instructions (array),
     *                      ingredients (array of {name, amount, unit}), tags (array of
     *                      {name} or strings), calories, protein, carbs, fat, fiber,
     *                      sugar, created_at, updated_at, author (optional).
     * @param ?string $imageBaseUrl Base URL to prepend to relative image_path values.
     */
    public static function toJsonLd(array $recipe, ?string $imageBaseUrl = null): array {
        $doc = [
            '@context' => 'https://schema.org',
            '@type' => 'Recipe',
        ];

        if (!empty($recipe['title'])) $doc['name'] = (string) $recipe['title'];
        if (!empty($recipe['description'])) $doc['description'] = (string) $recipe['description'];

        $image = self::imageUrl($recipe['image_path'] ?? null, $imageBaseUrl);
        if ($image !== null) $doc['image'] = $image;

        if (!empty($recipe['source_url'])) $doc['url'] = (string) $recipe['source_url'];

        $prep = self::minutesToIso8601($recipe['prep_time'] ?? null);
        $cook = self::minutesToIso8601($recipe['cook_time'] ?? null);
        if ($prep !== null) $doc['prepTime'] = $prep;
        if ($cook !== null) $doc['cookTime'] = $cook;

        $total = self::sumMinutes($recipe['prep_time'] ?? null, $recipe['cook_time'] ?? null);
        $totalIso = self::minutesToIso8601($total);
        if ($totalIso !== null) $doc['totalTime'] = $totalIso;

        if (!empty($recipe['servings'])) {
            $doc['recipeYield'] = (string) $recipe['servings'];
        }

        if (!empty($recipe['author'])) {
            $doc['author'] = ['@type' => 'Person', 'name' => (string) $recipe['author']];
        }

        $doc['recipeIngredient'] = self::ingredientsToStrings($recipe['ingredients'] ?? []);
        $doc['recipeInstructions'] = self::instructionsToHowToSteps($recipe['instructions'] ?? []);

        $tags = self::tagNames($recipe['tags'] ?? []);
        if (!empty($tags)) {
            $doc['keywords'] = implode(', ', $tags);
            $doc['recipeCategory'] = $tags[0];
        }

        $nutrition = self::nutrition($recipe);
        if ($nutrition !== null) $doc['nutrition'] = $nutrition;

        if (!empty($recipe['created_at'])) {
            $doc['dateCreated'] = self::toIsoDate($recipe['created_at']);
        }
        if (!empty($recipe['updated_at'])) {
            $doc['dateModified'] = self::toIsoDate($recipe['updated_at']);
        }

        return $doc;
    }

    /**
     * Convert minutes (int or numeric string) to ISO 8601 duration: 90 -> "PT1H30M".
     * Returns null for null, 0, or unparseable input.
     */
    public static function minutesToIso8601($minutes): ?string {
        if ($minutes === null || $minutes === '' || !is_numeric($minutes)) return null;
        $m = (int) $minutes;
        if ($m <= 0) return null;
        $hours = intdiv($m, 60);
        $mins = $m % 60;
        $iso = 'PT';
        if ($hours > 0) $iso .= $hours . 'H';
        if ($mins > 0) $iso .= $mins . 'M';
        return $iso === 'PT' ? null : $iso;
    }

    private static function sumMinutes($a, $b): ?int {
        $sum = 0;
        $any = false;
        if (is_numeric($a)) { $sum += (int) $a; $any = true; }
        if (is_numeric($b)) { $sum += (int) $b; $any = true; }
        return $any ? $sum : null;
    }

    /**
     * Build the human-readable ingredient strings schema.org expects.
     * "2 cups flour" rather than separate amount/unit/name fields.
     */
    private static function ingredientsToStrings(array $ingredients): array {
        $out = [];
        foreach ($ingredients as $ing) {
            if (is_string($ing)) {
                $line = trim($ing);
                if ($line !== '') $out[] = $line;
                continue;
            }
            $parts = [];
            if (!empty($ing['amount'])) $parts[] = trim((string) $ing['amount']);
            if (!empty($ing['unit'])) $parts[] = trim((string) $ing['unit']);
            if (!empty($ing['name'])) $parts[] = trim((string) $ing['name']);
            $line = trim(implode(' ', $parts));
            if ($line !== '') $out[] = $line;
        }
        return $out;
    }

    private static function instructionsToHowToSteps(array $instructions): array {
        $steps = [];
        foreach ($instructions as $i => $step) {
            if (is_string($step)) {
                $text = trim($step);
            } elseif (is_array($step) && isset($step['text'])) {
                $text = trim((string) $step['text']);
            } else {
                $text = trim((string) $step);
            }
            if ($text === '') continue;
            $steps[] = [
                '@type' => 'HowToStep',
                'name' => 'Step ' . ($i + 1),
                'text' => $text,
            ];
        }
        return $steps;
    }

    private static function tagNames(array $tags): array {
        $names = [];
        foreach ($tags as $t) {
            if (is_string($t)) {
                $n = trim($t);
            } elseif (is_array($t)) {
                $n = trim((string) ($t['name'] ?? ''));
            } else {
                $n = '';
            }
            if ($n !== '') $names[] = $n;
        }
        return $names;
    }

    private static function nutrition(array $recipe): ?array {
        $map = [
            'calories' => 'calories',
            'protein'  => 'proteinContent',
            'carbs'    => 'carbohydrateContent',
            'fat'      => 'fatContent',
            'fiber'    => 'fiberContent',
            'sugar'    => 'sugarContent',
        ];
        $n = ['@type' => 'NutritionInformation'];
        $hasAny = false;
        foreach ($map as $src => $schemaKey) {
            $v = $recipe[$src] ?? null;
            if ($v === null || $v === '' || !is_numeric($v)) continue;
            // schema.org expects strings with units. Calories has no unit.
            $n[$schemaKey] = ($src === 'calories') ? (string) (int) $v : ((string) (int) $v) . ' g';
            $hasAny = true;
        }
        return $hasAny ? $n : null;
    }

    private static function imageUrl(?string $path, ?string $base): ?string {
        if (!$path) return null;
        if (preg_match('#^https?://#i', $path)) return $path;
        if ($base === null) return $path;
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }

    private static function toIsoDate(string $datetime): string {
        // Already ISO? Pass through. Otherwise convert "Y-m-d H:i:s" -> ISO 8601.
        if (preg_match('/^\d{4}-\d{2}-\d{2}T/', $datetime)) return $datetime;
        $ts = strtotime($datetime);
        return $ts ? date('c', $ts) : $datetime;
    }
}
