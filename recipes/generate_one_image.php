<?php
// One-off equivalent of build_image_batch.php + process_batch_output.php,
// for generating a single recipe's photo without going through the async
// Batch API. Same "Premium Commercial Food Photography" prompt template.
//
// Usage:
//   OPENAI_API_KEY=sk-... php generate_one_image.php <recipe_id> [home_api_base_url]
//
// Fetches recipe data live from the given API (default home.cookslate.app),
// generates the image synchronously, and saves full.webp/thumb.webp into the
// LOCAL uploads/ dir (same as api/config/constants.php UPLOAD_DIR). Deploying
// to a remote container still requires `docker cp` as with the batch flow.

require_once __DIR__ . '/../api/config/constants.php';

$recipeId = $argv[1] ?? null;
$apiBase = rtrim($argv[2] ?? 'https://home.cookslate.app', '/');

if (!$recipeId || !ctype_digit($recipeId)) {
    fwrite(STDERR, "Usage: php generate_one_image.php <recipe_id> [api_base_url]\n");
    exit(1);
}

$apiKey = getenv('OPENAI_API_KEY');
if (!$apiKey) {
    fwrite(STDERR, "OPENAI_API_KEY is not set in the environment.\n");
    exit(1);
}

$template = <<<PROMPT
Create a premium commercial food photography image featuring {subject} as the hero dish, perfectly centered on {surface}, captured from {angle}.

Style requirements: Natural gourmet presentation, appetizing textures, realistic steam or freshness where appropriate, luxurious restaurant-quality lighting, balanced composition, cinematic color grading, shallow depth of field, ultra-sharp focus, authentic ingredients, premium table styling, flawless realism, high-end advertising quality, ultra-high resolution.

Exclude: text, logos, watermarks, borders, branding, or unwanted objects.
PROMPT;

$categories = [
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

function pickCategory(string $title, array $categories): array {
    $lower = strtolower($title);
    foreach ($categories as [$keywords, $surface, $angle]) {
        foreach ($keywords as $kw) {
            if (str_contains($lower, $kw)) {
                return [$surface, $angle];
            }
        }
    }
    return ['a white ceramic plate', 'a 45-degree angle'];
}

function cleanIngredientName(string $name): string {
    $n = trim($name);
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

const BASIC_SEASONINGS = ['salt', 'kosher salt', 'sea salt', 'black pepper', 'pepper',
    'freshly ground black pepper', 'water', 'oil', 'olive oil', 'neutral oil', 'cooking oil'];

function isBasicSeasoning(string $name): bool {
    return in_array(strtolower($name), BASIC_SEASONINGS, true);
}

function buildSubject(array $recipe): string {
    $title = $recipe['title'];
    $names = array_map(
        fn($i) => cleanIngredientName($i['name']),
        $recipe['ingredients'] ?? []
    );
    $names = array_values(array_filter($names, fn($n) => $n !== '' && !isBasicSeasoning($n)));
    $names = array_slice($names, 0, 6);
    if (empty($names)) {
        return $title;
    }
    return $title . ', made with ' . implode(', ', $names);
}

function resizeAndSave($source, int $origWidth, int $origHeight, int $maxWidth, int $quality, string $outputPath): void {
    if ($origWidth <= $maxWidth) {
        $newWidth = $origWidth;
        $newHeight = $origHeight;
    } else {
        $newWidth = $maxWidth;
        $newHeight = (int) round($origHeight * ($maxWidth / $origWidth));
    }

    $resized = imagecreatetruecolor($newWidth, $newHeight);
    imagesavealpha($resized, true);
    $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
    imagefill($resized, 0, 0, $transparent);
    imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    imagewebp($resized, $outputPath, $quality);
    imagedestroy($resized);
}

// Fetch recipe data.
$json = @file_get_contents("$apiBase/api/recipes/$recipeId");
$data = $json ? json_decode($json, true) : null;
if (!$data || empty($data['title'])) {
    fwrite(STDERR, "Could not fetch recipe $recipeId from $apiBase\n");
    exit(1);
}

$subject = buildSubject($data);
[$surface, $angle] = pickCategory($data['title'], $categories);
$prompt = str_replace(['{subject}', '{surface}', '{angle}'], [$subject, $surface, $angle], $template);

echo "Recipe: {$data['title']}\n";
echo "Prompt: $prompt\n\n";
echo "Generating image...\n";

// Synchronous (non-batch) image generation call.
$ch = curl_init('https://api.openai.com/v1/images/generations');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        "Authorization: Bearer $apiKey",
    ],
    CURLOPT_POSTFIELDS => json_encode([
        'model' => 'gpt-image-2',
        'prompt' => $prompt,
        'size' => '1536x1024',
        'quality' => 'high',
        'output_format' => 'jpeg',
        'output_compression' => 85,
    ]),
    CURLOPT_TIMEOUT => 120,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

if ($response === false) {
    fwrite(STDERR, "cURL error: $curlErr\n");
    exit(1);
}

$result = json_decode($response, true);
$b64 = $result['data'][0]['b64_json'] ?? null;
if ($httpCode !== 200 || !$b64) {
    fwrite(STDERR, "OpenAI API error (HTTP $httpCode): $response\n");
    exit(1);
}

$imageData = base64_decode($b64, true);
$source = @imagecreatefromstring($imageData);
if ($source === false) {
    fwrite(STDERR, "Failed to decode generated image.\n");
    exit(1);
}

$origWidth = imagesx($source);
$origHeight = imagesy($source);

$outputDir = UPLOAD_DIR . 'recipes' . DIRECTORY_SEPARATOR . $recipeId;
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

resizeAndSave($source, $origWidth, $origHeight, IMAGE_MAX_WIDTH, IMAGE_QUALITY, $outputDir . DIRECTORY_SEPARATOR . 'full.webp');
resizeAndSave($source, $origWidth, $origHeight, THUMB_MAX_WIDTH, THUMB_QUALITY, $outputDir . DIRECTORY_SEPARATOR . 'thumb.webp');
imagedestroy($source);

echo "Saved to $outputDir\\full.webp and thumb.webp\n";
echo "If deploying to a remote container:\n";
echo "  docker cp \"$outputDir\" <container>:/path/to/uploads/recipes/$recipeId\n";
