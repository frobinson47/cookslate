<?php

/**
 * Converts Cookslate recipes to Cooklang (.cook) format.
 *
 * Cooklang spec: https://cooklang.org/docs/spec
 * - @ingredient{quantity%unit} for ingredients
 * - #cookware{} for equipment
 * - ~timer{quantity%unit} for timers
 * - Metadata as YAML front matter
 */
class CooklangExporter
{
    /**
     * Export a single recipe to Cooklang format.
     *
     * @param array $recipe Recipe data with 'title', 'description', 'prep_time', 'cook_time',
     *                      'servings', 'source_url', 'ingredients', 'instructions', 'tags'
     * @return string Cooklang-formatted recipe
     */
    public function export(array $recipe): string
    {
        $lines = [];

        // Metadata (YAML front matter)
        $lines[] = '---';
        $lines[] = 'title: ' . $this->yamlString($recipe['title'] ?? 'Untitled');

        if (!empty($recipe['source_url'])) {
            $lines[] = 'source: ' . $this->yamlString($recipe['source_url']);
        }
        if (!empty($recipe['servings'])) {
            $lines[] = 'servings: ' . (int) $recipe['servings'];
        }
        if (!empty($recipe['prep_time'])) {
            $lines[] = 'prep_time: ' . (int) $recipe['prep_time'] . ' minutes';
        }
        if (!empty($recipe['cook_time'])) {
            $lines[] = 'cook_time: ' . (int) $recipe['cook_time'] . ' minutes';
        }
        if (!empty($recipe['tags'])) {
            $tagNames = array_map(function ($t) {
                return is_array($t) ? ($t['name'] ?? '') : $t;
            }, $recipe['tags']);
            $tagNames = array_filter($tagNames);
            if (!empty($tagNames)) {
                $lines[] = 'tags:';
                foreach ($tagNames as $tag) {
                    $lines[] = '  - ' . $tag;
                }
            }
        }
        $lines[] = '---';
        $lines[] = '';

        // Description as a note
        if (!empty($recipe['description'])) {
            $lines[] = '> ' . str_replace("\n", "\n> ", trim($recipe['description']));
            $lines[] = '';
        }

        // Build ingredient lookup for inline replacement
        $ingredients = $recipe['ingredients'] ?? [];
        $ingredientIndex = $this->buildIngredientIndex($ingredients);

        // Instructions with inline ingredients
        $instructions = $recipe['instructions'] ?? [];
        if (is_string($instructions)) {
            $instructions = json_decode($instructions, true) ?: [];
        }

        foreach ($instructions as $i => $step) {
            if (is_string($step)) {
                $step = trim($step);
            } elseif (is_array($step) && isset($step['text'])) {
                $step = trim($step['text']);
            } else {
                continue;
            }

            if (empty($step)) continue;

            // Try to replace ingredient names with Cooklang syntax
            $converted = $this->inlineIngredients($step, $ingredientIndex);
            $lines[] = $converted;
            $lines[] = ''; // Blank line between steps
        }

        // Append any ingredients not referenced in instructions
        $unreferenced = $this->getUnreferencedIngredients($ingredientIndex);
        if (!empty($unreferenced)) {
            $lines[] = '-- Ingredients not referenced in steps:';
            foreach ($unreferenced as $ing) {
                $lines[] = '-- ' . $this->formatIngredientTag($ing);
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    /**
     * Export multiple recipes, returning an array of [filename => content].
     */
    public function exportAll(array $recipes): array
    {
        $files = [];
        foreach ($recipes as $recipe) {
            $slug = $this->slugify($recipe['title'] ?? 'recipe');
            $files[$slug . '.cook'] = $this->export($recipe);
        }
        return $files;
    }

    /**
     * Build a lookup index from ingredients for matching against instruction text.
     */
    private function buildIngredientIndex(array $ingredients): array
    {
        $index = [];
        foreach ($ingredients as $ing) {
            $name = trim($ing['name'] ?? '');
            if (empty($name)) continue;

            $index[] = [
                'name' => $name,
                'amount' => trim($ing['amount'] ?? ''),
                'unit' => trim($ing['unit'] ?? ''),
                'used' => false,
                // Create search patterns: full name and last word (for partial matches)
                'patterns' => $this->buildSearchPatterns($name),
            ];
        }

        // Sort by name length descending so longer names match first
        usort($index, function ($a, $b) {
            return strlen($b['name']) - strlen($a['name']);
        });

        return $index;
    }

    /**
     * Build search patterns for an ingredient name.
     */
    private function buildSearchPatterns(string $name): array
    {
        $patterns = [strtolower($name)];

        // Add singular/plural variants
        $lower = strtolower($name);
        if (substr($lower, -1) === 's' && strlen($lower) > 3) {
            $patterns[] = substr($lower, 0, -1); // Remove trailing 's'
        } elseif (substr($lower, -2) === 'es' && strlen($lower) > 4) {
            $patterns[] = substr($lower, 0, -2);
        }

        return array_unique($patterns);
    }

    /**
     * Replace ingredient references in step text with Cooklang @syntax.
     */
    private function inlineIngredients(string $step, array &$index): string
    {
        $lowerStep = strtolower($step);

        foreach ($index as &$ing) {
            foreach ($ing['patterns'] as $pattern) {
                $pos = strpos($lowerStep, $pattern);
                if ($pos !== false) {
                    // Found ingredient in this step — replace first occurrence
                    $originalText = substr($step, $pos, strlen($pattern));
                    $tag = $this->formatIngredientTag($ing);
                    $step = substr_replace($step, $tag, $pos, strlen($pattern));
                    $lowerStep = strtolower($step); // Recalculate after replacement
                    $ing['used'] = true;
                    break; // Only replace once per ingredient per step
                }
            }
        }

        return $step;
    }

    /**
     * Format an ingredient as a Cooklang tag.
     */
    private function formatIngredientTag(array $ing): string
    {
        $name = $ing['name'];
        $amount = $ing['amount'];
        $unit = $ing['unit'];

        // Multi-word names need {} even without quantity
        $needsBraces = str_contains($name, ' ') || !empty($amount);

        if (!empty($amount) && !empty($unit)) {
            return "@{$name}{{$amount}%{$unit}}";
        } elseif (!empty($amount)) {
            return "@{$name}{{$amount}}";
        } elseif ($needsBraces) {
            return "@{$name}{}";
        }
        return "@{$name}";
    }

    /**
     * Get ingredients that weren't referenced in any instruction step.
     */
    private function getUnreferencedIngredients(array $index): array
    {
        return array_filter($index, fn($ing) => !$ing['used']);
    }

    /**
     * Escape a string for YAML.
     */
    private function yamlString(string $value): string
    {
        if (preg_match('/[:#\[\]{}|>*&!%@`]/', $value)) {
            return '"' . addslashes($value) . '"';
        }
        return $value;
    }

    /**
     * Create a URL-safe slug from a recipe title.
     */
    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}
