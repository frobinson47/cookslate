<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OpenAiPantryScanParserTest extends TestCase
{
    private \OpenAiPantryScanParser $parser;
    private array $emptyResult;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/LoggerService.php';
        require_once __DIR__ . '/../../services/OpenAiPantryScanParser.php';

        $this->parser = new \OpenAiPantryScanParser();
        $this->emptyResult = ['items' => []];
    }

    public function testParseModelContentMalformedJsonReturnsMalformedResponse(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, 'not json at all');

        $this->assertSame('malformed_response', $result['error_code']);
        $this->assertNotEmpty($result['error']);
    }

    public function testParseModelContentNoItemsFoundReturnsParseFailed(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, json_encode(['error' => 'no_items_found']));

        $this->assertSame('parse_failed', $result['error_code']);
    }

    public function testParseModelContentEmptyItemsReturnsParseFailed(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, json_encode(['items' => []]));

        $this->assertSame('parse_failed', $result['error_code']);
    }

    public function testParseModelContentValidScanMapsToPantryShape(): void
    {
        $content = json_encode([
            'items' => [
                ['name' => 'Eggs', 'quantity' => 6, 'unit' => 'count'],
                ['name' => 'Milk', 'quantity' => 1, 'unit' => 'gallon'],
                ['name' => 'unlabeled thing missing name'],
            ],
        ]);

        $result = $this->parser->parseModelContent($this->emptyResult, $content);

        $this->assertArrayNotHasKey('error_code', $result);
        $this->assertCount(3, $result['items']);
        $this->assertSame('Eggs', $result['items'][0]['name']);
        $this->assertSame(6.0, $result['items'][0]['quantity']);
        $this->assertSame('count', $result['items'][0]['unit']);
    }

    public function testParseModelContentDropsItemsMissingName(): void
    {
        $content = json_encode([
            'items' => [
                ['quantity' => 1],
                ['name' => 'Butter', 'quantity' => 1, 'unit' => 'stick'],
            ],
        ]);

        $result = $this->parser->parseModelContent($this->emptyResult, $content);

        $this->assertCount(1, $result['items']);
        $this->assertSame('Butter', $result['items'][0]['name']);
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
