<?php
// api/tests/Unit/RecipeSageImporterTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RecipeSageImporterTest extends TestCase
{
    private \RecipeSageImporter $importer;
    private array $tmpFiles = [];

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/IngredientParser.php';
        require_once __DIR__ . '/../../services/RecipeSageImporter.php';
        $this->importer = new \RecipeSageImporter();
    }

    protected function tearDown(): void
    {
        foreach ($this->tmpFiles as $f) {
            @unlink($f);
        }
    }

    private function makeZip(array $entries): string
    {
        $path = tempnam(sys_get_temp_dir(), 'rs_test_') . '.zip';
        $this->tmpFiles[] = $path;
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE);
        foreach ($entries as $name => $content) {
            $zip->addFromString($name, $content);
        }
        $zip->close();
        return $path;
    }

    public function testImportsRecipeWithStringIngredientsAndInstructions(): void
    {
        $dataJson = json_encode([
            'recipes' => [[
                'title' => 'RecipeSage Chili',
                'description' => 'Hearty chili',
                'activeTime' => '20 minutes',
                'totalTime' => '1 hour 30 minutes',
                'yield' => '6 servings',
                'ingredients' => "1 lb ground beef\n2 cans beans\n1 onion",
                'instructions' => "Brown the beef.\nAdd beans and onion.\nSimmer for an hour.",
                'source' => 'https://example.com/chili',
                'labels' => ['Dinner', 'Spicy'],
            ]],
        ]);
        $zipPath = $this->makeZip(['data.json' => $dataJson]);

        $result = $this->importer->import($zipPath);

        $this->assertCount(1, $result['results']);
        $this->assertSame('success', $result['results'][0]['status']);
        $recipe = $result['results'][0]['recipe'];
        $this->assertSame('RecipeSage Chili', $recipe['title']);
        $this->assertSame(20, $recipe['prep_time']);
        $this->assertSame(90, $recipe['cook_time']);
        $this->assertSame(6, $recipe['servings']);
        $this->assertCount(3, $recipe['ingredients']);
        $this->assertCount(3, $recipe['instructions']);
        $this->assertSame(['Dinner', 'Spicy'], $recipe['tags']);
        $this->assertSame('https://example.com/chili', $recipe['source_url']);
    }

    public function testImportsRecipeWithArrayIngredientsAndInstructions(): void
    {
        $dataJson = json_encode([
            'recipes' => [[
                'title' => 'Array Shape Recipe',
                'ingredients' => ['flour', 'sugar'],
                'instructions' => ['Mix', 'Bake'],
            ]],
        ]);
        $zipPath = $this->makeZip(['data.json' => $dataJson]);

        $result = $this->importer->import($zipPath);

        $recipe = $result['results'][0]['recipe'];
        $this->assertCount(2, $recipe['ingredients']);
        $this->assertSame(['Mix', 'Bake'], $recipe['instructions']);
    }

    public function testNoDataJsonReturnsError(): void
    {
        $zipPath = $this->makeZip(['other.json' => '{}']);

        $result = $this->importer->import($zipPath);

        $this->assertSame('error', $result['results'][0]['status']);
    }

    public function testEmptyRecipesArrayReturnsError(): void
    {
        $zipPath = $this->makeZip(['data.json' => json_encode(['recipes' => []])]);

        $result = $this->importer->import($zipPath);

        $this->assertSame('error', $result['results'][0]['status']);
    }
}
