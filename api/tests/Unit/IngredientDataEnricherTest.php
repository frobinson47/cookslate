<?php
// api/tests/Unit/IngredientDataEnricherTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../models/GroceryItem.php';
require_once __DIR__ . '/../../services/OpenFoodFactsClient.php';
require_once __DIR__ . '/../../services/IngredientDataEnricher.php';

class FakeOpenFoodFactsClient extends \OpenFoodFactsClient
{
    /** @var array<int, array> */
    public array $results = [];

    public function search(string $query, int $limit = 10): array
    {
        return $this->results;
    }
}

class IngredientDataEnricherTest extends TestCase
{
    private \PDO $db;
    private FakeOpenFoodFactsClient $client;
    private \IngredientDataEnricher $enricher;

    protected function setUp(): void
    {
        $this->db = \Database::getInstance();
        $this->db->exec("DELETE FROM ingredient_data WHERE name LIKE 'enrichtest%'");

        $this->client = new FakeOpenFoodFactsClient();
        $this->enricher = new \IngredientDataEnricher($this->client);
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE FROM ingredient_data WHERE name LIKE 'enrichtest%'");
    }

    private function fetchIngredientData(string $name): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM ingredient_data WHERE LOWER(name) = ?');
        $stmt->execute([strtolower($name)]);
        return $stmt->fetch() ?: null;
    }

    public function testConfidentExactMatchIsEnriched(): void
    {
        $this->client->results = [[
            'name' => 'enrichtest cereal',
            'categories' => 'Breakfast cereals',
            'nutrition' => ['calories_per_100g' => 380.0, 'protein_per_100g' => 8.0, 'carbs_per_100g' => 70.0, 'fat_per_100g' => 5.0, 'fiber_per_100g' => 6.0],
        ]];

        $enriched = $this->enricher->enrichFromScan(['enrichtest cereal']);

        $this->assertSame(['enrichtest cereal'], $enriched);
        $row = $this->fetchIngredientData('enrichtest cereal');
        $this->assertNotNull($row);
        $this->assertEquals(380.0, (float) $row['calories_per_100g']);
    }

    public function testNoMatchIsSkipped(): void
    {
        $this->client->results = [[
            'name' => 'completely unrelated product',
            'nutrition' => ['calories_per_100g' => 100.0],
        ]];

        $enriched = $this->enricher->enrichFromScan(['enrichtest widget']);

        $this->assertSame([], $enriched);
        $this->assertNull($this->fetchIngredientData('enrichtest widget'));
    }

    public function testEmptySearchResultsSkipped(): void
    {
        $this->client->results = [];

        $enriched = $this->enricher->enrichFromScan(['enrichtest nothing']);

        $this->assertSame([], $enriched);
    }

    public function testAlreadyEnrichedIngredientIsNotOverwritten(): void
    {
        $this->db->exec("INSERT INTO ingredient_data (name, calories_per_100g) VALUES ('enrichtest existing', 999)");

        $this->client->results = [[
            'name' => 'enrichtest existing',
            'nutrition' => ['calories_per_100g' => 1.0],
        ]];

        $enriched = $this->enricher->enrichFromScan(['enrichtest existing']);

        $this->assertSame([], $enriched);
        $row = $this->fetchIngredientData('enrichtest existing');
        $this->assertEquals(999.0, (float) $row['calories_per_100g']);
    }

    public function testPartialSubstringMatchWithinLengthGapIsAccepted(): void
    {
        $this->client->results = [[
            'name' => 'Enrichtest Widget Co',
            'nutrition' => ['calories_per_100g' => 200.0],
        ]];

        $enriched = $this->enricher->enrichFromScan(['enrichtest widget']);

        $this->assertSame(['enrichtest widget'], $enriched);
    }
}
