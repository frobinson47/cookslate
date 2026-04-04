<?php

require_once __DIR__ . '/../models/Database.php';

/**
 * Analyzes recipes for cost estimates and nutrition aggregation.
 * Uses the ingredient_data table for reference prices and nutrition.
 */
class RecipeAnalyzer
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Analyze a recipe's ingredients for cost and nutrition.
     *
     * @param array $ingredients Array of ingredients with 'name', 'amount', 'unit'
     * @param int|null $servings Number of servings
     * @return array Analysis results
     */
    public function analyze(array $ingredients, ?int $servings = null): array
    {
        $totalCost = 0;
        $totalCalories = 0;
        $totalProtein = 0;
        $totalCarbs = 0;
        $totalFat = 0;
        $totalFiber = 0;
        $matched = 0;
        $unmatched = [];
        $breakdown = [];

        foreach ($ingredients as $ing) {
            $name = strtolower(trim($ing['name'] ?? ''));
            if (empty($name)) continue;

            $data = $this->findIngredientData($name);
            if (!$data) {
                $unmatched[] = $name;
                continue;
            }

            $matched++;
            $entry = [
                'name' => $ing['name'],
                'category' => $data['category'],
            ];

            if ($data['avg_price'] !== null) {
                $entry['price'] = (float) $data['avg_price'];
                $entry['price_unit'] = $data['price_unit'];
                $totalCost += $entry['price'];
            }

            if ($data['calories_per_100g'] !== null) {
                // Rough estimate: assume ~100g per ingredient as a baseline
                // This is imprecise but gives a useful ballpark
                $entry['calories'] = (float) $data['calories_per_100g'];
                $entry['protein'] = (float) $data['protein_per_100g'];
                $entry['carbs'] = (float) $data['carbs_per_100g'];
                $entry['fat'] = (float) $data['fat_per_100g'];
                $entry['fiber'] = (float) $data['fiber_per_100g'];

                $totalCalories += $entry['calories'];
                $totalProtein += $entry['protein'];
                $totalCarbs += $entry['carbs'];
                $totalFat += $entry['fat'];
                $totalFiber += $entry['fiber'];
            }

            $breakdown[] = $entry;
        }

        $result = [
            'cost' => [
                'total' => round($totalCost, 2),
                'per_serving' => $servings ? round($totalCost / $servings, 2) : null,
                'currency' => 'USD',
            ],
            'nutrition' => [
                'calories' => round($totalCalories),
                'protein' => round($totalProtein, 1),
                'carbs' => round($totalCarbs, 1),
                'fat' => round($totalFat, 1),
                'fiber' => round($totalFiber, 1),
                'per_serving' => $servings ? [
                    'calories' => round($totalCalories / $servings),
                    'protein' => round($totalProtein / $servings, 1),
                    'carbs' => round($totalCarbs / $servings, 1),
                    'fat' => round($totalFat / $servings, 1),
                    'fiber' => round($totalFiber / $servings, 1),
                ] : null,
            ],
            'coverage' => [
                'matched' => $matched,
                'total' => count($ingredients),
                'percent' => count($ingredients) > 0 ? round(($matched / count($ingredients)) * 100) : 0,
                'unmatched' => $unmatched,
            ],
            'breakdown' => $breakdown,
        ];

        return $result;
    }

    /**
     * Analyze a meal plan's total nutrition and cost.
     *
     * @param array $recipes Array of recipes with 'ingredients' and 'servings'
     * @return array Aggregated analysis
     */
    public function analyzeMealPlan(array $recipes): array
    {
        $totalCost = 0;
        $totalCalories = 0;
        $totalProtein = 0;
        $totalCarbs = 0;
        $totalFat = 0;
        $recipeAnalyses = [];

        foreach ($recipes as $recipe) {
            $analysis = $this->analyze(
                $recipe['ingredients'] ?? [],
                $recipe['servings'] ?? null
            );
            $recipeAnalyses[] = [
                'title' => $recipe['title'] ?? 'Unknown',
                'cost' => $analysis['cost']['total'],
                'calories' => $analysis['nutrition']['calories'],
            ];
            $totalCost += $analysis['cost']['total'];
            $totalCalories += $analysis['nutrition']['calories'];
            $totalProtein += $analysis['nutrition']['protein'];
            $totalCarbs += $analysis['nutrition']['carbs'];
            $totalFat += $analysis['nutrition']['fat'];
        }

        return [
            'total_cost' => round($totalCost, 2),
            'total_nutrition' => [
                'calories' => round($totalCalories),
                'protein' => round($totalProtein, 1),
                'carbs' => round($totalCarbs, 1),
                'fat' => round($totalFat, 1),
            ],
            'recipe_count' => count($recipes),
            'recipes' => $recipeAnalyses,
        ];
    }

    /**
     * Find ingredient data by fuzzy matching the name.
     */
    private function findIngredientData(string $name): ?array
    {
        // Exact match first
        $stmt = $this->db->prepare('SELECT * FROM ingredient_data WHERE LOWER(name) = ?');
        $stmt->execute([$name]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) return $row;

        // Substring match — check if any reference ingredient is contained in the name
        $stmt = $this->db->prepare('SELECT * FROM ingredient_data WHERE ? LIKE CONCAT(\'%\', LOWER(name), \'%\') ORDER BY LENGTH(name) DESC LIMIT 1');
        $stmt->execute([$name]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) return $row;

        // Reverse: check if the name is contained in any reference ingredient
        $stmt = $this->db->prepare('SELECT * FROM ingredient_data WHERE LOWER(name) LIKE CONCAT(\'%\', ?, \'%\') ORDER BY LENGTH(name) ASC LIMIT 1');
        $stmt->execute([$name]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}
