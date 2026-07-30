<?php

require_once __DIR__ . '/LoggerService.php';

/**
 * Extracts a rough pantry inventory from a single photo of a fridge, pantry,
 * or freezer shelf using OpenAI's vision-capable chat completions API. Uses
 * the user's own API key (BYOK) — never an app-level key. Unlike receipt
 * scanning, there's no store/date/total — just item name + rough quantity.
 */
class OpenAiPantryScanParser
{
    // Bump here when OpenAI ships a newer/cheaper vision-capable chat model.
    private const MODEL = 'gpt-4o-mini';
    private const API_URL = 'https://api.openai.com/v1/chat/completions';

    private const ERROR_MESSAGES = [
        'invalid_api_key' => 'Your OpenAI API key was rejected. Check it in Settings.',
        'rate_limited' => 'OpenAI rate-limited this request. Wait a moment and try again.',
        'request_failed' => "Couldn't reach OpenAI. Try again in a moment.",
        'parse_failed' => "Got a response but couldn't find any food items in the photo.",
        'malformed_response' => 'OpenAI returned an unexpected response. Try a clearer photo.',
    ];

    private const SYSTEM_PROMPT = <<<PROMPT
You extract a rough food inventory from a photo of a fridge shelf, pantry
shelf, or freezer. Respond with ONLY a JSON object matching this exact shape
(no markdown, no commentary):
{
  "items": [ { "name": string, "quantity": number|null, "unit": string|null } ]
}
List every distinct food item you can identify, even if some are uncertain —
set quantity/unit to null rather than guessing precisely. Estimate a rough
count or amount only when it's visually obvious (e.g. "3" eggs, "1" jar).
Do not list non-food items (containers, shelves, appliances). If the image
shows no food items, or isn't a fridge/pantry/freezer photo, respond with
exactly: { "error": "no_items_found" }
PROMPT;

    public function parsePantryScan(string $imageBase64, string $mimeType, string $apiKey): array
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
            LoggerService::channel('openai_pantry_scan')->error('cURL failure', ['curl_error' => $curlError]);
            return $this->fail($result, 'request_failed');
        }

        if ($httpCode === 401) {
            return $this->fail($result, 'invalid_api_key');
        }
        if ($httpCode === 429) {
            return $this->fail($result, 'rate_limited');
        }
        if ($httpCode !== 200) {
            LoggerService::channel('openai_pantry_scan')->error('Non-200 response', [
                'http_code' => $httpCode,
                'body' => substr($response, 0, 500),
            ]);
            return $this->fail($result, 'request_failed');
        }

        $body = json_decode($response, true);
        $content = $body['choices'][0]['message']['content'] ?? null;
        if (!is_string($content)) {
            LoggerService::channel('openai_pantry_scan')->error('Missing content in response', [
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
                    ['type' => 'text', 'text' => 'Identify the food items visible in this photo.'],
                    ['type' => 'image_url', 'image_url' => [
                        'url' => "data:{$mimeType};base64,{$imageBase64}",
                        'detail' => 'high',
                    ]],
                ]],
            ],
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 1500,
            'temperature' => 0.2,
        ];
    }

    /**
     * Parse the model's raw text content into the pantry-scan result shape.
     * Pure/testable in isolation — no network calls.
     */
    public function parseModelContent(array $result, string $content): array
    {
        $parsed = json_decode($content, true);
        if (!is_array($parsed)) {
            LoggerService::channel('openai_pantry_scan')->error('Non-JSON content from model', [
                'content' => substr($content, 0, 500),
            ]);
            return $this->fail($result, 'malformed_response');
        }

        if (!empty($parsed['error']) || empty($parsed['items'])) {
            return $this->fail($result, 'parse_failed');
        }

        return $this->mapToPantryShape($result, $parsed);
    }

    private function mapToPantryShape(array $result, array $parsed): array
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
            ];
        }

        $result['items'] = $items;
        return $result;
    }

    private function emptyResult(): array
    {
        return ['items' => []];
    }

    private function fail(array $result, string $code): array
    {
        $result['error_code'] = $code;
        $result['error'] = self::ERROR_MESSAGES[$code] ?? self::ERROR_MESSAGES['request_failed'];
        return $result;
    }
}
