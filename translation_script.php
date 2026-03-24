<?php

$viewsDir = __DIR__ . '/resources/views';

function scanViews($dir, &$results) {
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            scanViews($path, $results);
        } else if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                // Ignore comments
                if (strpos(trim($line), '//') === 0 || strpos(trim($line), '{{--') !== false) {
                    continue;
                }
                
                // Remove translated parts `__('text')` and `@lang('text')` and `@choice('text')`
                $cleanLine = preg_replace('/(__|\@lang|\@choice)\([\'"].*?[\'"]\)/u', '', $line);
                
                // If there's still Arabic text
                if (preg_match('/[أ-ي]/u', $cleanLine)) {
                    $results['hardcoded'][] = [
                        'file' => str_replace(__DIR__ . '/', '', $path),
                        'line' => $i + 1,
                        'text' => trim($line)
                    ];
                }
            }
        }
    }
}

$results = [
    'hardcoded' => [],
    'egyptian_in_sa_json' => []
];

scanViews($viewsDir, $results);

$sa = json_decode(file_get_contents(__DIR__ . '/lang/sa.json'), true);
$egyptianWords = ['عاوز', 'عايز', 'ازيك', 'علشان', 'عشان', ' دي ', ' ده ', 'كده', 'بقى', 'ايه', 'دلوقتي', 'امتى', 'فين', 'إزاي', 'بتاع', 'عربيه', 'كويس', 'جدا', 'شوية', 'حاجة', 'برضه', 'ياسطى', 'بص ', ' طب ', 'كدا', 'ليها', 'ليه', 'اللي', 'عامل'];

foreach ($sa as $key => $val) {
    if (!is_string($val)) continue;
    foreach ($egyptianWords as $word) {
        if (mb_strpos($val, trim($word)) !== false) {
            $results['egyptian_in_sa_json'][$key] = $val;
            break;
        }
    }
}

file_put_contents(__DIR__ . '/translation_report.json', json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo "Report generated successfully at translation_report.json.\n";
