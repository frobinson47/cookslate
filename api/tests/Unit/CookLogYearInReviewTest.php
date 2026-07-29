<?php
// api/tests/Unit/CookLogYearInReviewTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CookLogYearInReviewTest extends TestCase
{
    private \PDO $db;
    private \CookLog $cookLog;
    private \Recipe $recipe;
    private int $testUserId;
    private array $recipeIds = [];

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/CookLog.php';
        require_once __DIR__ . '/../../models/Recipe.php';

        $this->db = \Database::getInstance();
        $this->cookLog = new \CookLog();
        $this->recipe = new \Recipe();

        $this->db->exec("DELETE FROM users WHERE username = 'yearinreviewtest'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('yearinreviewtest', 'yearinreviewtest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();

        foreach (['YIR Recipe A', 'YIR Recipe B'] as $title) {
            $result = $this->recipe->create(['title' => $title], $this->testUserId);
            $this->recipeIds[] = (int) $result['id'];
        }
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE FROM cook_log WHERE user_id = {$this->testUserId}");
        foreach ($this->recipeIds as $id) {
            $this->db->exec("DELETE FROM recipes WHERE id = {$id}");
        }
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
    }

    private function logCook(int $recipeId, string $date): void {
        $stmt = $this->db->prepare('INSERT INTO cook_log (user_id, recipe_id, cooked_at) VALUES (?, ?, ?)');
        $stmt->execute([$this->testUserId, $recipeId, $date]);
    }

    public function testTotalMealsAndUniqueRecipesForYear(): void
    {
        $this->logCook($this->recipeIds[0], '2026-01-05 12:00:00');
        $this->logCook($this->recipeIds[0], '2026-02-10 12:00:00');
        $this->logCook($this->recipeIds[1], '2025-12-31 12:00:00'); // different year, excluded

        $result = $this->cookLog->getYearInReview($this->testUserId, 2026);

        $this->assertSame(2, $result['total_meals']);
        $this->assertSame(1, $result['unique_recipes']);
    }

    public function testMostActiveMonth(): void
    {
        $this->logCook($this->recipeIds[0], '2026-03-01 12:00:00');
        $this->logCook($this->recipeIds[0], '2026-03-15 12:00:00');
        $this->logCook($this->recipeIds[1], '2026-06-01 12:00:00');

        $result = $this->cookLog->getYearInReview($this->testUserId, 2026);

        $this->assertSame('2026-03', $result['most_active_month']['month']);
        $this->assertSame(2, $result['most_active_month']['count']);
    }

    public function testMostMadeRecipe(): void
    {
        $this->logCook($this->recipeIds[0], '2026-01-01 12:00:00');
        $this->logCook($this->recipeIds[0], '2026-01-02 12:00:00');
        $this->logCook($this->recipeIds[1], '2026-01-03 12:00:00');

        $result = $this->cookLog->getYearInReview($this->testUserId, 2026);

        $this->assertSame($this->recipeIds[0], $result['most_made_recipe']['id']);
        $this->assertSame(2, $result['most_made_recipe']['cook_count']);
    }

    public function testNewRecipesTriedExcludesPriorYearRecipes(): void
    {
        $this->logCook($this->recipeIds[0], '2025-06-01 12:00:00');
        $this->logCook($this->recipeIds[0], '2026-01-01 12:00:00'); // repeat, not new
        $this->logCook($this->recipeIds[1], '2026-02-01 12:00:00'); // first time ever, new

        $result = $this->cookLog->getYearInReview($this->testUserId, 2026);

        $this->assertSame(1, $result['new_recipes_tried']);
    }

    public function testStreakPeakFindsLongestConsecutiveRun(): void
    {
        $this->logCook($this->recipeIds[0], '2026-01-01 12:00:00');
        $this->logCook($this->recipeIds[0], '2026-01-02 12:00:00');
        $this->logCook($this->recipeIds[0], '2026-01-03 12:00:00');
        // gap
        $this->logCook($this->recipeIds[1], '2026-01-10 12:00:00');
        $this->logCook($this->recipeIds[1], '2026-01-11 12:00:00');

        $result = $this->cookLog->getYearInReview($this->testUserId, 2026);

        $this->assertSame(3, $result['streak_peak']);
    }

    public function testEmptyYearReturnsZeroedResult(): void
    {
        $result = $this->cookLog->getYearInReview($this->testUserId, 2026);

        $this->assertSame(0, $result['total_meals']);
        $this->assertSame(0, $result['streak_peak']);
        $this->assertNull($result['most_active_month']);
        $this->assertNull($result['most_made_recipe']);
    }
}
