<?php
// api/tests/Unit/CollectionBulkOperationsTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CollectionBulkOperationsTest extends TestCase
{
    private \PDO $db;
    private \Collection $collections;
    private \Recipe $recipe;
    private int $testUserId;
    private int $collectionId;
    private array $recipeIds = [];

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/Collection.php';
        require_once __DIR__ . '/../../models/Recipe.php';

        $this->db = \Database::getInstance();
        $this->collections = new \Collection();
        $this->recipe = new \Recipe();

        $this->db->exec("DELETE FROM users WHERE username = 'collectionbulktest'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('collectionbulktest', 'collectionbulktest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();

        $collection = $this->collections->create('Bulk Test Collection', $this->testUserId);
        $this->collectionId = (int) $collection['id'];

        foreach (['Collection Bulk Recipe A', 'Collection Bulk Recipe B'] as $title) {
            $result = $this->recipe->create(['title' => $title], $this->testUserId);
            $this->recipeIds[] = (int) $result['id'];
        }
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE FROM recipe_collections WHERE collection_id = {$this->collectionId}");
        $this->db->exec("DELETE FROM collections WHERE id = {$this->collectionId}");
        foreach ($this->recipeIds as $id) {
            $this->db->exec("DELETE FROM recipes WHERE id = {$id}");
        }
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
    }

    public function testAddRecipesBulkAddsAllRecipes(): void
    {
        $this->collections->addRecipesBulk($this->collectionId, $this->recipeIds);

        $stmt = $this->db->prepare('SELECT recipe_id FROM recipe_collections WHERE collection_id = ?');
        $stmt->execute([$this->collectionId]);
        $linked = array_map('intval', array_column($stmt->fetchAll(), 'recipe_id'));

        sort($linked);
        $expected = $this->recipeIds;
        sort($expected);
        $this->assertSame($expected, $linked);
    }

    public function testAddRecipesBulkIsIdempotent(): void
    {
        $this->collections->addRecipesBulk($this->collectionId, $this->recipeIds);
        $this->collections->addRecipesBulk($this->collectionId, $this->recipeIds);

        $stmt = $this->db->prepare('SELECT COUNT(*) AS c FROM recipe_collections WHERE collection_id = ?');
        $stmt->execute([$this->collectionId]);
        $this->assertEquals(2, $stmt->fetch()['c']);
    }
}
