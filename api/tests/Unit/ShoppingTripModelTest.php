<?php
// api/tests/Unit/ShoppingTripModelTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ShoppingTripModelTest extends TestCase
{
    private \PDO $db;
    private \ShoppingTrip $trips;
    private int $testUserId;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/Database.php';
        require_once __DIR__ . '/../../models/ShoppingTrip.php';

        $this->db = \Database::getInstance();
        $this->trips = new \ShoppingTrip();

        $this->db->exec("DELETE FROM users WHERE username = 'shoppingtriptest'");
        $hash = password_hash('Test1234!', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES ('shoppingtriptest', 'shoppingtriptest@test.com', ?, 'member')");
        $stmt->execute([$hash]);
        $this->testUserId = (int) $this->db->lastInsertId();
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE FROM shopping_trips WHERE user_id = {$this->testUserId}");
        $this->db->exec("DELETE FROM users WHERE id = {$this->testUserId}");
    }

    private function sampleItems(): array {
        return [
            ['name' => 'Bananas', 'quantity' => 2.5, 'unit' => 'lb', 'price' => 1.98],
            ['name' => 'Milk', 'quantity' => 1, 'unit' => 'gallon', 'price' => 3.49],
        ];
    }

    public function testCreatePersistsTripAndItems(): void
    {
        $trip = $this->trips->create($this->testUserId, 'Kroger', '2026-07-20', 24.53, null, $this->sampleItems());

        $this->assertSame('Kroger', $trip['store_name']);
        $this->assertSame('2026-07-20', $trip['trip_date']);
        $this->assertEquals(24.53, (float) $trip['total_amount']);
        $this->assertCount(2, $trip['items']);
        $this->assertSame('Bananas', $trip['items'][0]['item_name']);
        $this->assertEquals(2.5, (float) $trip['items'][0]['quantity']);
    }

    public function testCreateSkipsItemsMissingName(): void
    {
        $items = array_merge($this->sampleItems(), [['quantity' => 1, 'price' => 2.0]]);
        $trip = $this->trips->create($this->testUserId, 'Kroger', null, null, null, $items);

        $this->assertCount(2, $trip['items']);
    }

    public function testGetAllForUserReturnsOwnTripsOnly(): void
    {
        $this->trips->create($this->testUserId, 'Kroger', '2026-07-20', 24.53, null, $this->sampleItems());
        $this->trips->create($this->testUserId, 'Publix', '2026-07-21', 10.00, null, $this->sampleItems());

        $all = $this->trips->getAllForUser($this->testUserId);
        $this->assertCount(2, $all);
        $this->assertArrayNotHasKey('items', $all[0]); // list view omits line items
    }

    public function testGetByIdRejectsOtherUser(): void
    {
        $trip = $this->trips->create($this->testUserId, 'Kroger', '2026-07-20', 24.53, null, $this->sampleItems());
        $result = $this->trips->getById((int) $trip['id'], 99999);
        $this->assertNull($result);
    }

    public function testDeleteRemovesTripAndItemsViaCascade(): void
    {
        $trip = $this->trips->create($this->testUserId, 'Kroger', '2026-07-20', 24.53, null, $this->sampleItems());
        $tripId = (int) $trip['id'];

        $result = $this->trips->delete($tripId, $this->testUserId);
        $this->assertTrue($result);

        $itemCount = $this->db->query("SELECT COUNT(*) AS c FROM shopping_trip_items WHERE trip_id = {$tripId}")->fetch()['c'];
        $this->assertEquals(0, $itemCount);
    }

    public function testDeleteRejectsOtherUser(): void
    {
        $trip = $this->trips->create($this->testUserId, 'Kroger', '2026-07-20', 24.53, null, $this->sampleItems());
        $result = $this->trips->delete((int) $trip['id'], 99999);
        $this->assertFalse($result);
    }
}
