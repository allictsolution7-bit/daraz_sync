<?php
function search_dir($dir, $pattern) {
    if (!is_dir($dir)) return;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $content = @file_get_contents($file->getPathname());
            if ($content && stripos($content, $pattern) !== false) {
                echo "Found in: " . $file->getPathname() . "\n";
            }
        }
    }
}

echo "Searching storage/...\n";
search_dir(__DIR__.'/storage', 'activation');
search_dir(__DIR__.'/storage', 'Not authorized');
echo "Search completed.\n";
