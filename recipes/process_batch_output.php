<?php
require_once __DIR__ . '/../api/config/constants.php';

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

$inputPath = $argv[1] ?? (__DIR__ . '/batch_output.jsonl');
$idsOutPath = $argv[2] ?? (__DIR__ . '/processed_ids.json');
$lines = file($inputPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$ok = [];
$failed = [];

foreach ($lines as $line) {
    $entry = json_decode($line, true);
    $customId = $entry['custom_id'] ?? null;
    $recipeId = (int) $customId;

    if ($recipeId <= 0) {
        $failed[] = "$customId (bad custom_id)";
        continue;
    }

    $status = $entry['response']['status_code'] ?? null;
    $b64 = $entry['response']['body']['data'][0]['b64_json'] ?? null;

    if ($status !== 200 || !$b64) {
        $failed[] = "$recipeId (status=$status, no image data)";
        continue;
    }

    $imageData = base64_decode($b64, true);
    if ($imageData === false) {
        $failed[] = "$recipeId (base64 decode failed)";
        continue;
    }

    $source = @imagecreatefromstring($imageData);
    if ($source === false) {
        $failed[] = "$recipeId (imagecreatefromstring failed)";
        continue;
    }

    $origWidth = imagesx($source);
    $origHeight = imagesy($source);

    $relativeDir = 'recipes' . DIRECTORY_SEPARATOR . $recipeId;
    $outputDir = UPLOAD_DIR . $relativeDir;
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    resizeAndSave($source, $origWidth, $origHeight, IMAGE_MAX_WIDTH, IMAGE_QUALITY, $outputDir . DIRECTORY_SEPARATOR . 'full.webp');
    resizeAndSave($source, $origWidth, $origHeight, THUMB_MAX_WIDTH, THUMB_QUALITY, $outputDir . DIRECTORY_SEPARATOR . 'thumb.webp');
    imagedestroy($source);

    $ok[] = $recipeId;
}

echo "Processed OK: " . count($ok) . "\n";
echo "Failed: " . count($failed) . "\n";
if ($failed) {
    echo "Failures:\n" . implode("\n", $failed) . "\n";
}
file_put_contents($idsOutPath, json_encode($ok));
