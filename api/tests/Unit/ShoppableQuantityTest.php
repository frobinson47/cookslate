<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ShoppableQuantityTest extends TestCase
{
    private \ShoppableQuantity $service;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/ShoppableQuantity.php';
        require_once __DIR__ . '/../../services/UnitConverter.php';
        $this->service = new \ShoppableQuantity();
    }

    public function testConvertSameUnit(): void
    {
        // 2 cups flour, package is 5 lb bag
        // cup (volume) vs lb (weight) — incompatible, should return null
        $result = $this->service->convert('2', 'cup', 'flour');
        $this->assertNull($result);
    }

    public function testConvertCompatibleUnits(): void
    {
        // 2 cups milk, package is 1 gallon
        // 2 cups = 2/16 gallon = 0.125 gallon → 0.125 of 1 gallon
        $result = $this->service->convert('2', 'cup', 'milk');
        $this->assertNotNull($result);
        $this->assertEquals(1, $result['packages_needed']);
        $this->assertEquals('Jug', $result['package_description']);
        $this->assertEquals('1 gallon', $result['package_label']);
    }

    public function testConvertMultiplePackages(): void
    {
        // 3 quarts milk → 0.75 gallon, package = 1 gallon → 1 package
        $result = $this->service->convert('3', 'quart', 'milk');
        $this->assertNotNull($result);
        $this->assertEquals(1, $result['packages_needed']);
    }

    public function testConvertTwoPackages(): void
    {
        // 6 quarts milk → 1.5 gallons → need 2 packages of 1 gallon
        $result = $this->service->convert('6', 'quart', 'milk');
        $this->assertNotNull($result);
        $this->assertEquals(2, $result['packages_needed']);
    }

    public function testConvertWeightUnits(): void
    {
        // 8 oz butter → package is 16 oz (4-stick pack) → 1 package
        $result = $this->service->convert('8', 'oz', 'butter');
        $this->assertNotNull($result);
        $this->assertEquals(1, $result['packages_needed']);
        $this->assertEquals('4-stick pack', $result['package_description']);
    }

    public function testUnknownIngredientReturnsNull(): void
    {
        $result = $this->service->convert('1', 'cup', 'dragon fruit paste');
        $this->assertNull($result);
    }

    public function testNoAmountReturnsNull(): void
    {
        $result = $this->service->convert(null, null, 'salt');
        $this->assertNull($result);
    }

    public function testCountBasedIngredient(): void
    {
        // 6 eggs, package = 12 count (Dozen) → 1 package
        $result = $this->service->convert('6', null, 'egg');
        $this->assertNotNull($result);
        $this->assertEquals(1, $result['packages_needed']);
        $this->assertEquals('Dozen', $result['package_description']);
    }

    public function testCountMoreThanOnePackage(): void
    {
        // 18 eggs → 1.5 dozen → 2 packages
        $result = $this->service->convert('18', null, 'egg');
        $this->assertNotNull($result);
        $this->assertEquals(2, $result['packages_needed']);
    }
}
