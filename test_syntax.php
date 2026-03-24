<?php
$files = ['lang/ar.json', 'lang/en.json', 'lang/sa.json'];
foreach ($files as $f) {
    $content = file_get_contents(__DIR__ . '/' . $f);
    json_decode($content);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON ERROR in $f: " . json_last_error_msg() . "\n";
    } else {
        echo "$f is valid.\n";
    }
}

// Also check blade files syntax
$dirs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/resources/views'));
$errors = 0;
foreach ($dirs as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        // Since we already did view:cache, the blade syntax itself is probably ok regarding `@` directives,
        // but let's check basic php syntax of the compiled files if they exist in storage/framework/views
    }
}

// Let's actually lint all compiled views
$compiledDirs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/storage/framework/views'));
foreach ($compiledDirs as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $output = shell_exec('php -l ' . escapeshellarg($path));
        if (strpos($output, 'No syntax errors detected') === false) {
            echo "Lint Error in compiled view $path: $output\n";
            $errors++;
        }
    }
}
if ($errors === 0) echo "All compiled views passed syntax check.\n";
