<?php

require_once __DIR__ . '/LoggerService.php';

/**
 * Derives the short copy fragments (subtitle/slogan/quote/etc) that card art
 * prompts need but the recipes schema doesn't store, via a cheap chat-completion
 * call. Failures degrade gracefully — callers fall back to empty strings rather
 * than blocking image generation.
 */
class CardArtEnricher
{
    private const MODEL = 'gpt-4o-mini';
    private const API_URL = 'https://api.openai.com/v1/chat/completions';

    private const SYSTEM_PROMPT = <<<PROMPT
You write short, tasteful marketing copy fragments for a printable recipe card design.
Given a recipe, return ONLY a JSON object with these exact keys:
subtitle (a short 3-6 word evocative subtitle), slogan (a short 4-8 word tagline),
quote (a short first-person 8-15 word pull-quote a home cook might say about this dish),
main_ingredient (the single most visually interesting ingredient, 1-3 words),
cooking_vibe (2-4 words describing the mood/atmosphere),
color_palette (3-4 comma-separated color words matching the dish),
headline_style (2-5 word punchy headline fragment).
No commentary, JSON only.
PROMPT;

    /**
     * @return array{subtitle:string,slogan:string,quote:string,main_ingredient:string,cooking_vibe:string,color_palette:string,headline_style:string}
     */
    public function enrich(array $recipe, string $apiKey): array
    {
        $empty = [
            'subtitle' => '', 'slogan' => '', 'quote' => '', 'main_ingredient' => '',
            'cooking_vibe' => '', 'color_palette' => '', 'headline_style' => '',
        ];

        $ingredientNames = array_map(fn($i) => $i['name'] ?? '', $recipe['ingredients'] ?? []);
        $userContent = "Recipe: " . ($recipe['title'] ?? '') . "\n"
            . "Ingredients: " . implode(', ', $ingredientNames) . "\n"
            . "Instructions: " . implode(' ', $recipe['instructions'] ?? []);

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'model' => self::MODEL,
                'messages' => [
                    ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                    ['role' => 'user', 'content' => $userContent],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
                'max_tokens' => 300,
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

        if ($response === false || $httpCode !== 200) {
            LoggerService::channel('card_art')->warning('Enrichment call failed, using empty fields', [
                'http_code' => $httpCode,
                'curl_error' => $curlError,
            ]);
            return $empty;
        }

        $body = json_decode($response, true);
        $content = $body['choices'][0]['message']['content'] ?? null;
        $parsed = is_string($content) ? json_decode($content, true) : null;
        if (!is_array($parsed)) {
            LoggerService::channel('card_art')->warning('Enrichment returned non-JSON content, using empty fields');
            return $empty;
        }

        foreach ($empty as $key => $_) {
            $empty[$key] = isset($parsed[$key]) ? (string) $parsed[$key] : '';
        }

        return $empty;
    }
}
