<?php
// World.CSS PHP Configuration
// This file contains all configuration options for the PHP backend

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

// Configuration array
$config = [
    // Server Configuration
    'server' => [
        'port' => $_ENV['PORT'] ?? 80,
        'host' => $_ENV['HOST'] ?? 'localhost',
        'debug' => $_ENV['DEBUG'] ?? false
    ],

    // API Configuration
    'api' => [
        // OpenWeatherMap API
        'openweather' => [
            'key' => $_ENV['OPENWEATHER_API_KEY'] ?? $_ENV['OPENWEATHERMAP_API_KEY'] ?? null,
            'base_url' => 'https://api.openweathermap.org/data/2.5',
            'units' => 'metric', // metric, imperial, kelvin
            'timeout' => 10
        ],

        // IP Geolocation API
        'geolocation' => [
            'provider' => 'ip-api.com', // Alternative: 'ipapi.co', 'ipinfo.io'
            'base_url' => 'http://ip-api.com/json',
            'timeout' => 5
        ],

        // Solar calculation settings
        'solar' => [
            'cache_duration' => 300, // 5 minutes for solar calculations
            'timezone_estimation' => true, // Use longitude-based timezone estimation
            'precision' => 6 // Decimal places for calculations
        ],

        // Weather cache settings
        'weather' => [
            'cache_duration' => 1800, // 30 minutes for weather data
            'include_forecast' => false, // Set to true to include 5-day forecast
            'include_alerts' => false // Set to true to include weather alerts
        ],

        // Location cache settings
        'location' => [
            'cache_duration' => 3600, // 1 hour for location data
            'fallback_location' => [
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'city' => 'New York',
                'country' => 'US',
                'timezone' => 'America/New_York'
            ]
        ]
    ],

    // Cache Configuration
    'cache' => [
        'directory' => __DIR__ . '/cache',
        'cleanup_interval' => 3600, // 1 hour in seconds
        'max_age' => 3600, // 1 hour default cache age
        'compression' => false, // Set to true to compress cache files
        'max_size' => 100 * 1024 * 1024 // 100MB max cache size
    ],

    // Logging Configuration
    'logging' => [
        'level' => $_ENV['LOG_LEVEL'] ?? 'INFO', // DEBUG, INFO, WARNING, ERROR
        'file' => $_ENV['LOG_FILE'] ?? null, // Set to file path for file logging
        'console' => true,
        'max_bytes' => 10 * 1024 * 1024, // 10MB max log file size
        'backup_count' => 5
    ],

    // Security Configuration
    'security' => [
        'rate_limit' => [
            'enabled' => true,
            'window' => 15 * 60, // 15 minutes in seconds
            'max_requests' => 100 // limit each IP to 100 requests per window
        ],
        'cors' => [
            'enabled' => true,
            'origins' => ['*'],
            'methods' => ['GET', 'POST', 'OPTIONS']
        ],
        'headers' => [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block'
        ]
    ],

    // Development Configuration
    'development' => [
        'debug' => true,
        'auto_reload' => true,
        'detailed_errors' => true
    ],

    // Production Configuration
    'production' => [
        'debug' => false,
        'compression' => true,
        'static_cache' => 86400, // 24 hours for static files
        'trust_proxy' => true
    ],

    // Custom Routes (optional)
    'custom_routes' => [
        // Add your custom API endpoints here
        // '/api/custom' => function() { return ['message' => 'Custom endpoint']; }
    ],

    // Environment-specific overrides
    'environments' => [
        'development' => [
            'logging' => [
                'level' => 'DEBUG'
            ],
            'cache' => [
                'max_age' => 300 // 5 minutes for development
            ],
            'security' => [
                'rate_limit' => [
                    'enabled' => false
                ]
            ]
        ],
        'production' => [
            'logging' => [
                'level' => 'WARNING'
            ],
            'security' => [
                'rate_limit' => [
                    'max_requests' => 1000 // Higher limit for production
                ]
            ]
        ],
        'test' => [
            'cache' => [
                'directory' => __DIR__ . '/cache-test'
            ],
            'logging' => [
                'level' => 'ERROR'
            ]
        ]
    ]
];

// Ensure cache directory exists
if (!is_dir($config['cache']['directory'])) {
    mkdir($config['cache']['directory'], 0755, true);
}

// Return configuration array
return $config;
?>
