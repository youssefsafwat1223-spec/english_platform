<?php

$saJsonPath = __DIR__ . '/lang/sa.json';
$saJson = json_decode(file_get_contents($saJsonPath), true);

$untranslated = [];
foreach ($saJson as $key => $val) {
    if (is_string($val) && preg_match('/[a-zA-Z]/', $val) && !preg_match('/[أ-ي]/u', $val)) {
        if (!preg_match('/^https?:\/\//i', $val) && !str_contains($val, '->')) { // Ignore URLs and PHP code
            $untranslated[$key] = $val;
        }
    }
}

$viewsDir = __DIR__ . '/resources/views';

function scanViewsForEnglish($dir, &$results) {
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            scanViewsForEnglish($path, $results);
        } else if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                if (strpos(trim($line), '//') === 0 || strpos(trim($line), '{{--') !== false) {
                    continue;
                }
                
                // Remove blade variables
                $cleanLine = preg_replace('/\{\{.*?\}\}/', '', $line);
                $cleanLine = preg_replace('/\{\!\!.*?\!\!\}/', '', $cleanLine);
                $cleanLine = preg_replace('/\@\w+.*/', '', $cleanLine);
                
                // Only look at text between HTML tags
                preg_match_all('/>([^<]+)</', $cleanLine, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $text) {
                        $text = trim($text);
                        // Check if it's English text with at least 3 letters
                        if (preg_match('/[a-zA-Z]{3,}/', $text) && !preg_match('/[أ-ي]/u', $text)) {
                            // ignore common non-text things
                            if (!str_contains($text, '->') && !str_contains($text, '()')) {
                                $results[] = [
                                    'file' => str_replace(__DIR__ . '/', '', $path),
                                    'line' => $i + 1,
                                    'text' => $text
                                ];
                            }
                        }
                    }
                }
            }
        }
    }
}

$englishHardcoded = [];
scanViewsForEnglish($viewsDir, $englishHardcoded);

$report = [
    'sa_json_untranslated' => $untranslated,
    'blade_english_hardcoded' => $englishHardcoded
];

file_put_contents(__DIR__ . '/english_report.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Saved to english_report.json\n";
