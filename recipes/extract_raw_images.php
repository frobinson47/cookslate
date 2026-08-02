<?php
// Extracts the raw AI-generated images from a batch output JSONL into a flat
// local folder, named by recipe id, no resizing (unlike process_batch_output.php).

$inputPath = $argv[1] ?? null;
$outDir = $argv[2] ?? null;

if (!$inputPath || !$outDir) {
    fwrite(STDERR, "Usage: php extract_raw_images.php <input.jsonl> <output_dir>\n");
    exit(1);
}

if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

$lines = file($inputPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$count = 0;
$failed = [];

foreach ($lines as $line) {
    $entry = json_decode($line, true);
    $customId = $entry['custom_id'] ?? null;

    $status = $entry['response']['status_code'] ?? null;
    $b64 = $entry['response']['body']['data'][0]['b64_json'] ?? null;

    if ($status !== 200 || !$b64) {
        $failed[] = "$customId (status=$status)";
        continue;
    }

    $imageData = base64_decode($b64, true);
    if ($imageData === false) {
        $failed[] = "$customId (base64 decode failed)";
        continue;
    }

    file_put_contents($outDir . DIRECTORY_SEPARATOR . $customId . '.jpg', $imageData);
    $count++;
}

echo "Extracted $count images to $outDir\n";
if ($failed) {
    echo "Failed: " . implode(', ', $failed) . "\n";
}
