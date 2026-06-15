<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class JsonLdRecipeExporterTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/JsonLdRecipeExporter.php';
    }

    private function minimalRecipe(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Test Cookies',
            'description' => 'A test recipe.',
            'prep_time' => 15,
            'cook_time' => 30,
            'servings' => 12,
            'source_url' => 'https://example.com/cookies',
            'image_path' => 'recipes/1/full.jpg',
            'instructions' => ['Mix dry ingredients.', 'Bake at 350F.'],
            'ingredients' => [
                ['name' => 'flour', 'amount' => '2', 'unit' => 'cups'],
                ['name' => 'sugar', 'amount' => '1', 'unit' => 'cup'],
            ],
            'tags' => [['name' => 'dessert'], ['name' => 'baking']],
            'calories' => 250,
            'protein' => 4,
            'carbs' => 35,
            'fat' => 11,
            'fiber' => 1,
            'sugar' => 18,
            'created_at' => '2026-01-15 10:30:00',
            'updated_at' => '2026-02-01 12:00:00',
            'author' => 'frank',
        ], $overrides);
    }

    public function testIncludesSchemaOrgContextAndType(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $this->assertSame('https://schema.org', $doc['@context']);
        $this->assertSame('Recipe', $doc['@type']);
    }

    public function testMapsBasicFields(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $this->assertSame('Test Cookies', $doc['name']);
        $this->assertSame('A test recipe.', $doc['description']);
        $this->assertSame('https://example.com/cookies', $doc['url']);
        $this->assertSame('12', $doc['recipeYield']);
    }

    public function testIso8601Durations(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $this->assertSame('PT15M', $doc['prepTime']);
        $this->assertSame('PT30M', $doc['cookTime']);
        $this->assertSame('PT45M', $doc['totalTime']);
    }

    public function testIso8601HandlesHoursAndMinutes(): void
    {
        $this->assertSame('PT1H30M', \JsonLdRecipeExporter::minutesToIso8601(90));
        $this->assertSame('PT2H', \JsonLdRecipeExporter::minutesToIso8601(120));
        $this->assertSame('PT5M', \JsonLdRecipeExporter::minutesToIso8601(5));
        $this->assertNull(\JsonLdRecipeExporter::minutesToIso8601(0));
        $this->assertNull(\JsonLdRecipeExporter::minutesToIso8601(null));
        $this->assertNull(\JsonLdRecipeExporter::minutesToIso8601(''));
    }

    public function testIngredientStringFormatting(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $this->assertSame(['2 cups flour', '1 cup sugar'], $doc['recipeIngredient']);
    }

    public function testIngredientWithMissingFieldsSkipsBlanks(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe([
            'ingredients' => [
                ['name' => 'salt', 'amount' => null, 'unit' => null],
                ['name' => 'pepper', 'amount' => 'to taste', 'unit' => null],
            ],
        ]));
        $this->assertSame(['salt', 'to taste pepper'], $doc['recipeIngredient']);
    }

    public function testInstructionsAsHowToSteps(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $this->assertCount(2, $doc['recipeInstructions']);
        $this->assertSame('HowToStep', $doc['recipeInstructions'][0]['@type']);
        $this->assertSame('Step 1', $doc['recipeInstructions'][0]['name']);
        $this->assertSame('Mix dry ingredients.', $doc['recipeInstructions'][0]['text']);
        $this->assertSame('Bake at 350F.', $doc['recipeInstructions'][1]['text']);
    }

    public function testInstructionsAcceptArrayOfObjectsWithTextKey(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe([
            'instructions' => [
                ['text' => 'First step.'],
                ['text' => 'Second step.'],
            ],
        ]));
        $this->assertSame('First step.', $doc['recipeInstructions'][0]['text']);
        $this->assertSame('Second step.', $doc['recipeInstructions'][1]['text']);
    }

    public function testTagsMapToKeywordsAndCategory(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $this->assertSame('dessert, baking', $doc['keywords']);
        $this->assertSame('dessert', $doc['recipeCategory']);
    }

    public function testNutritionWithUnits(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $n = $doc['nutrition'];
        $this->assertSame('NutritionInformation', $n['@type']);
        $this->assertSame('250', $n['calories']);
        $this->assertSame('4 g', $n['proteinContent']);
        $this->assertSame('35 g', $n['carbohydrateContent']);
        $this->assertSame('11 g', $n['fatContent']);
        $this->assertSame('1 g', $n['fiberContent']);
        $this->assertSame('18 g', $n['sugarContent']);
    }

    public function testNutritionOmittedWhenAllBlank(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe([
            'calories' => null, 'protein' => null, 'carbs' => null,
            'fat' => null, 'fiber' => null, 'sugar' => null,
        ]));
        $this->assertArrayNotHasKey('nutrition', $doc);
    }

    public function testAuthorAsPerson(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        $this->assertSame('Person', $doc['author']['@type']);
        $this->assertSame('frank', $doc['author']['name']);
    }

    public function testRelativeImagePathGetsBaseUrlPrepended(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd(
            $this->minimalRecipe(),
            'https://cookslate.app'
        );
        $this->assertSame('https://cookslate.app/recipes/1/full.jpg', $doc['image']);
    }

    public function testAbsoluteImageUrlPassesThrough(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe([
            'image_path' => 'https://cdn.example.com/img.jpg',
        ]), 'https://cookslate.app');
        $this->assertSame('https://cdn.example.com/img.jpg', $doc['image']);
    }

    public function testDatesConvertedToIso8601(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd($this->minimalRecipe());
        // dateCreated should be an ISO 8601 string (RFC 3339), starts with the year
        $this->assertStringStartsWith('2026-01-15T', $doc['dateCreated']);
        $this->assertStringStartsWith('2026-02-01T', $doc['dateModified']);
    }

    public function testMinimalRecipeStillProducesValidDocument(): void
    {
        $doc = \JsonLdRecipeExporter::toJsonLd([
            'title' => 'Just a title',
            'ingredients' => [],
            'instructions' => [],
            'tags' => [],
        ]);
        $this->assertSame('https://schema.org', $doc['@context']);
        $this->assertSame('Recipe', $doc['@type']);
        $this->assertSame('Just a title', $doc['name']);
        $this->assertArrayNotHasKey('prepTime', $doc);
        $this->assertArrayNotHasKey('nutrition', $doc);
        $this->assertSame([], $doc['recipeIngredient']);
        $this->assertSame([], $doc['recipeInstructions']);
    }
}
