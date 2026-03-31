<?php
$path = 'd:/english-platform/english-platform/resources/views/layouts/navigation.blade.php';
$lines = file($path);
$lines = array_slice($lines, 0, 679);
file_put_contents($path, implode('', $lines));
echo "Truncated.\n";
