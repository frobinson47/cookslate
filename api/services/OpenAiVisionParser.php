<?php

require_once __DIR__ . '/VisionRecipeParser.php';
require_once __DIR__ . '/LoggerService.php';

/**
 * Extracts recipe data from a photo using OpenAI's vision-capable chat completions API.
 * Uses the user's own API key (BYOK) — never an app-level key.
 */
class OpenAiVisionParser implements VisionRecipeParser
{
    // Bump here when OpenAI ships a newer/cheaper vision-capable chat model.
    private const MODEL = 'gpt-4o-mini';
    private const API_URL = 'https://api.openai.com/v1/chat/completions';

    private const ERROR_MESSAGES = [
        'invalid_api_key' => 'Your OpenAI API key was rejected. Check it in Settings.',
        'rate_limited' => 'OpenAI rate-limited this request. Wait a moment and try again.',
        'request_failed' => "Couldn't reach OpenAI. Try again in a moment.",
        'parse_failed' => "Got a response but couldn't find recipe data in the image.",
        'malformed_response' => 'OpenAI returned an unexpected response. Try a clearer photo.',
    ];

    private const SYSTEM_PROMPT = <<<PROMPT
You extract recipe data from an image of a cookbook page, recipe card, or screenshot.
Respond with ONLY a JSON object matching this exact shape (no markdown, no commentary):
{
  "title": string,
  "description": string,
  "prep_time": number|null,
  "cook_time": number|null,
  "servings": number|null,
  "ingredients": [ { "name": string, "amount": string, "unit": string } ],
  "instructions": [ string ]
}
prep_time and cook_time are in minutes. If the image does not contain a recipe,
or the text is unreadable, respond with exactly: { "error": "no_recipe_found" }
Do not invent quantities or steps that aren't visible in the image.
PROMPT;

    public function parseImage(string $imageBase64, string $mimeType, string $apiKey): array
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
            LoggerService::channel('openai_vision')->error('cURL failure', ['curl_error' => $curlError]);
            return $this->fail($result, 'request_failed');
        }

        if ($httpCode === 401) {
            return $this->fail($result, 'invalid_api_key');
        }
        if ($httpCode === 429) {
            return $this->fail($result, 'rate_limited');
        }
        if ($httpCode !== 200) {
            LoggerService::channel('openai_vision')->error('Non-200 response', [
                'http_code' => $httpCode,
                'body' => substr($response, 0, 500),
            ]);
            return $this->fail($result, 'request_failed');
        }

        $body = json_decode($response, true);
        $content = $body['choices'][0]['message']['content'] ?? null;
        if (!is_string($content)) {
            LoggerService::channel('openai_vision')->error('Missing content in response', [
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
                    ['type' => 'text', 'text' => 'Extract the recipe from this image.'],
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
     * Parse the model's raw text content into the RecipeScraper-shaped result.
     * Pure/testable in isolation — no network calls.
     */
    public function parseModelContent(array $result, string $content): array
    {
        $parsed = json_decode($content, true);
        if (!is_array($parsed)) {
            LoggerService::channel('openai_vision')->error('Non-JSON content from model', [
                'content' => substr($content, 0, 500),
            ]);
            return $this->fail($result, 'malformed_response');
        }

        if (!empty($parsed['error']) || empty($parsed['title'])) {
            return $this->fail($result, 'parse_failed');
        }

        return $this->mapToRecipeShape($result, $parsed);
    }

    /**
     * Map the model's parsed JSON into the RecipeScraper-compatible array shape.
     * Pure/testable in isolation.
     */
    public function mapToRecipeShape(array $result, array $parsed): array
    {
        $ingredients = [];
        foreach (($parsed['ingredients'] ?? []) as $i => $ing) {
            if (is_string($ing)) {
                $ingredients[] = ['name' => $ing, 'amount' => '', 'unit' => '', 'sort_order' => $i];
                continue;
            }
            $ingredients[] = [
                'name' => (string) ($ing['name'] ?? ''),
                'amount' => (string) ($ing['amount'] ?? ''),
                'unit' => (string) ($ing['unit'] ?? ''),
                'sort_order' => $i,
            ];
        }

        $result['title'] = (string) ($parsed['title'] ?? '');
        $result['description'] = (string) ($parsed['description'] ?? '');
        $result['prep_time'] = isset($parsed['prep_time']) ? (int) $parsed['prep_time'] : null;
        $result['cook_time'] = isset($parsed['cook_time']) ? (int) $parsed['cook_time'] : null;
        $result['servings'] = isset($parsed['servings']) ? (int) $parsed['servings'] : null;
        $result['ingredients'] = $ingredients;
        $result['instructions'] = array_values(array_filter(array_map('strval', $parsed['instructions'] ?? [])));
        // image_url / source_url stay '' — there is no source page for a photo import.

        return $result;
    }

    private function emptyResult(): array
    {
        return [
            'title' => '',
            'description' => '',
            'prep_time' => null,
            'cook_time' => null,
            'servings' => null,
            'ingredients' => [],
            'instructions' => [],
            'image_url' => '',
            'source_url' => '',
        ];
    }

    private function fail(array $result, string $code): array
    {
        $result['error_code'] = $code;
        $result['error'] = self::ERROR_MESSAGES[$code] ?? self::ERROR_MESSAGES['request_failed'];
        return $result;
    }
}
