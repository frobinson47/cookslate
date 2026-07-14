<?php

require_once __DIR__ . '/CardArtEnricher.php';
require_once __DIR__ . '/CardArtTemplates.php';
require_once __DIR__ . '/LoggerService.php';

/**
 * Generates styled recipe card art via OpenAI's image API. Uses the user's
 * own API key (BYOK) — never an app-level key. Real image generations take
 * 90-240s in practice, so this is meant to be called from a route with a
 * generous timeout, not a fast request/response cycle.
 */
class OpenAiCardArtGenerator
{
    private const MODEL = 'gpt-image-2';
    private const API_URL = 'https://api.openai.com/v1/images/generations';
    private const TIMEOUT_SECONDS = 240;

    private const ERROR_MESSAGES = [
        'invalid_api_key' => 'Your OpenAI API key was rejected. Check it in Settings.',
        'rate_limited' => 'OpenAI rate-limited this request. Wait a moment and try again.',
        'request_failed' => "Couldn't reach OpenAI. Try again in a moment.",
        'malformed_response' => 'OpenAI returned an unexpected response.',
    ];

    /**
     * @return array{success:bool,imageData?:string,error_code?:string,error?:string}
     */
    public function generate(string $template, array $recipe, string $apiKey): array
    {
        if (!CardArtTemplates::isValid($template)) {
            return ['success' => false, 'error_code' => 'invalid_template', 'error' => 'Unknown template.'];
        }

        $enrichment = (new CardArtEnricher())->enrich($recipe, $apiKey);
        $prompt = CardArtTemplates::buildPrompt($template, $recipe, $enrichment);
        $size = CardArtTemplates::sizeFor($template);

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::TIMEOUT_SECONDS,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'model' => self::MODEL,
                'prompt' => $prompt,
                'size' => $size,
                'quality' => 'high',
            ]),
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
            LoggerService::channel('card_art')->error('cURL failure', ['curl_error' => $curlError]);
            return $this->fail('request_failed');
        }

        if ($httpCode === 401) {
            return $this->fail('invalid_api_key');
        }
        if ($httpCode === 429) {
            return $this->fail('rate_limited');
        }
        if ($httpCode !== 200) {
            LoggerService::channel('card_art')->error('Non-200 response', [
                'http_code' => $httpCode,
                'body' => substr($response, 0, 500),
            ]);
            return $this->fail('request_failed');
        }

        $body = json_decode($response, true);
        $b64 = $body['data'][0]['b64_json'] ?? null;
        if (!is_string($b64)) {
            LoggerService::channel('card_art')->error('Missing image data in response', [
                'body' => substr($response, 0, 300),
            ]);
            return $this->fail('malformed_response');
        }

        $imageData = base64_decode($b64, true);
        if ($imageData === false) {
            return $this->fail('malformed_response');
        }

        return ['success' => true, 'imageData' => $imageData];
    }

    private function fail(string $code): array
    {
        return [
            'success' => false,
            'error_code' => $code,
            'error' => self::ERROR_MESSAGES[$code] ?? self::ERROR_MESSAGES['request_failed'],
        ];
    }
}
