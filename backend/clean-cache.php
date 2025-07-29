<?php
// Cache cleanup script for World.CSS
// Run this manually if you want to clear all cache files

$cache_dir = __DIR__ . '/cache';
$files = glob("$cache_dir/*.json");

if (empty($files)) {
    echo "Cache directory is already clean.\n";
    exit;
}

$count = 0;
foreach ($files as $file) {
    if (unlink($file)) {
        $count++;
    }
}

echo "Cleaned up $count cache files.\n";
?> 