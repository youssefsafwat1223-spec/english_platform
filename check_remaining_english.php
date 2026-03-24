<?php

$viewsDir = __DIR__ . '/resources/views';
$remaining = [];

function scanForEnglish($dir, &$results) {
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            scanForEnglish($path, $results);
        } else if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                $trimmed = trim($line);
                if (empty($trimmed) || str_starts_with($trimmed, '//') || str_contains($trimmed, '{{--') || str_contains($trimmed, 'php')) {
                    continue;
                }

                // Remove known translated text {{ __('...') }}
                $clean = preg_replace('/\{\{\s*__\([^\)]+\)\s*\}\}/', '', $trimmed);
                // Remove generic blade outputs {{ $var }}
                $clean = preg_replace('/\{\{.*?\}\}/', '', $clean);
                // Remove blade directives
                $clean = preg_replace('/\@\w+(\(.*?\))?/', '', $clean);
                // Remove HTML tags entirely
                $clean = preg_replace('/<[^>]*>/', ' ', $clean);
                
                // Now look for English words > 3 chars
                if (preg_match_all('/\b[A-Za-z]{3,}(?:\s+[a-zA-Z]{2,})*\b/', $clean, $matches)) {
                    foreach ($matches[0] as $match) {
                        $match = trim($match);
                        // Filter out common code leftovers
                        if (strlen($match) > 3 && !in_array(strtolower($match), ['true', 'false', 'null', 'class', 'href', 'span', 'div', 'button', 'function', 'return'])) {
                            // Filter out camelCase or snake_case
                            if (!preg_match('/\_|[a-z][A-Z]/', $match)) {
                                $results[$path][$i + 1] = $match;
                            }
                        }
                    }
                }
            }
        }
    }
}

scanForEnglish($viewsDir, $remaining);
$output = [];
foreach ($remaining as $file => $lines) {
    $relativePath = str_replace(__DIR__ . '/', '', $file);
    foreach ($lines as $line => $text) {
        $output[] = "$relativePath:$line => $text";
    }
}

file_put_contents(__DIR__ . '/remaining_english.txt', implode("\n", $output));
echo "Found " . count($output) . " matches. Saved to remaining_english.txt\n";
