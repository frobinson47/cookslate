<?php

/**
 * Parses Cooklang (.cook) files into Cookslate recipe format.
 *
 * Cooklang spec: https://cooklang.org/docs/spec
 */
class CooklangImporter
{
    /**
     * Parse a Cooklang string into a Cookslate-compatible recipe array.
     *
     * @param string $content Raw .cook file content
     * @return array Recipe data with 'title', 'description', 'ingredients', 'instructions', 'tags', etc.
     */
    public function parse(string $content): array
    {
        $recipe = [
            'title' => '',
            'description' => '',
            'prep_time' => null,
            'cook_time' => null,
            'servings' => null,
            'source_url' => '',
            'ingredients' => [],
            'instructions' => [],
            'tags' => [],
        ];

        // Split metadata and body
        $parts = $this->splitFrontMatter($content);
        $metadata = $parts['metadata'];
        $body = $parts['body'];

        // Apply metadata
        if (isset($metadata['title'])) {
            $recipe['title'] = $metadata['title'];
        }
        if (isset($metadata['source'])) {
            $recipe['source_url'] = $metadata['source'];
        }
        if (isset($metadata['servings'])) {
            $recipe['servings'] = (int) $metadata['servings'];
        }
        if (isset($metadata['prep_time'])) {
            $recipe['prep_time'] = $this->parseMinutes($metadata['prep_time']);
        }
        if (isset($metadata['cook_time'])) {
            $recipe['cook_time'] = $this->parseMinutes($metadata['cook_time']);
        }
        if (isset($metadata['tags']) && is_array($metadata['tags'])) {
            $recipe['tags'] = $metadata['tags'];
        }

        // Parse body
        $lines = explode("\n", $body);
        $ingredients = [];
        $ingredientNames = []; // Track unique ingredients
        $steps = [];
        $currentStep = '';
        $description = '';

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Skip comments
            if (str_starts_with($trimmed, '--')) continue;

            // Notes become description
            if (str_starts_with($trimmed, '> ')) {
                $description .= substr($trimmed, 2) . "\n";
                continue;
            }

            // Section headers
            if (preg_match('/^=+(.+?)=+$/', $trimmed)) {
                // Could be used for step grouping, but we'll just treat as a step separator
                if (!empty($currentStep)) {
                    $steps[] = $currentStep;
                    $currentStep = '';
                }
                continue;
            }

            // Blank line = step separator
            if (empty($trimmed)) {
                if (!empty($currentStep)) {
                    $steps[] = $currentStep;
                    $currentStep = '';
                }
                continue;
            }

            // Parse ingredients, cookware, and timers from the line
            $parsed = $this->parseLine($trimmed);
            foreach ($parsed['ingredients'] as $ing) {
                $key = strtolower($ing['name']);
                if (!isset($ingredientNames[$key])) {
                    $ingredients[] = $ing;
                    $ingredientNames[$key] = true;
                }
            }

            // Build clean instruction text (remove Cooklang syntax)
            $currentStep .= ($currentStep ? ' ' : '') . $parsed['text'];
        }

        // Don't forget the last step
        if (!empty($currentStep)) {
            $steps[] = $currentStep;
        }

        $recipe['description'] = trim($description);
        $recipe['ingredients'] = $ingredients;
        $recipe['instructions'] = $steps;

        return $recipe;
    }

    /**
     * Split YAML front matter from body content.
     */
    private function splitFrontMatter(string $content): array
    {
        $content = ltrim($content);
        if (str_starts_with($content, '---')) {
            $endPos = strpos($content, '---', 3);
            if ($endPos !== false) {
                $yamlStr = substr($content, 3, $endPos - 3);
                $body = substr($content, $endPos + 3);
                return [
                    'metadata' => $this->parseSimpleYaml($yamlStr),
                    'body' => $body,
                ];
            }
        }
        return ['metadata' => [], 'body' => $content];
    }

    /**
     * Simple YAML parser for metadata (handles basic key: value and lists).
     */
    private function parseSimpleYaml(string $yaml): array
    {
        $result = [];
        $currentKey = null;
        $currentList = null;

        foreach (explode("\n", $yaml) as $line) {
            $trimmed = trim($line);
            if (empty($trimmed)) continue;

            // List item
            if (preg_match('/^\s*-\s+(.+)$/', $line, $m) && $currentKey) {
                if ($currentList === null) $currentList = [];
                $currentList[] = trim($m[1]);
                continue;
            }

            // Save previous list
            if ($currentKey && $currentList !== null) {
                $result[$currentKey] = $currentList;
                $currentList = null;
            }

            // Key: value
            if (preg_match('/^([a-zA-Z_]+)\s*:\s*(.*)$/', $trimmed, $m)) {
                $currentKey = $m[1];
                $value = trim($m[2], ' "\'');
                if (empty($value)) {
                    // Might be followed by a list
                    $currentList = null; // Will be set to [] on first list item
                } else {
                    $result[$currentKey] = $value;
                    $currentKey = $m[1]; // Keep for potential list
                    $currentList = null;
                }
            }
        }

        // Save final list
        if ($currentKey && $currentList !== null) {
            $result[$currentKey] = $currentList;
        }

        return $result;
    }

    /**
     * Parse a single line, extracting ingredients and producing clean text.
     */
    private function parseLine(string $line): array
    {
        $ingredients = [];
        $text = $line;

        // Extract @ingredient{quantity%unit} patterns
        // Handles: @salt, @ground black pepper{}, @potato{2}, @bacon{1%kg}
        $text = preg_replace_callback(
            '/@([^@#~{}\n]+?)\{([^}]*)\}/',
            function ($m) use (&$ingredients) {
                $name = trim($m[1]);
                $spec = $m[2];
                $amount = '';
                $unit = '';

                if (str_contains($spec, '%')) {
                    [$amount, $unit] = explode('%', $spec, 2);
                } elseif (!empty($spec)) {
                    $amount = $spec;
                }

                $ingredients[] = [
                    'name' => $name,
                    'amount' => trim($amount),
                    'unit' => trim($unit),
                ];

                return $name;
            },
            $text
        );

        // Handle single-word @ingredient (no braces)
        $text = preg_replace_callback(
            '/@(\w+)(?!\{)/',
            function ($m) use (&$ingredients) {
                $name = $m[1];
                $ingredients[] = [
                    'name' => $name,
                    'amount' => '',
                    'unit' => '',
                ];
                return $name;
            },
            $text
        );

        // Remove cookware syntax: #pot, #potato masher{}
        $text = preg_replace('/#([^#@~{}\n]+?)\{[^}]*\}/', '$1', $text);
        $text = preg_replace('/#(\w+)/', '$1', $text);

        // Remove timer syntax but keep the time info: ~{25%minutes} → "25 minutes"
        $text = preg_replace_callback(
            '/~(?:[^{]*)\{([^}]*)\}/',
            function ($m) {
                $spec = $m[1];
                if (str_contains($spec, '%')) {
                    [$amount, $unit] = explode('%', $spec, 2);
                    return trim($amount) . ' ' . trim($unit);
                }
                return $spec;
            },
            $text
        );

        // Clean up extra whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));

        return ['text' => $text, 'ingredients' => $ingredients];
    }

    /**
     * Parse a time string like "25 minutes" or "1 hour" into minutes.
     */
    private function parseMinutes(string $time): ?int
    {
        if (preg_match('/(\d+)\s*min/', $time, $m)) {
            return (int) $m[1];
        }
        if (preg_match('/(\d+)\s*hour/', $time, $m)) {
            return (int) $m[1] * 60;
        }
        if (is_numeric(trim($time))) {
            return (int) trim($time);
        }
        return null;
    }
}
