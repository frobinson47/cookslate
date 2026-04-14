<?php
require_once __DIR__ . '/IngredientParser.php';

class TandoorImporter {

    public function import(string $zipPath): array {
        if (!class_exists('ZipArchive')) {
            return ['results' => [
                ['status' => 'error', 'error_message' => 'ZIP support is not available.']
            ]];
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return ['results' => [
                ['status' => 'error', 'error_message' => 'Failed to open zip file.']
            ]];
        }

        $parser = new IngredientParser();
        $results = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (!preg_match('/\.json$/i', $filename)) continue;

            $content = $zip->getFromIndex($i);
            if ($content === false) continue;

            $data = json_decode($content, true);
            if (!is_array($data)) continue;

            $recipes = isset($data['@type']) ? [$data] : (isset($data[0]) ? $data : [$data]);

            foreach ($recipes as $recipe) {
                $type = $recipe['@type'] ?? '';
                if ($type !== 'Recipe' && !str_contains(strtolower($type), 'recipe')) continue;

                try {
                    $mapped = $this->mapRecipe($recipe, $parser);
                    $results[] = ['status' => 'success', 'recipe' => $mapped];
                } catch (\Throwable $e) {
                    $results[] = [
                        'status' => 'error',
                        'error_message' => 'Error: ' . ($recipe['name'] ?? 'unknown') . ': ' . $e->getMessage(),
                    ];
                }
            }
        }

        $zip->close();

        if (empty($results)) {
            return ['results' => [
                ['status' => 'error', 'error_message' => 'No recipes found in ZIP. Is this a Tandoor export?']
            ]];
        }

        return ['results' => $results];
    }

    private function mapRecipe(array $data, IngredientParser $parser): array {
        $title = $data['name'] ?? 'Untitled';
        $description = $data['description'] ?? null;

        $prepTime = $this->parseDuration($data['prepTime'] ?? null);
        $cookTime = $this->parseDuration($data['cookTime'] ?? null);

        $servings = null;
        $yield = $data['recipeYield'] ?? null;
        if ($yield !== null) {
            if (is_numeric($yield)) {
                $servings = (int) $yield;
            } elseif (preg_match('/(\d+)/', (string) $yield, $m)) {
                $servings = (int) $m[1];
            }
        }

        $ingredients = [];
        $rawIngredients = $data['recipeIngredient'] ?? [];
        foreach ($rawIngredients as $i => $ing) {
            $text = is_string($ing) ? trim($ing) : trim($ing['text'] ?? '');
            if ($text === '') continue;
            $parsed = $parser->parse($text);
            $parsed['sort_order'] = $i;
            $ingredients[] = $parsed;
        }

        $instructions = [];
        $rawInstructions = $data['recipeInstructions'] ?? [];
        if (is_string($rawInstructions)) {
            $instructions = array_filter(array_map('trim', preg_split('/\n+/', $rawInstructions)));
        } else {
            foreach ($rawInstructions as $step) {
                $text = is_string($step) ? trim($step) : trim($step['text'] ?? '');
                if ($text !== '') $instructions[] = $text;
            }
        }

        $tags = [];
        $keywords = $data['keywords'] ?? [];
        if (is_string($keywords)) {
            $tags = array_filter(array_map('trim', explode(',', $keywords)));
        } elseif (is_array($keywords)) {
            foreach ($keywords as $kw) {
                $name = is_string($kw) ? $kw : ($kw['name'] ?? '');
                if (trim($name) !== '') $tags[] = trim($name);
            }
        }

        $sourceUrl = $data['url'] ?? null;
        $sourceImageUrl = null;
        $image = $data['image'] ?? null;
        if (is_string($image)) {
            $sourceImageUrl = $image;
        } elseif (is_array($image)) {
            $sourceImageUrl = $image['url'] ?? ($image[0] ?? null);
        }

        return [
            'title' => $title,
            'description' => $description,
            'prep_time' => $prepTime,
            'cook_time' => $cookTime,
            'servings' => $servings,
            'ingredients' => $ingredients,
            'instructions' => $instructions,
            'tags' => $tags,
            'source_url' => $sourceUrl,
            'source_image_url' => $sourceImageUrl,
        ];
    }

    private function parseDuration(?string $value): ?int {
        if ($value === null || $value === '') return null;
        if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $value, $m)) {
            $minutes = ((int)($m[1] ?? 0)) * 60 + (int)($m[2] ?? 0);
            return $minutes > 0 ? $minutes : null;
        }
        return null;
    }
}
