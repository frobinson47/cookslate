<?php
// api/tests/Unit/MealPlanTemplateTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MealPlanTemplateTest extends TestCase
{
    private \PDO $db;
    private \MealPlan $mealPlan;
    private \Recipe $recipe;
    private int $testUserId;
    private int $recipeId;
    private array $templateIds = [];

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/Recipe.php';
        require_once __DIR__ . '/../../pro/models/MealPlan.php';

        $this->db = \Database::getInstance();
        $this->mealPlan = new \MealPlan();
        $this->recipe = new \Recipe();

        $this->db->exec("DELETE FROM users WHERE username = 'mealplantemplatetest'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('mealplantemplatetest', 'mealplantemplatetest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();

        $result = $this->recipe->create([
            'title' => 'Template Test Recipe',
            'servings' => 2,
            'ingredients' => [['name' => 'test ingredient', 'amount' => '1', 'unit' => '']],
        ], $this->testUserId);
        $this->recipeId = (int) $result['id'];
    }

    protected function tearDown(): void
    {
        foreach ($this->templateIds as $id) {
            $this->db->exec("DELETE FROM meal_plan_templates WHERE id = {$id}");
        }
        $this->db->exec("DELETE FROM recipes WHERE id = {$this->recipeId}");
        $this->db->exec("DELETE FROM meal_plans WHERE user_id = {$this->testUserId}");
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
    }

    private function planForWeek(string $week): void {
        $plan = $this->mealPlan->getByWeek($this->testUserId, $week);
        $this->mealPlan->addItem($plan['id'], $this->recipeId, 0, $this->testUserId, 'dinner');
        $this->mealPlan->addItem($plan['id'], $this->recipeId, 2, $this->testUserId, 'lunch');
    }

    public function testSaveAsTemplateCapturesItems(): void
    {
        $this->planForWeek('2026-08-03');

        $template = $this->mealPlan->saveAsTemplate($this->testUserId, '2026-08-03', 'My Week');
        $this->templateIds[] = $template['id'];

        $this->assertSame('My Week', $template['name']);
        $this->assertCount(2, $template['items']);
    }

    public function testSaveAsTemplateReturnsNullForEmptyWeek(): void
    {
        $template = $this->mealPlan->saveAsTemplate($this->testUserId, '2026-09-07', 'Empty Week');
        $this->assertNull($template);
    }

    public function testGetTemplatesForUserListsSavedTemplates(): void
    {
        $this->planForWeek('2026-08-10');
        $template = $this->mealPlan->saveAsTemplate($this->testUserId, '2026-08-10', 'Listed Week');
        $this->templateIds[] = $template['id'];

        $templates = $this->mealPlan->getTemplatesForUser($this->testUserId);
        $names = array_column($templates, 'name');
        $this->assertContains('Listed Week', $names);
    }

    public function testApplyTemplateOverwritesTargetWeek(): void
    {
        $this->planForWeek('2026-08-17');
        $template = $this->mealPlan->saveAsTemplate($this->testUserId, '2026-08-17', 'Apply Source');
        $this->templateIds[] = $template['id'];

        // Target week starts with a different single item.
        $targetPlan = $this->mealPlan->getByWeek($this->testUserId, '2026-08-24');
        $this->mealPlan->addItem($targetPlan['id'], $this->recipeId, 5, $this->testUserId, 'breakfast');

        $result = $this->mealPlan->applyTemplate($template['id'], '2026-08-24', $this->testUserId);

        $this->assertNotNull($result);
        $this->assertCount(2, $result['items']);
        $days = array_column($result['items'], 'day_of_week');
        $this->assertEqualsCanonicalizing([0, 2], $days);
    }

    public function testApplyTemplateReturnsNullForUnownedTemplate(): void
    {
        $this->planForWeek('2026-08-31');
        $template = $this->mealPlan->saveAsTemplate($this->testUserId, '2026-08-31', 'Owned By Someone Else');
        $this->templateIds[] = $template['id'];

        $result = $this->mealPlan->applyTemplate($template['id'], '2026-09-07', 999999);
        $this->assertNull($result);
    }

    public function testDeleteTemplateRemovesItAndItsItems(): void
    {
        $this->planForWeek('2026-09-14');
        $template = $this->mealPlan->saveAsTemplate($this->testUserId, '2026-09-14', 'To Delete');

        $deleted = $this->mealPlan->deleteTemplate($template['id'], $this->testUserId);
        $this->assertTrue($deleted);

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM meal_plan_template_items WHERE template_id = ?');
        $stmt->execute([$template['id']]);
        $this->assertSame(0, (int) $stmt->fetchColumn());
    }
}
