<?php

require_once __DIR__ . '/LoggerService.php';

/**
 * Generates a hero recipe photo via OpenAI's image API, using the same
 * "Premium Commercial Food Photography" prompt template used for the
 * original bulk recipe photo regeneration (see recipes/build_image_batch.php).
 * Uses the calling admin's own OpenAI API key (BYOK) — never an app-level key.
 * Real image generations take 30-120s in practice, so this is meant to be
 * called from a route with a generous timeout, not a fast request/response cycle.
 */
class AiRecipePhotoGenerator
{
    private const MODEL = 'gpt-image-2';
    private const API_URL = 'https://api.openai.com/v1/images/generations';
    private const TIMEOUT_SECONDS = 240;

    private const PROMPT_TEMPLATE = <<<PROMPT
Create a premium commercial food photography image featuring {subject} as the hero dish, perfectly centered on {surface}, captured from {angle}.

Style requirements: Natural gourmet presentation, appetizing textures, realistic steam or freshness where appropriate, luxurious restaurant-quality lighting, balanced composition, cinematic color grading, shallow depth of field, ultra-sharp focus, authentic ingredients, premium table styling, flawless realism, high-end advertising quality, ultra-high resolution.

Exclude: text, logos, watermarks, borders, branding, or unwanted objects.
PROMPT;

    // [keywords, serving surface, camera angle]
    private const CATEGORIES = [
        [['punch', 'lemonade', 'cocktail', 'smoothie', 'shake', 'cider', 'tea', 'coffee', 'drink'],
            'a chilled glass with ice', 'a close eye-level angle'],
        [['muffin', 'bread', 'cake', 'cookie', 'pie', 'scone', 'waffle', 'pancake', 'cobbler', 'crumble'],
            'a rustic wooden board with a linen napkin', 'a 45-degree angle'],
        [['soup', 'stew', 'chili', 'bisque'],
            'a rustic ceramic bowl', 'an overhead top-down angle'],
        [['salad'],
            'a wide shallow ceramic bowl', 'an overhead top-down angle'],
        [['dip', 'sauce', 'dressing', 'salsa', 'gravy'],
            'a small rustic bowl with dippers arranged alongside', 'a 45-degree angle'],
        [['casserole', 'roast', 'skillet', 'bake', 'pasta', 'alfredo'],
            'a rustic ceramic platter', 'a 45-degree angle'],
    ];

    private const BASIC_SEASONINGS = ['salt', 'kosher salt', 'sea salt', 'black pepper', 'pepper',
        'freshly ground black pepper', 'water', 'oil', 'olive oil', 'neutral oil', 'cooking oil'];

    private const ERROR_MESSAGES = [
        'invalid_api_key' => 'Your OpenAI API key was rejected. Check it in Settings.',
        'rate_limited' => 'OpenAI rate-limited this request. Wait a moment and try again.',
        'request_failed' => "Couldn't reach OpenAI. Try again in a moment.",
        'malformed_response' => 'OpenAI returned an unexpected response.',
    ];

    /**
     * @return array{success:bool,imageData?:string,prompt?:string,error_code?:string,error?:string}
     */
    public function generate(array $recipe, string $apiKey): array
    {
        $prompt = $this->buildPrompt($recipe);

        $ch = curl_init(self::API_URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => self::TIMEOUT_SECONDS,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'model' => self::MODEL,
                'prompt' => $prompt,
                'size' => '1536x1024',
                'quality' => 'high',
                'output_format' => 'jpeg',
                'output_compression' => 85,
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
            LoggerService::channel('ai_photo')->error('cURL failure', ['curl_error' => $curlError]);
            return $this->fail('request_failed', $prompt);
        }

        if ($httpCode === 401) {
            return $this->fail('invalid_api_key', $prompt);
        }
        if ($httpCode === 429) {
            return $this->fail('rate_limited', $prompt);
        }
        if ($httpCode !== 200) {
            LoggerService::channel('ai_photo')->error('Non-200 response', [
                'http_code' => $httpCode,
                'body' => substr($response, 0, 500),
            ]);
            return $this->fail('request_failed', $prompt);
        }

        $body = json_decode($response, true);
        $b64 = $body['data'][0]['b64_json'] ?? null;
        if (!is_string($b64)) {
            LoggerService::channel('ai_photo')->error('Missing image data in response', [
                'body' => substr($response, 0, 300),
            ]);
            return $this->fail('malformed_response', $prompt);
        }

        $imageData = base64_decode($b64, true);
        if ($imageData === false) {
            return $this->fail('malformed_response', $prompt);
        }

        return ['success' => true, 'imageData' => $imageData, 'prompt' => $prompt];
    }

    private function buildPrompt(array $recipe): string
    {
        $subject = $this->buildSubject($recipe);
        [$surface, $angle] = $this->pickCategory($recipe['title'] ?? '');

        return str_replace(
            ['{subject}', '{surface}', '{angle}'],
            [$subject, $surface, $angle],
            self::PROMPT_TEMPLATE
        );
    }

    private function pickCategory(string $title): array
    {
        $lower = strtolower($title);
        foreach (self::CATEGORIES as [$keywords, $surface, $angle]) {
            foreach ($keywords as $kw) {
                if (str_contains($lower, $kw)) {
                    return [$surface, $angle];
                }
            }
        }
        return ['a white ceramic plate', 'a 45-degree angle'];
    }

    private function buildSubject(array $recipe): string
    {
        $title = $recipe['title'] ?? 'this dish';
        $names = array_map(
            fn($i) => $this->cleanIngredientName($i['name'] ?? ''),
            $recipe['ingredients'] ?? []
        );
        $names = array_values(array_filter(
            $names,
            fn($n) => $n !== '' && !in_array(strtolower($n), self::BASIC_SEASONINGS, true)
        ));
        $names = array_slice($names, 0, 6);

        if (empty($names)) {
            return $title;
        }
        return $title . ', made with ' . implode(', ', $names);
    }

    private function cleanIngredientName(string $name): string
    {
        $n = trim($name);
        // Drop parentheticals, including nested ones — loop until stable since a
        // single pass on "(chopped for garnish (optional))" leaves a stray ")".
        do {
            $prev = $n;
            $n = preg_replace('/\s*\([^()]*\)/', '', $n);
        } while ($n !== $prev);
        $n = preg_replace('/^[\d\x{00BC}-\x{00BE}\x{2150}-\x{215E}\s\/.\-]+\s*(cup|cups|tbsp|tsp|oz|lb|lbs|g|kg|ml|l|can|cans|package|packages?|scoops?)?\s*/iu', '', $n);
        $n = preg_replace('/,?\s*(plus|or)\s+more\s+(to\s+taste|as\s+needed)$/i', '', $n);
        $n = preg_replace('/,?\s*to\s+taste$/i', '', $n);
        $n = trim($n, " \t\n\r\0\x0B)(");
        return $n === '' ? trim($name) : $n;
    }

    private function fail(string $code, string $prompt): array
    {
        return [
            'success' => false,
            'error_code' => $code,
            'error' => self::ERROR_MESSAGES[$code] ?? self::ERROR_MESSAGES['request_failed'],
            'prompt' => $prompt,
        ];
    }
}
