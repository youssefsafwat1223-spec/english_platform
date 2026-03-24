<?php
$compiledDirs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/storage/framework/views'));
$errors = [];
foreach ($compiledDirs as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $output = shell_exec('php -l ' . escapeshellarg($path) . ' 2>&1');
        if (strpos($output, 'No syntax errors detected') === false) {
            $content = file_get_contents($path);
            preg_match('/PATH (.*?) ENDPATH/', $content, $matches);
            $origin = isset($matches[1]) ? $matches[1] : 'Unknown';
            $errors[] = [
                'compiled' => $path,
                'origin' => $origin,
                'error' => trim($output)
            ];
        }
    }
}
file_put_contents(__DIR__ . '/lint_errors.json', json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Found " . count($errors) . " errors.\n";
