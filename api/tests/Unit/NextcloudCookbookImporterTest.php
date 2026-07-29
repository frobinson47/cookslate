<?php
// api/tests/Unit/NextcloudCookbookImporterTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class NextcloudCookbookImporterTest extends TestCase
{
    private \NextcloudCookbookImporter $importer;
    private array $tmpFiles = [];

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/IngredientParser.php';
        require_once __DIR__ . '/../../services/NextcloudCookbookImporter.php';
        $this->importer = new \NextcloudCookbookImporter();
    }

    protected function tearDown(): void
    {
        foreach ($this->tmpFiles as $f) {
            @unlink($f);
        }
    }

    private function makeZip(array $entries): string
    {
        $path = tempnam(sys_get_temp_dir(), 'nc_test_') . '.zip';
        $this->tmpFiles[] = $path;
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE);
        foreach ($entries as $name => $content) {
            $zip->addFromString($name, $content);
        }
        $zip->close();
        return $path;
    }

    public function testImportsRecipeFromFolderStructure(): void
    {
        $recipeJson = json_encode([
            '@type' => 'Recipe',
            'name' => 'Nextcloud Pancakes',
            'description' => 'Fluffy pancakes',
            'prepTime' => 'PT10M',
            'cookTime' => 'PT15M',
            'recipeYield' => '4',
            'recipeIngredient' => ['2 cups flour', '1 cup milk'],
            'recipeInstructions' => ['Mix dry ingredients', 'Cook on griddle'],
            'recipeCategory' => 'Breakfast, Quick',
            'url' => 'https://example.com/pancakes',
        ]);
        $zipPath = $this->makeZip(['Pancakes/recipe.json' => $recipeJson]);

        $result = $this->importer->import($zipPath);

        $this->assertCount(1, $result['results']);
        $this->assertSame('success', $result['results'][0]['status']);
        $recipe = $result['results'][0]['recipe'];
        $this->assertSame('Nextcloud Pancakes', $recipe['title']);
        $this->assertSame(10, $recipe['prep_time']);
        $this->assertSame(15, $recipe['cook_time']);
        $this->assertSame(4, $recipe['servings']);
        $this->assertCount(2, $recipe['ingredients']);
        $this->assertSame(['Mix dry ingredients', 'Cook on griddle'], $recipe['instructions']);
        $this->assertContains('Breakfast', $recipe['tags']);
        $this->assertContains('Quick', $recipe['tags']);
    }

    public function testIgnoresNonRecipeJsonFiles(): void
    {
        $zipPath = $this->makeZip([
            'config.json' => json_encode(['setting' => 'value']),
        ]);

        $result = $this->importer->import($zipPath);

        $this->assertSame('error', $result['results'][0]['status']);
    }

    public function testHandlesMultipleRecipeFolders(): void
    {
        $recipeA = json_encode(['@type' => 'Recipe', 'name' => 'A', 'recipeIngredient' => ['salt']]);
        $recipeB = json_encode(['@type' => 'Recipe', 'name' => 'B', 'recipeIngredient' => ['pepper']]);
        $zipPath = $this->makeZip([
            'A/recipe.json' => $recipeA,
            'B/recipe.json' => $recipeB,
        ]);

        $result = $this->importer->import($zipPath);

        $this->assertCount(2, $result['results']);
        $titles = array_column(array_column($result['results'], 'recipe'), 'title');
        $this->assertContains('A', $titles);
        $this->assertContains('B', $titles);
    }
}
