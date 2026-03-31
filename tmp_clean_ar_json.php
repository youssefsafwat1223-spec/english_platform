<?php
$path = 'd:\\english-platform\\english-platform\\lang\\ar.json';
$content = file_get_contents($path);
$data = json_decode($content, true);
$cleanJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents($path, $cleanJson);
echo "Cleaned ar.json duplicates.\n";
