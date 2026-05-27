<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class RecipeScraperTest extends TestCase
{
    private \RecipeScraper $scraper;
    private ReflectionClass $ref;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/RecipeScraper.php';
        $this->scraper = new \RecipeScraper();
        $this->ref = new ReflectionClass($this->scraper);
    }

    private function invoke(string $method, ...$args)
    {
        $m = $this->ref->getMethod($method);
        $m->setAccessible(true);
        return $m->invokeArgs($this->scraper, $args);
    }

    // parseDuration

    public function testParseDurationIso8601MinutesOnly(): void
    {
        $this->assertEquals(30, $this->invoke('parseDuration', 'PT30M'));
    }

    public function testParseDurationIso8601HoursAndMinutes(): void
    {
        $this->assertEquals(75, $this->invoke('parseDuration', 'PT1H15M'));
    }

    public function testParseDurationIso8601HoursOnly(): void
    {
        $this->assertEquals(120, $this->invoke('parseDuration', 'PT2H'));
    }

    public function testParseDurationPlainNumber(): void
    {
        $this->assertEquals(45, $this->invoke('parseDuration', '45'));
    }

    public function testParseDurationNullAndEmpty(): void
    {
        $this->assertNull($this->invoke('parseDuration', null));
        $this->assertNull($this->invoke('parseDuration', ''));
    }

    public function testParseDurationGarbage(): void
    {
        $this->assertNull($this->invoke('parseDuration', 'about an hour'));
    }

    // parseServings

    public function testParseServingsInteger(): void
    {
        $this->assertEquals(4, $this->invoke('parseServings', 4));
    }

    public function testParseServingsString(): void
    {
        $this->assertEquals(6, $this->invoke('parseServings', '6 servings'));
    }

    public function testParseServingsArray(): void
    {
        $this->assertEquals(8, $this->invoke('parseServings', ['8 servings']));
    }

    public function testParseServingsNull(): void
    {
        $this->assertNull($this->invoke('parseServings', null));
    }

    public function testParseServingsNoNumber(): void
    {
        $this->assertNull($this->invoke('parseServings', 'a few'));
    }

    // cleanText

    public function testCleanTextStripsTags(): void
    {
        $this->assertEquals('Hello world', $this->invoke('cleanText', '<p>Hello <b>world</b></p>'));
    }

    public function testCleanTextDecodesEntities(): void
    {
        $this->assertEquals('Salt & pepper', $this->invoke('cleanText', 'Salt &amp; pepper'));
    }

    public function testCleanTextCollapsesWhitespace(): void
    {
        $this->assertEquals('one two three', $this->invoke('cleanText', "one\n\t  two   three"));
    }

    // isValidUrl

    public function testIsValidUrlRejectsNoScheme(): void
    {
        $this->assertFalse($this->invoke('isValidUrl', 'example.com/recipe'));
    }

    public function testIsValidUrlRejectsFileScheme(): void
    {
        $this->assertFalse($this->invoke('isValidUrl', 'file:///etc/passwd'));
    }

    public function testIsValidUrlRejectsLocalhost(): void
    {
        // 127.0.0.1 is reserved/private
        $this->assertFalse($this->invoke('isValidUrl', 'http://127.0.0.1/admin'));
    }

    public function testIsValidUrlRejectsPrivateIp(): void
    {
        $this->assertFalse($this->invoke('isValidUrl', 'http://192.168.1.1/'));
    }

    // parseJsonLd

    public function testParseJsonLdFlatRecipe(): void
    {
        $html = '<html><head><script type="application/ld+json">'
            . json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Recipe',
                'name' => 'Chocolate Chip Cookies',
                'description' => 'Classic cookies',
                'prepTime' => 'PT15M',
                'cookTime' => 'PT12M',
                'recipeYield' => '24 cookies',
                'image' => 'https://example.com/cookies.jpg',
                'recipeIngredient' => ['2 cups flour', '1 cup sugar', '1 tsp salt'],
                'recipeInstructions' => [
                    ['@type' => 'HowToStep', 'text' => 'Mix dry ingredients'],
                    ['@type' => 'HowToStep', 'text' => 'Bake at 350F for 12 minutes'],
                ],
            ])
            . '</script></head><body></body></html>';

        $result = $this->invoke('parseJsonLd', $html);

        $this->assertNotNull($result);
        $this->assertEquals('Chocolate Chip Cookies', $result['title']);
        $this->assertEquals('Classic cookies', $result['description']);
        $this->assertEquals(15, $result['prep_time']);
        $this->assertEquals(12, $result['cook_time']);
        $this->assertEquals(24, $result['servings']);
        $this->assertEquals('https://example.com/cookies.jpg', $result['image_url']);
        $this->assertCount(3, $result['ingredients']);
        $this->assertEquals('flour', $result['ingredients'][0]['name']);
        $this->assertCount(2, $result['instructions']);
        $this->assertStringContainsString('Mix dry', $result['instructions'][0]);
    }

    public function testParseJsonLdGraphContainingRecipe(): void
    {
        $html = '<script type="application/ld+json">' . json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                ['@type' => 'Organization', 'name' => 'Some Blog'],
                [
                    '@type' => 'Recipe',
                    'name' => 'Pasta Carbonara',
                    'recipeIngredient' => ['8 oz spaghetti'],
                    'recipeInstructions' => "Boil pasta.\nMix with sauce.",
                ],
            ],
        ]) . '</script>';

        $result = $this->invoke('parseJsonLd', $html);

        $this->assertNotNull($result);
        $this->assertEquals('Pasta Carbonara', $result['title']);
        $this->assertCount(2, $result['instructions']);
    }

    public function testParseJsonLdImageAsArray(): void
    {
        $html = '<script type="application/ld+json">' . json_encode([
            '@type' => 'Recipe',
            'name' => 'Soup',
            'image' => ['https://a.com/1.jpg', 'https://a.com/2.jpg'],
            'recipeIngredient' => [],
        ]) . '</script>';

        $result = $this->invoke('parseJsonLd', $html);
        $this->assertEquals('https://a.com/1.jpg', $result['image_url']);
    }

    public function testParseJsonLdImageAsImageObject(): void
    {
        $html = '<script type="application/ld+json">' . json_encode([
            '@type' => 'Recipe',
            'name' => 'Soup',
            'image' => ['@type' => 'ImageObject', 'url' => 'https://a.com/x.jpg'],
            'recipeIngredient' => [],
        ]) . '</script>';

        $result = $this->invoke('parseJsonLd', $html);
        $this->assertEquals('https://a.com/x.jpg', $result['image_url']);
    }

    public function testParseJsonLdReturnsNullWhenNoRecipe(): void
    {
        $html = '<script type="application/ld+json">' . json_encode([
            '@type' => 'WebPage',
            'name' => 'Not a recipe',
        ]) . '</script>';

        $this->assertNull($this->invoke('parseJsonLd', $html));
    }

    public function testParseJsonLdReturnsNullWhenNoScript(): void
    {
        $this->assertNull($this->invoke('parseJsonLd', '<html><body>nothing</body></html>'));
    }

    public function testParseJsonLdHandlesMalformedJson(): void
    {
        $html = '<script type="application/ld+json">not valid json</script>';
        $this->assertNull($this->invoke('parseJsonLd', $html));
    }

    public function testParseJsonLdTotalTimeFallback(): void
    {
        $html = '<script type="application/ld+json">' . json_encode([
            '@type' => 'Recipe',
            'name' => 'Stew',
            'totalTime' => 'PT2H',
            'recipeIngredient' => [],
        ]) . '</script>';

        $result = $this->invoke('parseJsonLd', $html);
        $this->assertEquals(120, $result['cook_time']);
        $this->assertNull($result['prep_time']);
    }

    public function testParseJsonLdHowToSectionWithSubsteps(): void
    {
        $html = '<script type="application/ld+json">' . json_encode([
            '@type' => 'Recipe',
            'name' => 'Layered Cake',
            'recipeIngredient' => [],
            'recipeInstructions' => [
                [
                    '@type' => 'HowToSection',
                    'name' => 'Make the cake',
                    'itemListElement' => [
                        ['@type' => 'HowToStep', 'text' => 'Mix batter'],
                        ['@type' => 'HowToStep', 'text' => 'Bake 30 min'],
                    ],
                ],
            ],
        ]) . '</script>';

        $result = $this->invoke('parseJsonLd', $html);
        $this->assertCount(3, $result['instructions']);
        $this->assertStringContainsString('Make the cake', $result['instructions'][0]);
        $this->assertEquals('Mix batter', $result['instructions'][1]);
    }

    // scrape() — integration of error handling without network

    public function testScrapeReturnsInvalidUrlError(): void
    {
        $result = $this->scraper->scrape('not a url');
        $this->assertEquals('invalid_url', $result['error_code']);
        $this->assertEmpty($result['title']);
    }

    public function testScrapeBlocksPrivateIp(): void
    {
        $result = $this->scraper->scrape('http://192.168.1.1/');
        $this->assertEquals('invalid_url', $result['error_code']);
    }
}
