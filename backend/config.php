<?php
// Load environment variables from .env file if it exists
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
                $value = $matches[2];
            }
            
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
    return true;
}

// Try to load .env file
$envLoaded = loadEnv(__DIR__ . '/../.env');

// API Configuration
// Priority: 1. Environment variable, 2. .env file, 3. config.php constant
$apiKey = $_ENV['OPENWEATHER_API_KEY'] ?? $_ENV['OPENWEATHERMAP_API_KEY'] ?? null;

if (!$apiKey) {
    // Fallback to config.php constant (for backward compatibility)
    if (defined('OPENWEATHER_API_KEY')) {
        $apiKey = OPENWEATHER_API_KEY;
    } elseif (defined('OPENWEATHERMAP_API_KEY')) {
        $apiKey = OPENWEATHERMAP_API_KEY;
    }
}

// Cache configuration
define('CACHE_DURATION', $_ENV['CACHE_DURATION'] ?? 3600); // Default: 1 hour
define('CACHE_DIR', __DIR__ . '/cache');

// Ensure cache directory exists
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0755, true);
}

// Export API key for use in world.php
if ($apiKey) {
    define('OPENWEATHER_API_KEY', $apiKey);
} else {
    // Log warning if no API key is found
    error_log('World.CSS: No OpenWeatherMap API key found. Please set OPENWEATHER_API_KEY in .env file or config.php');
}
?>
