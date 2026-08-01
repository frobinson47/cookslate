<?php
// api/tests/Unit/MealPlanGroceryTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MealPlanGroceryTest extends TestCase
{
    private \PDO $db;
    private \MealPlan $mealPlan;
    private \Recipe $recipe;
    private int $testUserId;
    private int $recipeId;
    private array $groceryListIds = [];

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/Recipe.php';
        require_once __DIR__ . '/../../models/GroceryItem.php';
        require_once __DIR__ . '/../../pro/models/MealPlan.php';

        $this->db = \Database::getInstance();
        $this->mealPlan = new \MealPlan();
        $this->recipe = new \Recipe();

        $this->db->exec("DELETE FROM users WHERE username = 'mealplangrocerytest'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('mealplangrocerytest', 'mealplangrocerytest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();

        $result = $this->recipe->create([
            'title' => 'Grocery Test Recipe',
            'servings' => 2,
            'ingredients' => [['name' => 'flour', 'amount' => '1', 'unit' => 'cup']],
        ], $this->testUserId);
        $this->recipeId = (int) $result['id'];
    }

    protected function tearDown(): void
    {
        foreach ($this->groceryListIds as $id) {
            $this->db->exec("DELETE FROM grocery_lists WHERE id = {$id}");
        }
        $this->db->exec("DELETE FROM recipes WHERE id = {$this->recipeId}");
        $this->db->exec("DELETE FROM meal_plans WHERE user_id = {$this->testUserId}");
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
    }

    public function testGenerateGroceryListReusesSameListForSameWeek(): void
    {
        $plan = $this->mealPlan->getByWeek($this->testUserId, '2026-08-03');
        $this->mealPlan->addItem($plan['id'], $this->recipeId, 0, $this->testUserId, 'dinner');

        $firstId = $this->mealPlan->generateGroceryList($plan['id'], 'Week List', $this->testUserId, '2026-08-03');
        $this->groceryListIds[] = $firstId;
        $secondId = $this->mealPlan->generateGroceryList($plan['id'], 'Week List', $this->testUserId, '2026-08-03');

        $this->assertSame($firstId, $secondId);

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM grocery_lists WHERE created_by = ? AND week_start = ?');
        $stmt->execute([$this->testUserId, '2026-08-03']);
        $this->assertSame(1, (int) $stmt->fetchColumn());
    }

    public function testRegeneratePreservesManuallyAddedItems(): void
    {
        $plan = $this->mealPlan->getByWeek($this->testUserId, '2026-08-10');
        $this->mealPlan->addItem($plan['id'], $this->recipeId, 0, $this->testUserId, 'dinner');

        $listId = $this->mealPlan->generateGroceryList($plan['id'], 'Week List', $this->testUserId, '2026-08-10');
        $this->groceryListIds[] = $listId;

        $groceryItemModel = new \GroceryItem();
        $groceryItemModel->create($listId, 'Paper Towels', null, null, null);

        $this->mealPlan->generateGroceryList($plan['id'], 'Week List', $this->testUserId, '2026-08-10');

        $items = $groceryItemModel->getAllForList($listId);
        $names = array_column($items, 'name');
        $this->assertContains('Paper Towels', $names);
        $this->assertContains('flour', $names);
    }

    public function testGetGroceryListForWeekReturnsNullWhenNoneExists(): void
    {
        $result = $this->mealPlan->getGroceryListForWeek($this->testUserId, '2026-09-21');
        $this->assertNull($result);
    }

    public function testGetGroceryListForWeekReturnsListWithItems(): void
    {
        $plan = $this->mealPlan->getByWeek($this->testUserId, '2026-09-28');
        $this->mealPlan->addItem($plan['id'], $this->recipeId, 0, $this->testUserId, 'dinner');
        $listId = $this->mealPlan->generateGroceryList($plan['id'], 'Week List', $this->testUserId, '2026-09-28');
        $this->groceryListIds[] = $listId;

        $result = $this->mealPlan->getGroceryListForWeek($this->testUserId, '2026-09-28');

        $this->assertNotNull($result);
        $this->assertSame($listId, $result['id']);
        $this->assertCount(1, $result['items']);
    }
}
