<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class GroceryItemTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../models/GroceryItem.php';
    }

    /**
     * Two recipes calling for the same ingredient should consolidate to a
     * single grocery list entry. This is the regression case from issue #9
     * (meal plan grocery list duplicates).
     */
    public function testFlourFromTwoRecipesNormalizesToSameKey(): void
    {
        $a = \GroceryItem::normalizeForMatch('2 cups all-purpose flour');
        $b = \GroceryItem::normalizeForMatch('1 cup all-purpose flour');
        $this->assertSame($a, $b);
        $this->assertSame('all-purpose flour', $a);
    }

    public function testGarlicWithDifferentPrepNotation(): void
    {
        // "2 garlic cloves, minced" + "2 clove garlic, minced" → both "garlic"
        $a = \GroceryItem::normalizeForMatch('2 garlic cloves, minced');
        $b = \GroceryItem::normalizeForMatch('2 clove garlic, minced');
        $this->assertSame('garlic', $a);
        $this->assertSame('garlic', $b);
    }

    public function testOnionWithSizeQualifier(): void
    {
        $a = \GroceryItem::normalizeForMatch('1 small onion');
        $b = \GroceryItem::normalizeForMatch('1 onion');
        $this->assertSame('onion', $a);
        $this->assertSame('onion', $b);
    }

    public function testOliveOilWithQualityPrefix(): void
    {
        $a = \GroceryItem::normalizeForMatch('extra-virgin olive oil');
        $b = \GroceryItem::normalizeForMatch('olive oil');
        $this->assertSame('olive oil', $a);
        $this->assertSame('olive oil', $b);
    }

    public function testSaltVariants(): void
    {
        $a = \GroceryItem::normalizeForMatch('salt');
        $b = \GroceryItem::normalizeForMatch('kosher salt');
        $this->assertSame('salt', $a);
        $this->assertSame('salt', $b);
    }

    public function testStripsParentheticals(): void
    {
        // "diced" is also stripped as a prep qualifier, so canned diced tomatoes
        // consolidates with fresh tomatoes — intentional for grocery purposes.
        $this->assertSame(
            'tomatoes',
            \GroceryItem::normalizeForMatch('1 can (14.5oz) diced tomatoes')
        );
    }

    public function testStripsTrailingPrepInstruction(): void
    {
        $this->assertSame(
            'cilantro',
            \GroceryItem::normalizeForMatch('1 bunch cilantro, chopped')
        );
    }

    public function testStripsMeasurementFragments(): void
    {
        // "into 1/2 inch pieces" type tail
        $result = \GroceryItem::normalizeForMatch('1 lb beef cut into 1/2 inch pieces');
        $this->assertSame('beef cut', $result);
    }

    public function testUnicodeFraction(): void
    {
        $result = \GroceryItem::normalizeForMatch('½ cup sugar');
        $this->assertSame('sugar', $result);
    }

    public function testNonConsolidatableItemsStayDistinct(): void
    {
        // Different core ingredients shouldn't collapse.
        $a = \GroceryItem::normalizeForMatch('1 cup flour');
        $b = \GroceryItem::normalizeForMatch('1 cup sugar');
        $this->assertNotSame($a, $b);
    }
}
