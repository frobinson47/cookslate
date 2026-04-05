<?php
// api/tests/Unit/PantryModelTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PantryModelTest extends TestCase
{
    private \PDO $db;
    private \Pantry $pantry;
    private int $testUserId;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/Pantry.php';

        $this->db = \Database::getInstance();
        $this->pantry = new \Pantry();

        // Create a test user
        $this->db->exec("DELETE FROM users WHERE username = 'pantrytest'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('pantrytest', 'pantrytest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE FROM pantry WHERE user_id = {$this->testUserId}");
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
    }

    public function testAddItemToPantry(): void
    {
        $item = $this->pantry->add($this->testUserId, 'salt');
        $this->assertEquals('salt', $item['ingredient_name']);
        $this->assertEquals($this->testUserId, (int) $item['user_id']);
        $this->assertEquals(1, (int) $item['always_stocked']);
    }

    public function testAddDuplicateReturnsSameItem(): void
    {
        $first = $this->pantry->add($this->testUserId, 'salt');
        $second = $this->pantry->add($this->testUserId, 'salt');
        $this->assertEquals((int) $first['id'], (int) $second['id']);
    }

    public function testGetAllForUser(): void
    {
        $this->pantry->add($this->testUserId, 'salt');
        $this->pantry->add($this->testUserId, 'pepper');

        $items = $this->pantry->getAllForUser($this->testUserId);
        $this->assertCount(2, $items);
        $names = array_column($items, 'ingredient_name');
        $this->assertContains('salt', $names);
        $this->assertContains('pepper', $names);
    }

    public function testRemove(): void
    {
        $item = $this->pantry->add($this->testUserId, 'salt');
        $result = $this->pantry->remove((int) $item['id'], $this->testUserId);
        $this->assertTrue($result);

        $items = $this->pantry->getAllForUser($this->testUserId);
        $this->assertCount(0, $items);
    }

    public function testRemoveRejectsOtherUser(): void
    {
        $item = $this->pantry->add($this->testUserId, 'salt');
        $result = $this->pantry->remove((int) $item['id'], 99999);
        $this->assertFalse($result);
    }

    public function testIsInPantry(): void
    {
        $this->pantry->add($this->testUserId, 'salt');
        $this->assertTrue($this->pantry->isInPantry($this->testUserId, 'salt'));
        $this->assertTrue($this->pantry->isInPantry($this->testUserId, 'Salt'));
        $this->assertFalse($this->pantry->isInPantry($this->testUserId, 'cumin'));
    }

    public function testGetPantryMatchesForIngredients(): void
    {
        $this->pantry->add($this->testUserId, 'salt');
        $this->pantry->add($this->testUserId, 'olive oil');

        $ingredients = ['salt', 'flour', 'olive oil', 'butter'];
        $matches = $this->pantry->getPantryMatches($this->testUserId, $ingredients);

        $this->assertContains('salt', $matches);
        $this->assertContains('olive oil', $matches);
        $this->assertNotContains('flour', $matches);
        $this->assertNotContains('butter', $matches);
    }
}
