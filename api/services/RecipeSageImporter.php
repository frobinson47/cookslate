<?php
require_once __DIR__ . '/IngredientParser.php';

/**
 * Imports recipes from a RecipeSage export ZIP. RecipeSage's export is a
 * ZIP containing a top-level data.json with a "recipes" array; each recipe
 * stores ingredients/instructions as a single multi-line plain-text string
 * (not structured arrays) and free-text time fields like "15 minutes"
 * rather than ISO8601 durations.
 *
 * NOTE: built from documented format knowledge, not verified against a
 * real RecipeSage export file — field names or shapes may need adjustment
 * if real-world exports differ.
 */
class RecipeSageImporter {

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

        $data = null;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (!preg_match('/(^|\/)data\.json$/i', $filename)) continue;
            $content = $zip->getFromIndex($i);
            if ($content === false) continue;
            $decoded = json_decode($content, true);
            if (is_array($decoded) && isset($decoded['recipes'])) {
                $data = $decoded;
                break;
            }
        }
        $zip->close();

        if ($data === null || empty($data['recipes'])) {
            return ['results' => [
                ['status' => 'error', 'error_message' => 'No recipes found in ZIP. Is this a RecipeSage export?']
            ]];
        }

        $parser = new IngredientParser();
        $results = [];

        foreach ($data['recipes'] as $recipe) {
            if (!is_array($recipe)) continue;
            try {
                $mapped = $this->mapRecipe($recipe, $parser);
                $results[] = ['status' => 'success', 'recipe' => $mapped];
            } catch (\Throwable $e) {
                $results[] = [
                    'status' => 'error',
                    'error_message' => 'Error: ' . ($recipe['title'] ?? 'unknown') . ': ' . $e->getMessage(),
                ];
            }
        }

        if (empty($results)) {
            return ['results' => [
                ['status' => 'error', 'error_message' => 'No recipes found in ZIP. Is this a RecipeSage export?']
            ]];
        }

        return ['results' => $results];
    }

    private function mapRecipe(array $data, IngredientParser $parser): array {
        $title = $data['title'] ?? 'Untitled';
        $description = $data['description'] ?? null;

        $prepTime = $this->parseFreeTextMinutes($data['activeTime'] ?? null);
        $cookTime = $this->parseFreeTextMinutes($data['totalTime'] ?? null);

        $servings = null;
        $yield = $data['yield'] ?? null;
        if ($yield !== null && preg_match('/(\d+)/', (string) $yield, $m)) {
            $servings = (int) $m[1];
        }

        $ingredients = [];
        $rawIngredients = $data['ingredients'] ?? [];
        $ingredientLines = is_array($rawIngredients)
            ? $rawIngredients
            : array_filter(array_map('trim', preg_split('/\r?\n/', (string) $rawIngredients)));
        foreach (array_values($ingredientLines) as $i => $line) {
            $text = trim((string) $line);
            if ($text === '') continue;
            $parsed = $parser->parse($text);
            $parsed['sort_order'] = $i;
            $ingredients[] = $parsed;
        }

        $rawInstructions = $data['instructions'] ?? [];
        $instructionLines = is_array($rawInstructions)
            ? $rawInstructions
            : array_filter(array_map('trim', preg_split('/\r?\n/', (string) $rawInstructions)));
        $instructions = array_values(array_filter(array_map('trim', $instructionLines), fn($s) => $s !== ''));

        $tags = [];
        foreach (($data['labels'] ?? []) as $label) {
            $name = is_string($label) ? $label : ($label['title'] ?? $label['name'] ?? '');
            if (trim($name) !== '') $tags[] = trim($name);
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
            'source_url' => $data['source'] ?? ($data['url'] ?? null),
            'source_image_url' => null,
        ];
    }

    /**
     * RecipeSage stores times as free text like "15 minutes" or "1 hour 30 minutes"
     * rather than ISO8601 durations.
     */
    private function parseFreeTextMinutes(?string $value): ?int {
        if ($value === null || trim($value) === '') return null;

        $minutes = 0;
        if (preg_match('/(\d+)\s*h/i', $value, $m)) {
            $minutes += ((int) $m[1]) * 60;
        }
        if (preg_match('/(\d+)\s*m/i', $value, $m)) {
            $minutes += (int) $m[1];
        }
        if ($minutes === 0 && preg_match('/^\s*(\d+)\s*$/', $value, $m)) {
            $minutes = (int) $m[1];
        }

        return $minutes > 0 ? $minutes : null;
    }
}
