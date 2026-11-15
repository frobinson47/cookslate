<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../services/CooklangExporter.php';
require_once __DIR__ . '/../../services/CooklangImporter.php';

class CooklangTest extends TestCase
{
    public function testExportBasicRecipe(): void
    {
        $exporter = new CooklangExporter();
        $recipe = [
            'title' => 'Simple Pancakes',
            'description' => 'Fluffy breakfast pancakes',
            'servings' => 4,
            'prep_time' => 10,
            'cook_time' => 15,
            'tags' => [['name' => 'breakfast'], ['name' => 'easy']],
            'ingredients' => [
                ['name' => 'flour', 'amount' => '2', 'unit' => 'cups'],
                ['name' => 'eggs', 'amount' => '2', 'unit' => ''],
                ['name' => 'milk', 'amount' => '1', 'unit' => 'cup'],
            ],
            'instructions' => [
                'Mix the flour and eggs in a bowl.',
                'Add the milk and stir until smooth.',
                'Cook on a hot griddle until golden.',
            ],
        ];

        $result = $exporter->export($recipe);

        $this->assertStringContainsString('title: Simple Pancakes', $result);
        $this->assertStringContainsString('servings: 4', $result);
        $this->assertStringContainsString('prep_time: 10 minutes', $result);
        $this->assertStringContainsString('@flour{2%cups}', $result);
        $this->assertStringContainsString('@eggs{2}', $result);
        $this->assertStringContainsString('@milk{1%cup}', $result);
        $this->assertStringContainsString('> Fluffy breakfast pancakes', $result);
    }

    public function testImportBasicCooklang(): void
    {
        $importer = new CooklangImporter();
        $cook = <<<COOK
---
title: Test Recipe
servings: 2
tags:
  - dinner
  - quick
---

> A simple test recipe.

Mix @flour{2%cups} and @sugar{1%tbsp} together.

Add @eggs{2} and stir.
COOK;

        $result = $importer->parse($cook);

        $this->assertEquals('Test Recipe', $result['title']);
        $this->assertEquals(2, $result['servings']);
        $this->assertCount(2, $result['tags']);
        $this->assertEquals('A simple test recipe.', $result['description']);
        $this->assertCount(3, $result['ingredients']);
        $this->assertEquals('flour', $result['ingredients'][0]['name']);
        $this->assertEquals('2', $result['ingredients'][0]['amount']);
        $this->assertEquals('cups', $result['ingredients'][0]['unit']);
        $this->assertCount(2, $result['instructions']);
    }

    public function testRoundTrip(): void
    {
        $exporter = new CooklangExporter();
        $importer = new CooklangImporter();

        $original = [
            'title' => 'Round Trip Test',
            'description' => '',
            'servings' => 4,
            'prep_time' => 5,
            'cook_time' => 10,
            'source_url' => '',
            'tags' => [['name' => 'test']],
            'ingredients' => [
                ['name' => 'butter', 'amount' => '2', 'unit' => 'tbsp'],
                ['name' => 'salt', 'amount' => '', 'unit' => ''],
            ],
            'instructions' => [
                'Melt the butter in a pan.',
                'Add salt to taste.',
            ],
        ];

        $cooklang = $exporter->export($original);
        $reimported = $importer->parse($cooklang);

        $this->assertEquals($original['title'], $reimported['title']);
        $this->assertEquals($original['servings'], $reimported['servings']);
        $this->assertCount(2, $reimported['ingredients']);
        $this->assertCount(2, $reimported['instructions']);
    }

    public function testImportSpecMetadataLinesAndNotes(): void
    {
        $cook = ">> title: Shepherd's Pie
>> servings: 6
>> prep time: 20 minutes
>> total time: 1 hour 30 minutes
"
            . ">> source: https://example.com/pie
>> tags: carnivore, casserole, beef

"
            . "Melt @butter{2%tbsp} in a #skillet{}.

Bake for ~{25%min}.

-- Notes --
Use bacon fat instead.
";
        $r = (new CooklangImporter())->parse($cook);

        $this->assertSame("Shepherd's Pie", $r['title']);
        $this->assertSame(6, $r['servings']);
        $this->assertSame(20, $r['prep_time']);
        $this->assertSame('https://example.com/pie', $r['source_url']);
        $this->assertSame(['carnivore', 'casserole', 'beef'], $r['tags']);
        $this->assertCount(2, $r['instructions']);
        $this->assertStringNotContainsString('>>', implode(' ', $r['instructions']));
        $this->assertStringContainsString('Use bacon fat instead.', $r['description']);
    }
}
