<?php
// api/tests/Unit/RecipeCostCacheTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RecipeCostCacheTest extends TestCase
{
    private \PDO $db;
    private \Recipe $recipe;
    private int $testUserId;
    private array $recipeIds = [];

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/Recipe.php';

        $this->db = \Database::getInstance();
        $this->recipe = new \Recipe();

        $this->db->exec("DELETE FROM users WHERE username = 'costcachetest'");
        $this->db->exec("DELETE FROM ingredient_data WHERE name = 'costcache widget'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('costcachetest', 'costcachetest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();

        $this->db->exec("INSERT INTO ingredient_data (name, category, avg_price, price_unit) VALUES ('costcache widget', 'Baking', 4.00, 'each')");
    }

    protected function tearDown(): void
    {
        foreach ($this->recipeIds as $id) {
            $this->db->exec("DELETE FROM recipes WHERE id = {$id}");
        }
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
        $this->db->exec("DELETE FROM ingredient_data WHERE name = 'costcache widget'");
    }

    public function testCreateCachesEstimatedCostPerServing(): void
    {
        $result = $this->recipe->create([
            'title' => 'Cost Cache Recipe',
            'servings' => 2,
            'ingredients' => [
                ['name' => 'costcache widget', 'amount' => '2', 'unit' => ''],
            ],
        ], $this->testUserId);
        $this->recipeIds[] = (int) $result['id'];

        $found = $this->recipe->findById((int) $result['id']);
        $this->assertNotNull($found['estimated_cost_per_serving']);
        $this->assertGreaterThan(0, (float) $found['estimated_cost_per_serving']);
    }

    public function testUpdateRecalculatesCost(): void
    {
        // RecipeAnalyzer prices per-ingredient-use (avg_price is a flat cost
        // for the ingredient as used, not scaled by amount) — so changing
        // servings from 1 to 2 is what should visibly change per-serving cost.
        $result = $this->recipe->create([
            'title' => 'Cost Cache Recipe 2',
            'servings' => 1,
            'ingredients' => [
                ['name' => 'costcache widget', 'amount' => '1', 'unit' => ''],
            ],
        ], $this->testUserId);
        $recipeId = (int) $result['id'];
        $this->recipeIds[] = $recipeId;

        $firstCost = (float) $this->recipe->findById($recipeId)['estimated_cost_per_serving'];

        $this->recipe->update($recipeId, ['servings' => 2]);

        $secondCost = (float) $this->recipe->findById($recipeId)['estimated_cost_per_serving'];
        $this->assertLessThan($firstCost, $secondCost);
    }

    public function testUnmatchedIngredientsLeaveCostNull(): void
    {
        $result = $this->recipe->create([
            'title' => 'No Cost Data Recipe',
            'servings' => 2,
            'ingredients' => [
                ['name' => 'totally unrecognized ingredient xyz', 'amount' => '1', 'unit' => ''],
            ],
        ], $this->testUserId);
        $this->recipeIds[] = (int) $result['id'];

        $found = $this->recipe->findById((int) $result['id']);
        $this->assertNull($found['estimated_cost_per_serving']);
    }
}
