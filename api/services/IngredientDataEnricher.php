<?php

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/GroceryItem.php';
require_once __DIR__ . '/OpenFoodFactsClient.php';
require_once __DIR__ . '/LoggerService.php';

/**
 * Best-effort enrichment of ingredient_data from Open Food Facts, triggered
 * after a receipt or pantry-scan import. Only attaches nutrition when a
 * scanned item name confidently matches a known branded product — never
 * guesses. Failures (API down, no match) are silent; this is a nice-to-have
 * enrichment, not a required step in the scan flow.
 */
class IngredientDataEnricher
{
    private PDO $db;
    private OpenFoodFactsClient $client;

    public function __construct(?OpenFoodFactsClient $client = null)
    {
        $this->db = Database::getInstance();
        $this->client = $client ?? new OpenFoodFactsClient();
    }

    /**
     * @param string[] $itemNames Raw item names from a receipt or pantry scan.
     * @return string[] Names that were successfully enriched, for logging.
     */
    public function enrichFromScan(array $itemNames): array
    {
        $enriched = [];

        foreach ($itemNames as $name) {
            $name = trim((string) $name);
            if ($name === '') continue;

            try {
                if ($this->alreadyHasNutrition($name)) continue;

                $match = $this->findConfidentMatch($name);
                if ($match === null) continue;

                $this->upsert($name, $match);
                $enriched[] = $name;
            } catch (\Throwable $e) {
                // Best-effort — never let enrichment failures break the scan flow.
                LoggerService::channel('ingredient_enrichment')->warning('Enrichment failed', [
                    'name' => $name,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $enriched;
    }

    private function alreadyHasNutrition(string $name): bool
    {
        $normalized = GroceryItem::normalizeForMatch($name) ?: strtolower(trim($name));
        $stmt = $this->db->prepare('SELECT 1 FROM ingredient_data WHERE LOWER(name) = ? AND calories_per_100g IS NOT NULL');
        $stmt->execute([$normalized]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Search Open Food Facts and only accept the top result if its product
     * name confidently matches the scanned item name after normalization
     * (exact match, or one fully contains the other with a small length gap).
     */
    private function findConfidentMatch(string $name): ?array
    {
        $normalizedQuery = GroceryItem::normalizeForMatch($name) ?: strtolower(trim($name));
        if ($normalizedQuery === '') return null;

        $results = $this->client->search($name, 3);
        foreach ($results as $product) {
            $productName = trim((string) ($product['name'] ?? ''));
            if ($productName === '') continue;
            $normalizedProduct = strtolower($productName);

            if ($normalizedProduct === $normalizedQuery) {
                return $product;
            }

            $shorter = strlen($normalizedProduct) < strlen($normalizedQuery) ? $normalizedProduct : $normalizedQuery;
            $longer = strlen($normalizedProduct) < strlen($normalizedQuery) ? $normalizedQuery : $normalizedProduct;
            if (strlen($shorter) >= 4 && str_contains($longer, $shorter) && strlen($longer) - strlen($shorter) <= 8) {
                return $product;
            }
        }

        return null;
    }

    private function upsert(string $name, array $product): void
    {
        $normalized = GroceryItem::normalizeForMatch($name) ?: strtolower(trim($name));
        $nutrition = $product['nutrition'] ?? [];
        $category = !empty($product['categories']) ? explode(',', $product['categories'])[0] : null;

        $stmt = $this->db->prepare('
            INSERT INTO ingredient_data (name, category, calories_per_100g, protein_per_100g, carbs_per_100g, fat_per_100g, fiber_per_100g)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                category = COALESCE(category, VALUES(category)),
                calories_per_100g = VALUES(calories_per_100g),
                protein_per_100g = VALUES(protein_per_100g),
                carbs_per_100g = VALUES(carbs_per_100g),
                fat_per_100g = VALUES(fat_per_100g),
                fiber_per_100g = VALUES(fiber_per_100g)
        ');
        $stmt->execute([
            $normalized,
            $category,
            $nutrition['calories_per_100g'] ?? null,
            $nutrition['protein_per_100g'] ?? null,
            $nutrition['carbs_per_100g'] ?? null,
            $nutrition['fat_per_100g'] ?? null,
            $nutrition['fiber_per_100g'] ?? null,
        ]);
    }
}
