<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OpenAiVisionParserTest extends TestCase
{
    private \OpenAiVisionParser $parser;
    private array $emptyResult;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/VisionRecipeParser.php';
        require_once __DIR__ . '/../../services/LoggerService.php';
        require_once __DIR__ . '/../../services/OpenAiVisionParser.php';

        $this->parser = new \OpenAiVisionParser();
        $this->emptyResult = [
            'title' => '', 'description' => '', 'prep_time' => null, 'cook_time' => null,
            'servings' => null, 'ingredients' => [], 'instructions' => [],
            'image_url' => '', 'source_url' => '',
        ];
    }

    public function testParseModelContentMalformedJsonReturnsMalformedResponse(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, 'not json at all');

        $this->assertSame('malformed_response', $result['error_code']);
        $this->assertNotEmpty($result['error']);
    }

    public function testParseModelContentNoRecipeFoundReturnsParseFailed(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, json_encode(['error' => 'no_recipe_found']));

        $this->assertSame('parse_failed', $result['error_code']);
    }

    public function testParseModelContentMissingTitleReturnsParseFailed(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, json_encode(['ingredients' => []]));

        $this->assertSame('parse_failed', $result['error_code']);
    }

    public function testParseModelContentValidRecipeMapsToRecipeShape(): void
    {
        $content = json_encode([
            'title' => 'Test Pancakes',
            'description' => 'Fluffy pancakes',
            'prep_time' => 10,
            'cook_time' => 15,
            'servings' => 4,
            'ingredients' => [
                ['name' => 'flour', 'amount' => '2', 'unit' => 'cups'],
                'a pinch of salt',
            ],
            'instructions' => ['Mix dry ingredients', 'Cook on griddle'],
        ]);

        $result = $this->parser->parseModelContent($this->emptyResult, $content);

        $this->assertArrayNotHasKey('error_code', $result);
        $this->assertSame('Test Pancakes', $result['title']);
        $this->assertSame(10, $result['prep_time']);
        $this->assertSame(15, $result['cook_time']);
        $this->assertSame(4, $result['servings']);
        $this->assertCount(2, $result['ingredients']);
        $this->assertSame('flour', $result['ingredients'][0]['name']);
        $this->assertSame('2', $result['ingredients'][0]['amount']);
        $this->assertSame('cups', $result['ingredients'][0]['unit']);
        $this->assertSame('a pinch of salt', $result['ingredients'][1]['name']);
        $this->assertSame(['Mix dry ingredients', 'Cook on griddle'], $result['instructions']);
        $this->assertSame('', $result['source_url']);
        $this->assertSame('', $result['image_url']);
    }

    public function testBuildPayloadIncludesImageDataUri(): void
    {
        $payload = $this->parser->buildPayload('BASE64DATA', 'image/jpeg');

        $this->assertSame('gpt-4o-mini', $payload['model']);
        $imageContent = $payload['messages'][1]['content'][1];
        $this->assertSame('image_url', $imageContent['type']);
        $this->assertSame('data:image/jpeg;base64,BASE64DATA', $imageContent['image_url']['url']);
    }
}
