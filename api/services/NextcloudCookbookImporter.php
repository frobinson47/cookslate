<?php
require_once __DIR__ . '/IngredientParser.php';

/**
 * Imports recipes from a Nextcloud Cookbook export ZIP. Nextcloud Cookbook
 * stores each recipe as its own folder containing a recipe.json in
 * schema.org/Recipe JSON-LD shape — the same underlying format Tandoor
 * exports, so this mirrors TandoorImporter's parsing approach.
 */
class NextcloudCookbookImporter {

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
            // Nextcloud Cookbook names each recipe's file recipe.json, one per folder.
            if (!preg_match('/(^|\/)recipe\.json$/i', $filename)) continue;

            $content = $zip->getFromIndex($i);
            if ($content === false) continue;

            $data = json_decode($content, true);
            if (!is_array($data)) continue;

            $type = $data['@type'] ?? '';
            if ($type !== 'Recipe' && !str_contains(strtolower((string) $type), 'recipe')) continue;

            try {
                $mapped = $this->mapRecipe($data, $parser);
                $results[] = ['status' => 'success', 'recipe' => $mapped];
            } catch (\Throwable $e) {
                $results[] = [
                    'status' => 'error',
                    'error_message' => 'Error: ' . ($data['name'] ?? 'unknown') . ': ' . $e->getMessage(),
                ];
            }
        }

        $zip->close();

        if (empty($results)) {
            return ['results' => [
                ['status' => 'error', 'error_message' => 'No recipes found in ZIP. Is this a Nextcloud Cookbook export?']
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
        // Nextcloud Cookbook stores its own categories under "recipeCategory"
        // in addition to the standard schema.org "keywords".
        foreach ([$data['recipeCategory'] ?? null, $data['keywords'] ?? null] as $source) {
            if (is_string($source)) {
                foreach (array_filter(array_map('trim', explode(',', $source))) as $t) {
                    $tags[] = $t;
                }
            } elseif (is_array($source)) {
                foreach ($source as $kw) {
                    $name = is_string($kw) ? $kw : ($kw['name'] ?? '');
                    if (trim($name) !== '') $tags[] = trim($name);
                }
            }
        }
        $tags = array_values(array_unique($tags));

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
