<?php
// Deduplicate ar.json: read with duplicate handling, keep last value, write clean JSON
$file = __DIR__ . '/lang/ar.json';
$content = file_get_contents($file);

// PHP's json_decode naturally handles duplicates by keeping the last value
$data = json_decode($content, true);

if ($data === null) {
    echo "ERROR: " . json_last_error_msg() . "\n";
    exit(1);
}

$count_before = substr_count($content, '": "');

// Sort keys for consistency
ksort($data, SORT_STRING | SORT_FLAG_CASE);

$clean = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

file_put_contents($file, $clean . "\n");

$count_after = count($data);
echo "Done! Before: ~{$count_before} entries, After: {$count_after} unique keys\n";
echo "Duplicates removed: " . ($count_before - $count_after) . "\n";
