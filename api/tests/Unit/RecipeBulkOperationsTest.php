<?php
// api/tests/Unit/RecipeBulkOperationsTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RecipeBulkOperationsTest extends TestCase
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

        $this->db->exec("DELETE FROM users WHERE username = 'bulkoptest'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('bulkoptest', 'bulkoptest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();

        foreach (['Bulk Test Recipe A', 'Bulk Test Recipe B'] as $title) {
            $result = $this->recipe->create(['title' => $title], $this->testUserId);
            $this->recipeIds[] = (int) $result['id'];
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->recipeIds as $id) {
            $this->db->exec("DELETE FROM recipes WHERE id = {$id}");
        }
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
        $this->db->exec("DELETE FROM tags WHERE name IN ('weeknight', 'quick')");
    }

    public function testAddTagsCreatesAndLinksNewTags(): void
    {
        $this->recipe->addTags($this->recipeIds[0], ['weeknight', 'quick']);

        $found = $this->recipe->findById($this->recipeIds[0]);
        $tagNames = array_column($found['tags'], 'name');
        $this->assertContains('weeknight', $tagNames);
        $this->assertContains('quick', $tagNames);
    }

    public function testAddTagsIsAdditiveNotReplacing(): void
    {
        $this->recipe->addTags($this->recipeIds[0], ['weeknight']);
        $this->recipe->addTags($this->recipeIds[0], ['quick']);

        $found = $this->recipe->findById($this->recipeIds[0]);
        $tagNames = array_column($found['tags'], 'name');
        $this->assertContains('weeknight', $tagNames);
        $this->assertContains('quick', $tagNames);
    }

    public function testAddTagsIsIdempotentForExistingTag(): void
    {
        $this->recipe->addTags($this->recipeIds[0], ['weeknight']);
        $this->recipe->addTags($this->recipeIds[0], ['weeknight']);

        $found = $this->recipe->findById($this->recipeIds[0]);
        $tagNames = array_column($found['tags'], 'name');
        $this->assertCount(1, array_filter($tagNames, fn($n) => $n === 'weeknight'));
    }

    public function testAddTagsSkipsBlankNames(): void
    {
        $this->recipe->addTags($this->recipeIds[0], ['', '  ', 'quick']);

        $found = $this->recipe->findById($this->recipeIds[0]);
        $this->assertCount(1, $found['tags']);
        $this->assertSame('quick', $found['tags'][0]['name']);
    }

    public function testIsCreatorTrueForOwnerFalseForOther(): void
    {
        $this->assertTrue($this->recipe->isCreator($this->recipeIds[0], $this->testUserId));
        $this->assertFalse($this->recipe->isCreator($this->recipeIds[0], 99999));
    }
}
