<?php
$lines = file(__DIR__ . '/remaining_english.txt');
$unique = [];
$ignoreList = [
    'Math', 'String', 'Date', 'Chart', 'Intl', 'Promise', 'Error', 'FormData', 'URLSearchParams', 'Map', 'Set', 'JSON',
    'POST', 'GET', 'PUT', 'DELETE', 'Accept', 'Content', 'Type', 'Authorization', 'Bearer',
    'HTML', 'CSS', 'JS', 'PHP', 'API', 'URL', 'URI', 'ID', 'IDs', 'IP', 'MAC', 'OS',
    'Lime Team', 'Red Team', 'Blue Team', // If these are defaults we can translate but let's see
];

foreach ($lines as $line) {
    $parts = explode('=> ', $line);
    if (!isset($parts[1])) continue;
    
    $phrase = trim($parts[1]);
    
    // Skip single lowercase words (mostly CSS, html attrs, JS vars)
    if (preg_match('/^[a-z]+$/', $phrase)) continue;
    
    // Skip pure numbers or symbols
    if (preg_match('/^[^a-zA-Z]*$/', $phrase)) continue;
    
    // Skip ignore list
    if (in_array($phrase, $ignoreList)) continue;
    
    // Skip single words with one capital (mostly classes or JS objects) unless it's a specific UI word
    // Actually, UI words like "Cancel", "Update", "Next" are single capitalized words.
    // Let's keep them and filter manually later.
    
    // Skip phrases that look like CSS classes (e.g. 'text gray', 'bg blue', 'hover bg')
    if (preg_match('/^(text|bg|border|hover|focus|ring|shadow|from|to|via|flex|grid|hidden|block|rounded|w|h|p|m|gap|items|justify|space)\s[a-z0-9]+$/', $phrase)) continue;
    if (preg_match('/^(text|bg|border|hover|focus|stroke)\-[a-z0-9\-]+$/', $phrase)) continue;

    // Skip string literals that look like code
    if (str_contains($phrase, '=>') || str_contains($phrase, '==') || str_contains($phrase, '&&')) continue;

    $unique[$phrase] = true;
}

$u = array_keys($unique);
sort($u);
$output = "Unique phrases: " . count($u) . PHP_EOL;
foreach($u as $phrase) {
    if (strlen($phrase) > 2) {
        $output .= $phrase . PHP_EOL;
    }
}

file_put_contents(__DIR__ . '/filtered_english.txt', $output);
echo "Filtered down to " . count($u) . " potential English phrases. Saved to filtered_english.txt.\n";
