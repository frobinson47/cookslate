<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OpenAiReceiptParserTest extends TestCase
{
    private \OpenAiReceiptParser $parser;
    private array $emptyResult;

    protected function setUp(): void
    {
        require_once __DIR__ . '/../../services/ReceiptVisionParser.php';
        require_once __DIR__ . '/../../services/LoggerService.php';
        require_once __DIR__ . '/../../services/OpenAiReceiptParser.php';

        $this->parser = new \OpenAiReceiptParser();
        $this->emptyResult = [
            'store_name' => null, 'trip_date' => null, 'total_amount' => null, 'items' => [],
        ];
    }

    public function testParseModelContentMalformedJsonReturnsMalformedResponse(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, 'not json at all');

        $this->assertSame('malformed_response', $result['error_code']);
        $this->assertNotEmpty($result['error']);
    }

    public function testParseModelContentNoReceiptFoundReturnsParseFailed(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, json_encode(['error' => 'no_receipt_found']));

        $this->assertSame('parse_failed', $result['error_code']);
    }

    public function testParseModelContentNoItemsReturnsParseFailed(): void
    {
        $result = $this->parser->parseModelContent($this->emptyResult, json_encode(['store_name' => 'Kroger', 'items' => []]));

        $this->assertSame('parse_failed', $result['error_code']);
    }

    public function testParseModelContentValidReceiptMapsToReceiptShape(): void
    {
        $content = json_encode([
            'store_name' => 'Kroger',
            'trip_date' => '2026-07-20',
            'total_amount' => 24.53,
            'items' => [
                ['name' => 'Bananas', 'quantity' => 2.5, 'unit' => 'lb', 'price' => 1.98],
                ['name' => 'Milk', 'quantity' => 1, 'unit' => 'gallon', 'price' => 3.49],
                ['name' => 'unlabeled thing missing name'],
            ],
        ]);

        $result = $this->parser->parseModelContent($this->emptyResult, $content);

        $this->assertArrayNotHasKey('error_code', $result);
        $this->assertSame('Kroger', $result['store_name']);
        $this->assertSame('2026-07-20', $result['trip_date']);
        $this->assertSame(24.53, $result['total_amount']);
        $this->assertCount(3, $result['items']);
        $this->assertSame('Bananas', $result['items'][0]['name']);
        $this->assertSame(2.5, $result['items'][0]['quantity']);
        $this->assertSame('lb', $result['items'][0]['unit']);
        $this->assertSame(1.98, $result['items'][0]['price']);
    }

    public function testParseModelContentDropsItemsMissingName(): void
    {
        $content = json_encode([
            'items' => [
                ['quantity' => 1, 'price' => 2.00],
                ['name' => 'Eggs', 'quantity' => 1, 'unit' => 'dozen', 'price' => 4.29],
            ],
        ]);

        $result = $this->parser->parseModelContent($this->emptyResult, $content);

        $this->assertCount(1, $result['items']);
        $this->assertSame('Eggs', $result['items'][0]['name']);
    }

    public function testParseModelContentInvalidDateFormatBecomesNull(): void
    {
        $content = json_encode([
            'trip_date' => 'July 20th',
            'items' => [['name' => 'Bread', 'price' => 3.00]],
        ]);

        $result = $this->parser->parseModelContent($this->emptyResult, $content);

        $this->assertNull($result['trip_date']);
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
