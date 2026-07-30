<?php
// api/tests/Unit/HouseholdSharedVisibilityTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HouseholdSharedVisibilityTest extends TestCase
{
    private \PDO $db;
    private int $userA;
    private int $userB;
    private \Recipe $recipe;
    private int $recipeId;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/Collection.php';
        require_once __DIR__ . '/../../models/GroceryList.php';
        require_once __DIR__ . '/../../models/Pantry.php';
        require_once __DIR__ . '/../../models/Recipe.php';
        require_once __DIR__ . '/../../pro/models/MealPlan.php';

        $this->db = \Database::getInstance();
        $this->db->exec("DELETE FROM users WHERE username IN ('householdtesta', 'householdtestb')");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('householdtesta', 'householdtesta@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->userA = (int) $this->db->lastInsertId();

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('householdtestb', 'householdtestb@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->userB = (int) $this->db->lastInsertId();

        $this->recipe = new \Recipe();
        $result = $this->recipe->create([
            'title' => 'Household Test Recipe',
            'servings' => 2,
            'ingredients' => [['name' => 'test ingredient', 'amount' => '1', 'unit' => '']],
        ], $this->userA);
        $this->recipeId = (int) $result['id'];
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE FROM recipes WHERE id = {$this->recipeId}");
        $this->db->exec("DELETE FROM collections WHERE created_by IN ({$this->userA}, {$this->userB})");
        $this->db->exec("DELETE FROM grocery_lists WHERE created_by IN ({$this->userA}, {$this->userB})");
        $this->db->exec("DELETE FROM pantry WHERE user_id IN ({$this->userA}, {$this->userB})");
        $this->db->exec("DELETE FROM meal_plans WHERE user_id IN ({$this->userA}, {$this->userB})");
        $this->db->exec("DELETE FROM users WHERE id IN ({$this->userA}, {$this->userB})");
    }

    public function testCollectionsAreVisibleAcrossUsers(): void
    {
        $model = new \Collection();
        $model->create('User B Collection', $this->userB);

        $asA = $model->getAllForUser($this->userA);
        $names = array_column($asA, 'name');
        $this->assertContains('User B Collection', $names);

        $bCollection = current(array_filter($asA, fn($c) => $c['name'] === 'User B Collection'));
        $this->assertFalse($bCollection['is_owner']);
        $this->assertSame('householdtestb', $bCollection['created_by_username']);
    }

    public function testGroceryListsAreVisibleAcrossUsers(): void
    {
        $model = new \GroceryList();
        $model->create('User B Grocery List', $this->userB);

        $asA = $model->getAllForUser($this->userA);
        $names = array_column($asA, 'name');
        $this->assertContains('User B Grocery List', $names);

        $bList = current(array_filter($asA, fn($l) => $l['name'] === 'User B Grocery List'));
        $this->assertFalse($bList['is_owner']);
    }

    public function testPantryItemsAreVisibleAcrossUsers(): void
    {
        $model = new \Pantry();
        $model->add($this->userB, 'householdtest shared milk');

        $asA = $model->getAllForUser($this->userA);
        $names = array_column($asA, 'ingredient_name');
        $this->assertContains('householdtest shared milk', $names);

        $bItem = current(array_filter($asA, fn($i) => $i['ingredient_name'] === 'householdtest shared milk'));
        $this->assertFalse($bItem['is_owner']);
    }

    public function testPantryMatchesAreHouseholdWide(): void
    {
        $model = new \Pantry();
        $item = $model->add($this->userB, 'householdtest stocked flour');
        $this->db->prepare('UPDATE pantry SET always_stocked = 1 WHERE id = ?')->execute([$item['id']]);

        // Checked as userA, but the stock belongs to userB — should still match.
        $this->assertTrue($model->isInPantry($this->userA, 'householdtest stocked flour'));
        $matches = $model->getPantryMatches($this->userA, ['householdtest stocked flour', 'something else']);
        $this->assertContains('householdtest stocked flour', $matches);
    }

    public function testMealPlanViewOfAnotherUserIsReadOnlyAndDoesNotAutoCreate(): void
    {
        $model = new \MealPlan();

        // userB has never opened a meal plan for this week — viewing it from
        // userA's session must not create a row for userB.
        $plan = $model->getByWeek($this->userA, '2026-09-21', $this->userB);

        $this->assertNull($plan['id']);
        $this->assertFalse($plan['is_owner']);
        $this->assertSame([], $plan['items']);

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM meal_plans WHERE user_id = ?');
        $stmt->execute([$this->userB]);
        $this->assertSame(0, (int) $stmt->fetchColumn());
    }

    public function testMealPlanViewOfAnotherUsersExistingPlanShowsItsItems(): void
    {
        $model = new \MealPlan();
        $bPlan = $model->getByWeek($this->userB, '2026-09-28');
        $model->addItem($bPlan['id'], $this->recipeId, 0, $this->userB);

        $viewedByA = $model->getByWeek($this->userA, '2026-09-28', $this->userB);

        $this->assertFalse($viewedByA['is_owner']);
        $this->assertCount(1, $viewedByA['items']);
    }

    public function testMealPlanUpdateItemDeniedForNonOwnerNonAdmin(): void
    {
        $model = new \MealPlan();
        $bPlan = $model->getByWeek($this->userB, '2026-10-05');
        $item = $model->addItem($bPlan['id'], $this->recipeId, 0, $this->userB);

        $deniedForA = $model->updateItem($item['id'], ['sort_order' => 1], $this->userA, false);
        $this->assertFalse($deniedForA);

        $allowedForAdmin = $model->updateItem($item['id'], ['sort_order' => 1], $this->userA, true);
        $this->assertTrue($allowedForAdmin);
    }
}
