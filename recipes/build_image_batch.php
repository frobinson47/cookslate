<?php
// Builds a Batch API JSONL file for GPT Image 2, one image-generation
// request per recipe JSON in this directory, using the "Premium Commercial
// Food Photography" prompt template with per-recipe subject/serving
// surface/camera angle filled in from each recipe's actual data.

$dir = __DIR__;
$outPath = $dir . '/image_batch.jsonl';

$template = <<<PROMPT
Create a premium commercial food photography image featuring {subject} as the hero dish, perfectly centered on {surface}, captured from {angle}.

Style requirements: Natural gourmet presentation, appetizing textures, realistic steam or freshness where appropriate, luxurious restaurant-quality lighting, balanced composition, cinematic color grading, shallow depth of field, ultra-sharp focus, authentic ingredients, premium table styling, flawless realism, high-end advertising quality, ultra-high resolution.

Exclude: text, logos, watermarks, borders, branding, or unwanted objects.
PROMPT;

// [keywords, serving surface, camera angle]
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

// Basic seasonings that add no visual value and just clutter the prompt.
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

$files = glob($dir . '/*.json');
sort($files);

$lines = [];
$skipped = [];

foreach ($files as $file) {
    $data = json_decode(file_get_contents($file), true);
    if (!$data || empty($data['title'])) {
        $skipped[] = basename($file);
        continue;
    }

    $subject = buildSubject($data);
    [$surface, $angle] = pickCategory($data['title'], $categories);

    $prompt = str_replace(
        ['{subject}', '{surface}', '{angle}'],
        [$subject, $surface, $angle],
        $template
    );

    $customId = isset($data['id']) ? (string) $data['id'] : pathinfo($file, PATHINFO_FILENAME);

    $lines[] = json_encode([
        'custom_id' => $customId,
        'method' => 'POST',
        'url' => '/v1/images/generations',
        'body' => [
            'model' => 'gpt-image-2',
            'prompt' => $prompt,
            'size' => '1536x1024',
            'quality' => 'high',
            'output_format' => 'jpeg',
            'output_compression' => 85,
        ],
    ], JSON_UNESCAPED_SLASHES);
}

file_put_contents($outPath, implode("\n", $lines) . "\n");

echo count($lines) . " requests written to $outPath\n";
if (!empty($skipped)) {
    echo "Skipped (no title): " . implode(', ', $skipped) . "\n";
}
