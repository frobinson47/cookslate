<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UnitConverterTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/UnitConverter.php';
    }

    // canConvert

    public function testCanConvertVolumeToVolume(): void
    {
        $this->assertTrue(\UnitConverter::canConvert('cup', 'tbsp'));
        $this->assertTrue(\UnitConverter::canConvert('ml', 'L'));
        $this->assertTrue(\UnitConverter::canConvert('tsp', 'gallon'));
    }

    public function testCanConvertWeightToWeight(): void
    {
        $this->assertTrue(\UnitConverter::canConvert('g', 'kg'));
        $this->assertTrue(\UnitConverter::canConvert('oz', 'lb'));
    }

    public function testCannotConvertVolumeToWeight(): void
    {
        $this->assertFalse(\UnitConverter::canConvert('cup', 'g'));
        $this->assertFalse(\UnitConverter::canConvert('ml', 'oz'));
    }

    public function testCannotConvertNulls(): void
    {
        $this->assertFalse(\UnitConverter::canConvert(null, 'cup'));
        $this->assertFalse(\UnitConverter::canConvert('cup', null));
    }

    public function testCanConvertWithAliases(): void
    {
        $this->assertTrue(\UnitConverter::canConvert('cups', 'tablespoons'));
        $this->assertTrue(\UnitConverter::canConvert('TBSP', 'tsp'));
    }

    // convert

    public function testConvertCupToTbsp(): void
    {
        $this->assertEquals(16.0, \UnitConverter::convert(1, 'cup', 'tbsp'));
    }

    public function testConvertTbspToTsp(): void
    {
        $this->assertEquals(3.0, \UnitConverter::convert(1, 'tbsp', 'tsp'));
    }

    public function testConvertGallonToCup(): void
    {
        $this->assertEquals(16.0, \UnitConverter::convert(1, 'gallon', 'cup'));
    }

    public function testConvertKgToG(): void
    {
        $this->assertEquals(1000.0, \UnitConverter::convert(1, 'kg', 'g'));
    }

    public function testConvertLbToOz(): void
    {
        $this->assertEqualsWithDelta(16.0, \UnitConverter::convert(1, 'lb', 'oz'), 0.01);
    }

    public function testConvertReturnsNullForIncompatibleUnits(): void
    {
        $this->assertNull(\UnitConverter::convert(1, 'cup', 'g'));
        $this->assertNull(\UnitConverter::convert(1, 'bogus', 'tsp'));
    }

    public function testConvertRespectsAliases(): void
    {
        $this->assertEquals(16.0, \UnitConverter::convert(1, 'CUPS', 'tablespoon'));
    }

    // getMeasureType

    public function testGetMeasureTypeVolume(): void
    {
        $this->assertEquals('volume', \UnitConverter::getMeasureType('cup'));
        $this->assertEquals('volume', \UnitConverter::getMeasureType('ml'));
        $this->assertEquals('volume', \UnitConverter::getMeasureType('gallons'));
    }

    public function testGetMeasureTypeWeight(): void
    {
        $this->assertEquals('weight', \UnitConverter::getMeasureType('g'));
        $this->assertEquals('weight', \UnitConverter::getMeasureType('oz'));
        $this->assertEquals('weight', \UnitConverter::getMeasureType('pounds'));
    }

    public function testGetMeasureTypeUnknown(): void
    {
        $this->assertNull(\UnitConverter::getMeasureType('pinch'));
        $this->assertNull(\UnitConverter::getMeasureType(null));
    }

    // parseAmount

    public function testParseAmountInteger(): void
    {
        $this->assertEquals(2.0, \UnitConverter::parseAmount('2'));
    }

    public function testParseAmountDecimal(): void
    {
        $this->assertEquals(0.5, \UnitConverter::parseAmount('0.5'));
    }

    public function testParseAmountSimpleFraction(): void
    {
        $this->assertEqualsWithDelta(0.75, \UnitConverter::parseAmount('3/4'), 0.001);
    }

    public function testParseAmountMixedNumber(): void
    {
        $this->assertEqualsWithDelta(1.5, \UnitConverter::parseAmount('1 1/2'), 0.001);
    }

    public function testParseAmountUnicodeFractionBareHalf(): void
    {
        $this->assertEqualsWithDelta(0.5, \UnitConverter::parseAmount('½'), 0.001);
    }

    public function testParseAmountUnicodeFractionMixed(): void
    {
        $this->assertEqualsWithDelta(1.25, \UnitConverter::parseAmount('1¼'), 0.001);
    }

    public function testParseAmountRangeAverages(): void
    {
        $this->assertEqualsWithDelta(2.5, \UnitConverter::parseAmount('2-3'), 0.001);
    }

    public function testParseAmountEmptyReturnsNull(): void
    {
        $this->assertNull(\UnitConverter::parseAmount(''));
        $this->assertNull(\UnitConverter::parseAmount('   '));
        $this->assertNull(\UnitConverter::parseAmount(null));
    }

    public function testParseAmountGarbageReturnsNull(): void
    {
        $this->assertNull(\UnitConverter::parseAmount('to taste'));
    }

    // formatAmount

    public function testFormatAmountWhole(): void
    {
        $this->assertEquals('2', \UnitConverter::formatAmount(2.0));
    }

    public function testFormatAmountCommonFraction(): void
    {
        $this->assertEquals('1/2', \UnitConverter::formatAmount(0.5));
        $this->assertEquals('1/4', \UnitConverter::formatAmount(0.25));
        $this->assertEquals('3/4', \UnitConverter::formatAmount(0.75));
    }

    public function testFormatAmountMixedFraction(): void
    {
        $this->assertEquals('1 1/2', \UnitConverter::formatAmount(1.5));
        $this->assertEquals('2 1/4', \UnitConverter::formatAmount(2.25));
    }

    public function testFormatAmountFallsBackToDecimal(): void
    {
        // 0.05 is not close to any common fraction; falls through to number_format
        $result = \UnitConverter::formatAmount(0.05);
        $this->assertMatchesRegularExpression('/^0\.\d+$/', $result);
    }

    // bestUnit

    public function testBestUnitImperialVolumeLarge(): void
    {
        // 48 tsp = 1 cup
        $result = \UnitConverter::bestUnit(48, 'cup', 'volume');
        $this->assertEquals('cup', $result['unit']);
        $this->assertEqualsWithDelta(1.0, $result['amount'], 0.01);
    }

    public function testBestUnitImperialVolumeSmall(): void
    {
        // 6 tsp = 2 tbsp
        $result = \UnitConverter::bestUnit(6, 'tsp', 'volume');
        $this->assertEquals('tbsp', $result['unit']);
        $this->assertEqualsWithDelta(2.0, $result['amount'], 0.01);
    }

    public function testBestUnitMetricVolume(): void
    {
        // 202.9 tsp ≈ 1L
        $result = \UnitConverter::bestUnit(202.9, 'L', 'volume');
        $this->assertEquals('L', $result['unit']);
        $this->assertEqualsWithDelta(1.0, $result['amount'], 0.01);
    }

    public function testBestUnitImperialWeight(): void
    {
        // 453.6 g = 1 lb
        $result = \UnitConverter::bestUnit(453.6, 'lb', 'weight');
        $this->assertEquals('lb', $result['unit']);
        $this->assertEqualsWithDelta(1.0, $result['amount'], 0.01);
    }

    public function testBestUnitMetricWeight(): void
    {
        $result = \UnitConverter::bestUnit(1500, 'kg', 'weight');
        $this->assertEquals('kg', $result['unit']);
        $this->assertEqualsWithDelta(1.5, $result['amount'], 0.01);
    }
}
