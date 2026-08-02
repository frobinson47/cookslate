<?php
// Fixes for 3 recipes:
// - 294, 295: id-reuse bug — these ids were regenerated with the WRONG recipe's
//   image (an old export's stale id mapping). Regenerate correctly against their
//   real current titles/ingredients.
// - 307: AI generation came out looking like pasta/noodles instead of a melted
//   cheese dip. Same recipe, clarified prompt.

$outPath = __DIR__ . '/image_batch_fix.jsonl';

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

// id => extra clarifying note appended to the subject (empty string = none)
$fixes = [
    294 => '',
    295 => '',
    307 => ', served as a warm melted cheese dip with a smooth creamy consistency (not pasta, not noodles, not spaghetti strands)',
];

$lines = [];
$skipped = [];

foreach ($fixes as $id => $extraNote) {
    $json = @file_get_contents("https://home.cookslate.app/api/recipes/{$id}");
    $data = $json ? json_decode($json, true) : null;

    if (!$data || empty($data['title'])) {
        $skipped[] = $id;
        continue;
    }

    $subject = buildSubject($data) . $extraNote;
    [$surface, $angle] = pickCategory($data['title'], $categories);

    $prompt = str_replace(
        ['{subject}', '{surface}', '{angle}'],
        [$subject, $surface, $angle],
        $template
    );

    $lines[] = json_encode([
        'custom_id' => (string) $id,
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
    echo "Skipped: " . implode(', ', $skipped) . "\n";
}
