<?php
// World.CSS Backend API
// Handles: locate, sun, weather

// Prevent any output before JSON headers
ob_start();

// Temporarily enable error display for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set JSON headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Error handler to prevent HTML output
function json_error_handler($errno, $errstr, $errfile, $errline) {
    // Log error but don't display it
    error_log("World.CSS Error: $errstr in $errfile on line $errline");
    return true; // Don't execute PHP internal error handler
}
set_error_handler('json_error_handler');

try {
    // Load configuration
    $config = require __DIR__ . '/config.php';
    
    if (!$config) {
        throw new Exception('Failed to load configuration');
    }
} catch (Exception $e) {
    // Clear any output buffer
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Configuration error: ' . $e->getMessage()
    ]);
    exit;
}

// Helper functions
function get_api_key() {
    global $config;
    return $config['api']['openweather']['key'] ?? null;
}

function get_cache_dir() {
    global $config;
    return $config['cache']['directory'] ?? __DIR__ . '/cache';
}

function get_cache_duration() {
    global $config;
    return $config['cache']['max_age'] ?? 3600;
}

function cache_get($key) {
    $cache_dir = get_cache_dir();
    $file = "$cache_dir/$key.json";
    if (file_exists($file) && (time() - filemtime($file) < get_cache_duration())) {
        $content = file_get_contents($file);
        if ($content !== false) {
            return json_decode($content, true);
        }
    }
    return null;
}

function cache_set($key, $data) {
    $cache_dir = get_cache_dir();
    
    // Ensure cache directory exists
    if (!is_dir($cache_dir)) {
        if (!mkdir($cache_dir, 0755, true)) {
            error_log("World.CSS: Failed to create cache directory: $cache_dir");
            return false;
        }
    }
    
    $file = "$cache_dir/$key.json";
    $result = file_put_contents($file, json_encode($data));
    
    if ($result === false) {
        error_log("World.CSS: Failed to write cache file: $file");
        return false;
    }
    
    // Clean up old cache files
    try {
        $files = glob("$cache_dir/*.json");
        $now = time();
        foreach ($files as $file) {
            if ($now - filemtime($file) > get_cache_duration()) {
                unlink($file);
            }
        }
    } catch (Exception $e) {
        error_log("World.CSS: Cache cleanup error: " . $e->getMessage());
    }
    
    return true;
}

function respond($data) {
    // Clear any output buffer
    ob_end_clean();
    echo json_encode($data);
    exit;
}

function respond_error($message, $code = 400) {
    http_response_code($code);
    respond(['success' => false, 'error' => $message]);
}

// Get action parameter
$action = $_GET['action'] ?? '';

// Get API key for use in endpoints
$apiKey = get_api_key();

if (!$apiKey) {
    // Log the issue but continue with fallback data
    error_log("World.CSS: OpenWeatherMap API key not configured. Using fallback data.");
}

switch ($action) {
    case 'locate':
        // Locate user by IP using ip-api.com
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        $cache_key = 'locate_' . md5($ip);
        
        if ($data = cache_get($cache_key)) {
            respond(['success' => true, 'data' => $data]);
        }
        
        $resp = file_get_contents("http://ip-api.com/json/$ip");
        $data = json_decode($resp, true);
        
        if ($data && isset($data['status']) && $data['status'] === 'success') {
            $location_data = [
                'latitude' => $data['lat'],
                'longitude' => $data['lon'],
                'city' => $data['city'],
                'country' => $data['country'],
                'timezone' => $data['timezone']
            ];
            cache_set($cache_key, $location_data);
            respond(['success' => true, 'data' => $location_data]);
        } else {
            // Fallback to default location
            $fallback = $config['api']['location']['fallback_location'];
            respond(['success' => true, 'data' => $fallback]);
        }
        break;
        
    case 'weather':
        // Get weather for lat/lon
        $lat = $_GET['lat'] ?? null;
        $lon = $_GET['lon'] ?? null;
        
        if (!$lat || !$lon) {
            respond_error('Missing lat/lon parameters');
        }
        
        $cache_key = 'weather_' . md5("$lat,$lon");
        if ($data = cache_get($cache_key)) {
            respond(['success' => true, 'data' => $data]);
        }
        
        if ($apiKey) {
            // Try real API
            $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric";
            $resp = file_get_contents($url);
            $data = json_decode($resp, true);
            
            if ($data && isset($data['main'])) {
                $weather_data = [
                    'temp' => $data['main']['temp'],
                    'humidity' => $data['main']['humidity'],
                    'pressure' => $data['main']['pressure'],
                    'clouds' => $data['clouds']['all'],
                    'description' => $data['weather'][0]['description'],
                    'icon' => $data['weather'][0]['icon'],
                    'wind_speed' => $data['wind']['speed'],
                    'wind_deg' => $data['wind']['deg']
                ];
                cache_set($cache_key, $weather_data);
                respond(['success' => true, 'data' => $weather_data]);
            } else {
                respond_error('Failed to get weather data', 500);
            }
        } else {
            // Provide fallback weather data
            $weather_data = [
                'temp' => 22,
                'humidity' => 65,
                'pressure' => 1013,
                'clouds' => 30,
                'description' => 'partly cloudy',
                'icon' => '02d',
                'wind_speed' => 5.2,
                'wind_deg' => 180
            ];
            respond(['success' => true, 'data' => $weather_data]);
        }
        break;
        
    case 'sun':
        // Calculate solar elevation, azimuth, sunrise/sunset
        $lat = isset($_GET['lat']) && is_numeric($_GET['lat']) ? (float)$_GET['lat'] : null;
        $lon = isset($_GET['lon']) && is_numeric($_GET['lon']) ? (float)$_GET['lon'] : null;
        $time = isset($_GET['time']) && is_numeric($_GET['time']) ? (int)$_GET['time'] : time();
        
        if ($lat === null || $lon === null) {
            respond_error('Missing or invalid lat/lon parameters');
        }
        
        $cache_key = 'sun_' . md5("$lat,$lon," . floor($time / 300)); // Cache for 5 minutes
        if ($data = cache_get($cache_key)) {
            respond(['success' => true, 'data' => $data]);
        }
        
        $data = sun_data($lat, $lon, $time);
        cache_set($cache_key, $data);
        respond(['success' => true, 'data' => $data]);
        break;
        
    default:
        respond_error('Invalid action');
}

// Solar calculations
function sun_data($lat, $lon, $timestamp) {
    // Use date_sun_info for sunrise/sunset
    $info = date_sun_info($timestamp, $lat, $lon);
    
    // Calculate timezone offset for the location
    $timezone_offset = get_timezone_offset($lat, $lon);
    $local_timestamp = $timestamp + $timezone_offset;
    
    // Solar elevation/azimuth (approximate)
    $dt = new DateTime('@' . $local_timestamp);
    $dt->setTimezone(new DateTimeZone('UTC'));
    $d = $dt->format('z') + 1;
    $h = $dt->format('G') + $dt->format('i')/60;
    $decl = 23.44 * cos(deg2rad(360/365 * ($d - 172)));
    $ha = 15 * ($h - 12);
    $elev = rad2deg(asin(sin(deg2rad($lat))*sin(deg2rad($decl)) + cos(deg2rad($lat))*cos(deg2rad($decl))*cos(deg2rad($ha))));
    
    // Determine phase based on elevation
    $phase = 'day';
    if ($elev < -6) $phase = 'night';
    elseif ($elev < -3) $phase = 'twilight';
    elseif ($elev < 5) $phase = 'sunrise';
    elseif ($elev < 20) $phase = 'day';
    elseif ($elev < 45) $phase = 'noon';
    elseif ($elev < 70) $phase = 'sunset';
    elseif ($elev < 100) $phase = 'evening';
    
    return [
        'elevation' => $elev,
        'azimuth' => $ha,
        'phase' => $phase,
        'timestamp' => $timestamp * 1000
    ];
}

function get_timezone_offset($lat, $lon) {
    // Simple timezone estimation based on longitude
    // In a real implementation, you'd use a timezone API
    $offset_hours = round($lon / 15);
    return $offset_hours * 60; // Convert to minutes
}
?>
