<?php

require_once __DIR__ . '/ReceiptVisionParser.php';
require_once __DIR__ . '/LoggerService.php';

/**
 * Extracts line-item receipt data from a photo using OpenAI's vision-capable
 * chat completions API. Uses the user's own API key (BYOK) — never an
 * app-level key.
 */
class OpenAiReceiptParser implements ReceiptVisionParser
{
    // Bump here when OpenAI ships a newer/cheaper vision-capable chat model.
    private const MODEL = 'gpt-4o-mini';
    private const API_URL = 'https://api.openai.com/v1/chat/completions';

    private const ERROR_MESSAGES = [
        'invalid_api_key' => 'Your OpenAI API key was rejected. Check it in Settings.',
        'rate_limited' => 'OpenAI rate-limited this request. Wait a moment and try again.',
        'request_failed' => "Couldn't reach OpenAI. Try again in a moment.",
        'parse_failed' => "Got a response but couldn't find receipt data in the image.",
        'malformed_response' => 'OpenAI returned an unexpected response. Try a clearer photo.',
    ];

    private const SYSTEM_PROMPT = <<<PROMPT
You extract data from a photo of a grocery/shopping receipt.
Respond with ONLY a JSON object matching this exact shape (no markdown, no commentary):
{
  "store_name": string|null,
  "trip_date": string|null,
  "total_amount": number|null,
  "items": [ { "name": string, "quantity": number|null, "unit": string|null, "price": number|null } ]
}
trip_date must be in YYYY-MM-DD format if present, otherwise null.
List every line item you can read, even if some fields are uncertain — set a
field to null rather than guessing. Do not include tax, subtotal, or total
lines as items. If the image is not a receipt, or the text is unreadable,
respond with exactly: { "error": "no_receipt_found" }
PROMPT;

    public function parseReceipt(string $imageBase64, string $mimeType, string $apiKey): array
    {
        $result = $this->emptyResult();

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($this->buildPayload($imageBase64, $mimeType)),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $caBundle = function_exists('getCaBundlePath') ? getCaBundlePath() : null;
        if ($caBundle) {
            curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            LoggerService::channel('openai_receipt')->error('cURL failure', ['curl_error' => $curlError]);
            return $this->fail($result, 'request_failed');
        }

        if ($httpCode === 401) {
            return $this->fail($result, 'invalid_api_key');
        }
        if ($httpCode === 429) {
            return $this->fail($result, 'rate_limited');
        }
        if ($httpCode !== 200) {
            LoggerService::channel('openai_receipt')->error('Non-200 response', [
                'http_code' => $httpCode,
                'body' => substr($response, 0, 500),
            ]);
            return $this->fail($result, 'request_failed');
        }

        $body = json_decode($response, true);
        $content = $body['choices'][0]['message']['content'] ?? null;
        if (!is_string($content)) {
            LoggerService::channel('openai_receipt')->error('Missing content in response', [
                'body' => substr($response, 0, 500),
            ]);
            return $this->fail($result, 'malformed_response');
        }

        return $this->parseModelContent($result, $content);
    }

    /**
     * Build the chat completions request payload. Pure/testable in isolation.
     */
    public function buildPayload(string $imageBase64, string $mimeType): array
    {
        return [
            'model' => self::MODEL,
            'messages' => [
                ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                ['role' => 'user', 'content' => [
                    ['type' => 'text', 'text' => 'Extract the line items from this receipt.'],
                    ['type' => 'image_url', 'image_url' => [
                        'url' => "data:{$mimeType};base64,{$imageBase64}",
                        'detail' => 'high',
                    ]],
                ]],
            ],
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 2000,
            'temperature' => 0.2,
        ];
    }

    /**
     * Parse the model's raw text content into the receipt-shaped result.
     * Pure/testable in isolation — no network calls.
     */
    public function parseModelContent(array $result, string $content): array
    {
        $parsed = json_decode($content, true);
        if (!is_array($parsed)) {
            LoggerService::channel('openai_receipt')->error('Non-JSON content from model', [
                'content' => substr($content, 0, 500),
            ]);
            return $this->fail($result, 'malformed_response');
        }

        if (!empty($parsed['error']) || empty($parsed['items'])) {
            return $this->fail($result, 'parse_failed');
        }

        return $this->mapToReceiptShape($result, $parsed);
    }

    /**
     * Map the model's parsed JSON into the receipt result shape.
     * Pure/testable in isolation.
     */
    public function mapToReceiptShape(array $result, array $parsed): array
    {
        $items = [];
        foreach (($parsed['items'] ?? []) as $item) {
            if (!is_array($item) || empty($item['name'])) {
                continue;
            }
            $items[] = [
                'name' => (string) $item['name'],
                'quantity' => isset($item['quantity']) && is_numeric($item['quantity']) ? (float) $item['quantity'] : null,
                'unit' => isset($item['unit']) && $item['unit'] !== '' ? (string) $item['unit'] : null,
                'price' => isset($item['price']) && is_numeric($item['price']) ? (float) $item['price'] : null,
            ];
        }

        $result['store_name'] = isset($parsed['store_name']) && $parsed['store_name'] !== '' ? (string) $parsed['store_name'] : null;
        $result['trip_date'] = $this->normalizeDate($parsed['trip_date'] ?? null);
        $result['total_amount'] = isset($parsed['total_amount']) && is_numeric($parsed['total_amount']) ? (float) $parsed['total_amount'] : null;
        $result['items'] = $items;

        return $result;
    }

    private function normalizeDate(mixed $value): ?string
    {
        if (!is_string($value) || $value === '') {
            return null;
        }
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? $value : null;
    }

    private function emptyResult(): array
    {
        return [
            'store_name' => null,
            'trip_date' => null,
            'total_amount' => null,
            'items' => [],
        ];
    }

    private function fail(array $result, string $code): array
    {
        $result['error_code'] = $code;
        $result['error'] = self::ERROR_MESSAGES[$code] ?? self::ERROR_MESSAGES['request_failed'];
        return $result;
    }
}
