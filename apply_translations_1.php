<?php

$saJsonPath = __DIR__ . '/lang/sa.json';
$arJsonPath = __DIR__ . '/lang/ar.json';
$enJsonPath = __DIR__ . '/lang/en.json';

$saJson = json_decode(file_get_contents($saJsonPath), true);
$arJson = json_decode(file_get_contents($arJsonPath), true);
$enJson = json_decode(file_get_contents($enJsonPath), true);

$replacements = [
    // Egyptian -> Saudi
    'عشان تكسب' => 'عشان تكسب', // acceptable
    'اللي لازم' => 'المفروض',
    'اللي أنت مشترك فيها' => 'اللي أنت مشترك فيها',
    'بتاعتك' => 'حقتك',
    'لسه موجودة' => 'للحين موجودة',
    'بتكمل عملية الدفع' => 'تكتمل عملية الدفع',
    'بيتم تفعيل' => 'يتم تفعيل',
    'تقدر تبدأ مذاكرة' => 'وتقدر تبدأ تذاكر',
    'دي ' => 'هذي ',
    'ده ' => 'هذا ',
    'ازيك' => 'كيفك',
    'عربيه' => 'سيارة',
    'إزاي' => 'كيف',
    'دلوقتي' => 'الحين'
];

foreach ($saJson as $key => $value) {
    if (is_string($value)) {
        foreach ($replacements as $egyptian => $saudi) {
            if (mb_strpos($value, $egyptian) !== false) {
                $saJson[$key] = str_replace($egyptian, $saudi, $value);
            }
        }
    }
}

// Ensure all exist in en/ar based on sa
foreach ($saJson as $key => $val) {
    if (!isset($enJson[$key])) {
        $enJson[$key] = $key;
    }
    if (!isset($arJson[$key])) {
        $arJson[$key] = $val; // fallback ar to saudi
    }
}

file_put_contents($saJsonPath, json_encode($saJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arJsonPath, json_encode($arJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($enJsonPath, json_encode($enJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Language JSON files updated with Saudi dialect replacements.\n";
