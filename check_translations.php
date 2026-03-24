<?php

$en = json_decode(file_get_contents(__DIR__ . '/lang/en.json'), true);
$sa = json_decode(file_get_contents(__DIR__ . '/lang/sa.json'), true);

$missingKeys = [];
foreach ($en as $key => $val) {
    if (!isset($sa[$key])) {
        $missingKeys[] = $key;
    }
}

$egyptianWords = ['عاوز', 'عايز', 'ازيك', 'علشان', 'عشان', ' دي ', ' ده ', 'كده', 'بقى', 'ايه', 'دلوقتي', 'امتى', 'فين', 'إزاي', 'بتاع', 'عربيه', 'كويس', 'جدا', 'شوية', 'حاجة', 'برضه', 'ياسطى', ' بص ', ' طب ', 'كدا', 'ليها', 'ليه', 'اللي', 'عامل'];
$egyptianFound = [];

foreach ($sa as $key => $val) {
    foreach ($egyptianWords as $word) {
        if (strpos($val, trim($word)) !== false) {
            $egyptianFound[$key] = $val;
            break;
        }
    }
}

echo "=== Missing Keys in sa.json ===\n";
echo count($missingKeys) . " keys missing.\n";
if (count($missingKeys) > 0) {
    print_r(array_slice($missingKeys, 0, 10)); // print first 10
}

echo "\n=== Potential Egyptian Dialect in sa.json ===\n";
echo count($egyptianFound) . " potential matches.\n";
foreach (array_slice($egyptianFound, 0, 10) as $k => $v) {
    echo "[$k] => $v\n";
}
