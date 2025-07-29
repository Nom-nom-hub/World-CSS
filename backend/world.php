<?php
// World.CSS Backend API
// Handles: locate, sun, weather

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$action = $_GET['action'] ?? '';
$config = require __DIR__ . '/config.php';
$cache_dir = __DIR__ . '/cache';
if (!is_dir($cache_dir)) mkdir($cache_dir, 0777, true);

function cache_get($key) {
    global $cache_dir;
    $file = "$cache_dir/$key.json";
    if (file_exists($file) && (time() - filemtime($file) < 300)) {
        return json_decode(file_get_contents($file), true);
    }
    return null;
}

function cache_set($key, $data) {
    global $cache_dir;
    file_put_contents("$cache_dir/$key.json", json_encode($data));
    
    // Clean up old cache files (older than 1 hour)
    $files = glob("$cache_dir/*.json");
    $now = time();
    foreach ($files as $file) {
        if ($now - filemtime($file) > 3600) {
            unlink($file);
        }
    }
}

function respond($data) {
    echo json_encode($data);
    exit;
}

switch ($action) {
    case 'locate':
        // Locate user by IP using ip-api.com
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        $cache_key = 'locate_' . md5($ip);
        if ($data = cache_get($cache_key)) respond($data);
        $resp = file_get_contents("http://ip-api.com/json/$ip");
        $data = json_decode($resp, true);
        cache_set($cache_key, $data);
        respond($data);
        break;
    case 'weather':
        // Get weather for lat/lon
        $lat = $_GET['lat'] ?? null;
        $lon = $_GET['lon'] ?? null;
        if (!$lat || !$lon) respond(['error' => 'Missing lat/lon']);
        $cache_key = 'weather_' . md5("$lat,$lon");
        if ($data = cache_get($cache_key)) respond($data);
        $apiKey = $config['OPENWEATHERMAP_API_KEY'];
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric";
        $resp = file_get_contents($url);
        $data = json_decode($resp, true);
        cache_set($cache_key, $data);
        respond($data);
        break;
    case 'sun':
        // Calculate solar elevation, azimuth, sunrise/sunset
        $lat = isset($_GET['lat']) && is_numeric($_GET['lat']) ? (float)$_GET['lat'] : null;
        $lon = isset($_GET['lon']) && is_numeric($_GET['lon']) ? (float)$_GET['lon'] : null;
        $time = isset($_GET['time']) && is_numeric($_GET['time']) ? (int)$_GET['time'] : time();
        if ($lat === null || $lon === null) respond(['error' => 'Missing or invalid lat/lon']);
        $cache_key = 'sun_' . md5("$lat,$lon,$time");
        if ($data = cache_get($cache_key)) respond($data);
        $data = sun_data($lat, $lon, $time);
        cache_set($cache_key, $data);
        respond($data);
        break;
    default:
        respond(['error' => 'Invalid action']);
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
    $az = rad2deg(atan2(-sin(deg2rad($ha)), tan(deg2rad($decl))*cos(deg2rad($lat)) - sin(deg2rad($lat))*cos(deg2rad($ha))));
    
    return [
        'sunrise' => $info['sunrise'],
        'sunset' => $info['sunset'],
        'solar_noon' => $info['transit'],
        'elevation' => $elev,
        'azimuth' => $az,
        'timestamp' => $timestamp,
        'local_time' => date('Y-m-d H:i:s', $local_timestamp),
        'timezone_offset' => $timezone_offset
    ];
}

// Get timezone offset for location (approximate)
function get_timezone_offset($lat, $lon) {
    // Simple timezone estimation based on longitude
    // This is approximate - for production, use a proper timezone database
    $offset_hours = round($lon / 15);
    
    // Adjust for daylight saving time (very rough approximation)
    $month = date('n');
    if ($month >= 3 && $month <= 11) {
        $offset_hours += 1; // DST adjustment
    }
    
    return $offset_hours * 3600; // Convert to seconds
}
