<?php

require_once __DIR__ . '/LoggerService.php';

/**
 * TheMealDB API client.
 * Free API for recipe discovery and inspiration.
 * Docs: https://www.themealdb.com/api.php
 */
class MealDbClient
{
    private const BASE_URL = 'https://www.themealdb.com/api/json/v1/1';

    /**
     * Search meals by name.
     */
    public function search(string $query): array
    {
        $data = $this->fetch('/search.php?s=' . urlencode($query));
        return $this->normalizeMeals($data['meals'] ?? []);
    }

    /**
     * Get a random meal.
     */
    public function random(): ?array
    {
        $data = $this->fetch('/random.php');
        $meals = $this->normalizeMeals($data['meals'] ?? []);
        return $meals[0] ?? null;
    }

    /**
     * Filter by category (e.g., "Seafood", "Dessert").
     */
    public function byCategory(string $category): array
    {
        $data = $this->fetch('/filter.php?c=' . urlencode($category));
        return $this->normalizeFiltered($data['meals'] ?? []);
    }

    /**
     * Filter by area/cuisine (e.g., "Italian", "Mexican").
     */
    public function byArea(string $area): array
    {
        $data = $this->fetch('/filter.php?a=' . urlencode($area));
        return $this->normalizeFiltered($data['meals'] ?? []);
    }

    // TheMealDB has no diet data, only categories and sparse free-text tags, so
    // the diets below are derived from those. 'estimated' diets are heuristics.
    private const DIETS = [
        'vegetarian' => ['label' => 'Vegetarian', 'estimated' => false],
        'vegan'      => ['label' => 'Vegan', 'estimated' => false],
        'carnivore'  => ['label' => 'Carnivore', 'estimated' => true],
        'keto'       => ['label' => 'Keto-friendly', 'estimated' => true],
    ];

    private const DIET_ALIASES = [
        'vegetarian' => 'vegetarian', 'veggie' => 'vegetarian',
        'vegan' => 'vegan', 'plant based' => 'vegan', 'plant-based' => 'vegan',
        'carnivore' => 'carnivore', 'carnivore diet' => 'carnivore',
        'keto' => 'keto', 'keto friendly' => 'keto', 'keto-friendly' => 'keto',
        'low carb' => 'keto', 'low-carb' => 'keto', 'lowcarb' => 'keto',
    ];

    private const MEAT_CATEGORIES = ['Beef', 'Chicken', 'Lamb', 'Pork', 'Seafood', 'Goat'];

    private const HIGH_CARB = '/\b(flour|sugar|rice|potato|yam|pasta|spaghetti|penne|macaroni|lasagne|noodle|bread|crumb|bun|wrap|tortilla|pastry|dough|cracker|biscuit|couscous|oat|corn|bean|lentil|chickpea|pea|tapioca|honey|syrup|maple|jam|ketchup|banana|apple|mango|pineapple)/i';

    private const CATALOG_TTL = 7 * 24 * 3600;

    /**
     * Map a search query to a diet key, or null if it isn't a diet term.
     */
    public static function dietForQuery(string $query): ?string
    {
        return self::DIET_ALIASES[strtolower(trim($query))] ?? null;
    }

    public static function dietInfo(string $diet): ?array
    {
        return isset(self::DIETS[$diet]) ? ['key' => $diet] + self::DIETS[$diet] : null;
    }

    /**
     * Meals matching a diet, from the cached full catalog.
     */
    public function byDiet(string $diet): array
    {
        if (!isset(self::DIETS[$diet])) return [];

        $meals = array_filter($this->catalog(), function ($m) use ($diet) {
            $tags = array_map('strtolower', $m['tags']);
            $isMeat = in_array($m['category'], self::MEAT_CATEGORIES, true);
            switch ($diet) {
                case 'vegan':
                    return $m['category'] === 'Vegan' || in_array('vegan', $tags, true);
                case 'vegetarian':
                    return in_array($m['category'], ['Vegetarian', 'Vegan'], true)
                        || in_array('vegetarian', $tags, true) || in_array('vegan', $tags, true);
                case 'carnivore':
                    return $isMeat;
                case 'keto':
                    if (array_intersect($tags, ['keto', 'lowcarbs', 'paleo'])) return true;
                    if (!$isMeat) return false;
                    foreach ($m['ingredients'] as $ing) {
                        if (preg_match(self::HIGH_CARB, $ing)) return false;
                    }
                    return true;
            }
            return false;
        });

        return array_values(array_map(fn($m) => [
            'mealdb_id' => $m['mealdb_id'],
            'title' => $m['title'],
            'image' => $m['image'],
            'area' => $m['area'],
        ], $meals));
    }

    /**
     * Every TheMealDB meal (light records), cached on disk. The API can only
     * list by first letter, so building it takes ~26 requests; a partial
     * build is returned but never cached. Requests run in parallel.
     */
    private function catalog(): array
    {
        $file = sys_get_temp_dir() . '/cookslate_mealdb_catalog.json';
        if (is_file($file) && time() - filemtime($file) < self::CATALOG_TTL) {
            $cached = json_decode((string)file_get_contents($file), true);
            if (is_array($cached) && $cached) return $cached;
        }

        $catalog = [];
        $complete = true;
        $letters = str_split('abcdefghijklmnopqrstuvwxyz');
        $results = $this->fetchMany(array_map(fn($l) => '/search.php?f=' . $l, $letters));
        foreach ($results as $data) {
            if ($data === null || !array_key_exists('meals', $data)) {
                $complete = false;
                continue;
            }
            foreach ($this->normalizeMeals($data['meals'] ?? []) as $m) {
                $catalog[$m['mealdb_id']] = [
                    'mealdb_id' => $m['mealdb_id'],
                    'title' => $m['title'],
                    'image' => $m['image'],
                    'area' => $m['area'],
                    'category' => $m['category'],
                    'tags' => $m['tags'],
                    'ingredients' => array_column($m['ingredients'], 'name'),
                ];
            }
        }

        $catalog = array_values($catalog);
        if ($complete && $catalog) {
            $tmp = $file . '.' . getmypid() . '.tmp';
            if (@file_put_contents($tmp, json_encode($catalog)) !== false) {
                @rename($tmp, $file);
            }
        }
        return $catalog;
    }

    /**
     * Get full meal details by TheMealDB ID.
     */
    public function getById(string $id): ?array
    {
        $data = $this->fetch('/lookup.php?i=' . urlencode($id));
        $meals = $this->normalizeMeals($data['meals'] ?? []);
        return $meals[0] ?? null;
    }

    /**
     * List all categories.
     */
    public function categories(): array
    {
        $data = $this->fetch('/categories.php');
        return array_map(function ($cat) {
            return [
                'name' => $cat['strCategory'],
                'image' => $cat['strCategoryThumb'] ?? null,
                'description' => $cat['strCategoryDescription'] ?? '',
            ];
        }, $data['categories'] ?? []);
    }

    /**
     * List all areas/cuisines.
     */
    public function areas(): array
    {
        $data = $this->fetch('/list.php?a=list');
        return array_map(fn($a) => $a['strArea'], $data['meals'] ?? []);
    }

    /**
     * Convert a TheMealDB meal to Cookslate recipe format for import.
     */
    public function toRecipeFormat(array $meal): array
    {
        return [
            'title' => $meal['title'],
            'description' => $meal['area'] ? "A {$meal['area']} dish" : '',
            'source_url' => $meal['source'] ?? '',
            'source_image_url' => $meal['image'] ?? '',
            'servings' => 4,
            'instructions' => $meal['instructions'] ?? [],
            'ingredients' => $meal['ingredients'] ?? [],
            'tags' => array_filter(array_merge(
                $meal['tags'] ?? [],
                $meal['category'] ? [$meal['category']] : [],
                $meal['area'] ? [$meal['area']] : []
            )),
        ];
    }

    /**
     * Normalize full meal objects from TheMealDB format.
     */
    private function normalizeMeals(?array $meals): array
    {
        if (!$meals) return [];

        return array_map(function ($m) {
            // Extract ingredients (TheMealDB uses strIngredient1-20 / strMeasure1-20)
            $ingredients = [];
            for ($i = 1; $i <= 20; $i++) {
                $name = trim($m["strIngredient{$i}"] ?? '');
                $measure = trim($m["strMeasure{$i}"] ?? '');
                if (!empty($name)) {
                    $ingredients[] = [
                        'name' => $name,
                        'amount' => $this->extractAmount($measure),
                        'unit' => $this->extractUnit($measure),
                    ];
                }
            }

            // Parse instructions into steps
            $rawInstructions = $m['strInstructions'] ?? '';
            $steps = array_filter(array_map('trim', preg_split('/\r?\n/', $rawInstructions)));
            // Remove empty lines and step numbers
            $steps = array_values(array_filter($steps, fn($s) => strlen($s) > 5));

            // Parse tags
            $tags = [];
            if (!empty($m['strTags'])) {
                $tags = array_map('trim', explode(',', $m['strTags']));
            }

            return [
                'mealdb_id' => $m['idMeal'],
                'title' => $m['strMeal'] ?? '',
                'category' => $m['strCategory'] ?? '',
                'area' => $m['strArea'] ?? '',
                'image' => $m['strMealThumb'] ?? '',
                'source' => $m['strSource'] ?? '',
                'youtube' => $m['strYoutube'] ?? '',
                'tags' => $tags,
                'ingredients' => $ingredients,
                'instructions' => $steps,
            ];
        }, $meals);
    }

    /**
     * Normalize filtered results (only have id, name, image).
     */
    private function normalizeFiltered(?array $meals): array
    {
        if (!$meals) return [];
        return array_map(fn($m) => [
            'mealdb_id' => $m['idMeal'],
            'title' => $m['strMeal'] ?? '',
            'image' => $m['strMealThumb'] ?? '',
        ], $meals);
    }

    /**
     * Extract numeric amount from a measure string like "1/2 cup".
     */
    private function extractAmount(string $measure): string
    {
        if (preg_match('/^([\d\/\.\s]+)/', $measure, $m)) {
            return trim($m[1]);
        }
        return '';
    }

    /**
     * Extract unit from a measure string like "1/2 cup".
     */
    private function extractUnit(string $measure): string
    {
        $cleaned = preg_replace('/^[\d\/\.\s]+/', '', $measure);
        return trim($cleaned ?? '');
    }

    /**
     * Fetch several endpoints in parallel. Returns decoded JSON per endpoint
     * (same order), or null for any that failed.
     */
    private function fetchMany(array $endpoints): array
    {
        $mh = curl_multi_init();
        $handles = [];
        $caBundle = function_exists('getCaBundlePath') ? getCaBundlePath() : null;
        foreach ($endpoints as $i => $endpoint) {
            $ch = curl_init(self::BASE_URL . $endpoint);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            if ($caBundle) curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
            curl_multi_add_handle($mh, $ch);
            $handles[$i] = $ch;
        }

        do {
            $status = curl_multi_exec($mh, $running);
            if ($running) curl_multi_select($mh, 1.0);
        } while ($running && $status === CURLM_OK);

        $out = [];
        foreach ($handles as $i => $ch) {
            $body = curl_multi_getcontent($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $decoded = ($code === 200 && $body) ? json_decode($body, true) : null;
            if (!is_array($decoded)) {
                LoggerService::channel('mealdb')->error('MealDB API request failed', ['http_code' => $code, 'endpoint' => $endpoints[$i]]);
                $decoded = null;
            }
            $out[$i] = $decoded;
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        curl_multi_close($mh);
        return $out;
    }

    /**
     * Fetch from TheMealDB API.
     */
    private function fetch(string $endpoint): array
    {
        $url = self::BASE_URL . $endpoint;
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $caBundle = function_exists('getCaBundlePath') ? getCaBundlePath() : null;
        if ($caBundle) {
            curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $response === false) {
            LoggerService::channel('mealdb')->error('MealDB API request failed', ['http_code' => $httpCode, 'endpoint' => $endpoint]);
            return [];
        }

        return json_decode($response, true) ?: [];
    }
}
