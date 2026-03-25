<?php
// Check if JSONs are valid still
$files = ['lang/ar.json', 'lang/en.json', 'lang/sa.json'];
foreach ($files as $f) {
    json_decode(file_get_contents(__DIR__ . '/' . $f));
    if (json_last_error() !== JSON_ERROR_NONE) echo "JSON ERROR in $f: " . json_last_error_msg() . "\n";
}

// Read last 100 lines of Laravel log
$log = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($log)) {
    $lines = file($log);
    $l = count($lines);
    $start = max(0, $l - 50);
    echo "--- LAST 50 LOG LINES ---\n";
    for($i=$start;$i<$l;$i++){
        echo $lines[$i];
    }
} else {
    echo "No laravel.log found.\n";
}
