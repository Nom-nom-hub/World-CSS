#!/usr/bin/env python3
"""
World.CSS Python Backend
Real-time environmental theming system backend using Flask
"""

import os
import json
import math
import time
import requests
from datetime import datetime
from flask import Flask, request, jsonify, send_from_directory
from flask_cors import CORS
import threading
import schedule

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Configuration
PORT = int(os.environ.get('PORT', 5000))
CACHE_DURATION = int(os.environ.get('CACHE_DURATION', 3600))  # 1 hour
CACHE_DIR = os.path.join(os.path.dirname(__file__), 'cache')

# Ensure cache directory exists
os.makedirs(CACHE_DIR, exist_ok=True)

# Load environment variables
from dotenv import load_dotenv
load_dotenv()

def get_cache_path(key):
    """Get cache file path for a key"""
    return os.path.join(CACHE_DIR, f"{key}.json")

def get_cached_data(key):
    """Get cached data if it exists and is not expired"""
    try:
        cache_path = get_cache_path(key)
        if os.path.exists(cache_path):
            with open(cache_path, 'r') as f:
                data = json.load(f)
                if time.time() - data['timestamp'] < CACHE_DURATION:
                    return data['data']
    except Exception as e:
        print(f"Cache read error: {e}")
    return None

def set_cached_data(key, data):
    """Set cached data with timestamp"""
    try:
        cache_path = get_cache_path(key)
        cache_data = {
            'timestamp': time.time(),
            'data': data
        }
        with open(cache_path, 'w') as f:
            json.dump(cache_data, f)
    except Exception as e:
        print(f"Cache write error: {e}")

def clean_cache():
    """Clean expired cache files"""
    try:
        current_time = time.time()
        for filename in os.listdir(CACHE_DIR):
            if filename.endswith('.json'):
                file_path = os.path.join(CACHE_DIR, filename)
                file_time = os.path.getmtime(file_path)
                if current_time - file_time > CACHE_DURATION:
                    os.remove(file_path)
                    print(f"Cleaned cache file: {filename}")
    except Exception as e:
        print(f"Cache cleanup error: {e}")

def get_timezone_offset(lat, lng):
    """Get timezone offset for a location (simplified)"""
    # Simple timezone estimation based on longitude
    # In a real implementation, you'd use a timezone API
    offset_hours = round(lng / 15)
    return offset_hours * 60  # Convert to minutes

def calculate_solar_position(lat, lng, date=None):
    """Calculate solar position (elevation and azimuth)"""
    if date is None:
        date = datetime.utcnow()
    
    # Convert to radians
    lat_rad = math.radians(lat)
    lng_rad = math.radians(lng)
    
    # Get timezone offset
    timezone_offset = get_timezone_offset(lat, lng)
    
    # Calculate Julian Day Number
    year = date.year
    month = date.month
    day = date.day
    hour = date.hour
    minute = date.minute
    
    jd = (367 * year - int(7 * (year + int((month + 9) / 12)) / 4) + 
           int(275 * month / 9) + day + 1721013.5 + 
           (hour + minute / 60) / 24)
    
    # Calculate time since J2000 epoch
    t = (jd - 2451545.0) / 36525
    
    # Calculate mean longitude of the sun
    L0 = 280.46645 + 36000.76983 * t + 0.0003032 * t * t
    
    # Calculate mean anomaly of the sun
    M = 357.52910 + 35999.05030 * t - 0.0001559 * t * t - 0.00000048 * t * t * t
    
    # Calculate eccentricity of Earth's orbit
    e = 0.016708617 - 0.000042037 * t - 0.0000001236 * t * t
    
    # Calculate sun's equation of center
    C = ((1.914600 - 0.004817 * t - 0.000014 * t * t) * math.sin(math.radians(M)) +
         (0.019993 - 0.000101 * t) * math.sin(2 * math.radians(M)) +
         0.000290 * math.sin(3 * math.radians(M)))
    
    # Calculate true longitude of the sun
    L = L0 + C
    
    # Calculate apparent longitude of the sun
    omega = 125.04 - 1934.136 * t
    lambda_ = L - 0.00569 - 0.00478 * math.sin(math.radians(omega))
    
    # Calculate obliquity of the ecliptic
    epsilon = 23.439 - 0.0000004 * t
    
    # Calculate right ascension and declination
    alpha = math.degrees(math.atan2(
        math.cos(math.radians(epsilon)) * math.sin(math.radians(lambda_)),
        math.cos(math.radians(lambda_))
    ))
    delta = math.degrees(math.asin(
        math.sin(math.radians(epsilon)) * math.sin(math.radians(lambda_))
    ))
    
    # Calculate local sidereal time
    lst = (280.46061837 + 360.98564736629 * (jd - 2451545.0) + 
           0.000387933 * t * t - t * t * t / 38710000 + lng)
    
    # Calculate hour angle
    ha = lst - alpha
    while ha < 0:
        ha += 360
    while ha > 360:
        ha -= 360
    
    # Calculate azimuth and elevation
    ha_rad = math.radians(ha)
    delta_rad = math.radians(delta)
    
    sin_el = (math.sin(lat_rad) * math.sin(delta_rad) + 
              math.cos(lat_rad) * math.cos(delta_rad) * math.cos(ha_rad))
    elevation = math.degrees(math.asin(sin_el))
    
    cos_az = ((math.sin(delta_rad) - math.sin(lat_rad) * sin_el) / 
               (math.cos(lat_rad) * math.cos(math.asin(sin_el))))
    azimuth = math.degrees(math.acos(cos_az))
    
    # Adjust azimuth based on hour angle
    final_azimuth = 360 - azimuth if ha > 180 else azimuth
    
    return {
        'elevation': elevation,
        'azimuth': final_azimuth
    }

# API Routes
@app.route('/api/locate')
def locate():
    """Get location based on IP address"""
    try:
        # Get client IP
        client_ip = request.remote_addr
        cache_key = f"locate_{client_ip}"
        data = get_cached_data(cache_key)
        
        if not data:
            # Use IP geolocation service
            response = requests.get(f"http://ip-api.com/json/{client_ip}")
            
            if response.status_code == 200 and response.json().get('status') == 'success':
                resp_data = response.json()
                data = {
                    'latitude': resp_data['lat'],
                    'longitude': resp_data['lon'],
                    'city': resp_data['city'],
                    'country': resp_data['country'],
                    'timezone': resp_data['timezone']
                }
                set_cached_data(cache_key, data)
            else:
                # Fallback to default location
                data = {
                    'latitude': 40.7128,
                    'longitude': -74.0060,
                    'city': 'New York',
                    'country': 'US',
                    'timezone': 'America/New_York'
                }
        
        return jsonify({
            'success': True,
            'data': data
        })
    except Exception as e:
        print(f"Location error: {e}")
        return jsonify({
            'success': False,
            'error': 'Failed to get location'
        }), 500

@app.route('/api/sun')
def sun():
    """Get solar position data"""
    try:
        lat = request.args.get('lat')
        lng = request.args.get('lng')
        
        if not lat or not lng:
            return jsonify({
                'success': False,
                'error': 'Latitude and longitude required'
            }), 400
        
        # Cache for 5 minutes
        cache_key = f"sun_{lat}_{lng}_{int(time.time() / 300)}"
        data = get_cached_data(cache_key)
        
        if not data:
            solar = calculate_solar_position(float(lat), float(lng))
            
            # Determine phase based on elevation
            elevation = solar['elevation']
            if elevation < -6:
                phase = 'night'
            elif elevation < -3:
                phase = 'twilight'
            elif elevation < 5:
                phase = 'sunrise'
            elif elevation < 20:
                phase = 'day'
            elif elevation < 45:
                phase = 'noon'
            elif elevation < 70:
                phase = 'sunset'
            elif elevation < 100:
                phase = 'evening'
            else:
                phase = 'day'
            
            data = {
                'elevation': elevation,
                'azimuth': solar['azimuth'],
                'phase': phase,
                'timestamp': int(time.time() * 1000)
            }
            
            set_cached_data(cache_key, data)
        
        return jsonify({
            'success': True,
            'data': data
        })
    except Exception as e:
        print(f"Solar calculation error: {e}")
        return jsonify({
            'success': False,
            'error': 'Failed to calculate solar position'
        }), 500

@app.route('/api/weather')
def weather():
    """Get weather data"""
    try:
        lat = request.args.get('lat')
        lng = request.args.get('lng')
        
        if not lat or not lng:
            return jsonify({
                'success': False,
                'error': 'Latitude and longitude required'
            }), 400
        
        api_key = os.getenv('OPENWEATHER_API_KEY')
        if not api_key:
            return jsonify({
                'success': False,
                'error': 'OpenWeatherMap API key not configured'
            }), 500
        
        cache_key = f"weather_{lat}_{lng}"
        data = get_cached_data(cache_key)
        
        if not data:
            url = f"https://api.openweathermap.org/data/2.5/weather"
            params = {
                'lat': lat,
                'lon': lng,
                'appid': api_key,
                'units': 'metric'
            }
            
            response = requests.get(url, params=params)
            if response.status_code == 200:
                weather_data = response.json()
                data = {
                    'temp': weather_data['main']['temp'],
                    'humidity': weather_data['main']['humidity'],
                    'pressure': weather_data['main']['pressure'],
                    'clouds': weather_data['clouds']['all'],
                    'description': weather_data['weather'][0]['description'],
                    'icon': weather_data['weather'][0]['icon'],
                    'wind_speed': weather_data['wind']['speed'],
                    'wind_deg': weather_data['wind']['deg']
                }
                set_cached_data(cache_key, data)
            else:
                return jsonify({
                    'success': False,
                    'error': 'Failed to get weather data'
                }), 500
        
        return jsonify({
            'success': True,
            'data': data
        })
    except Exception as e:
        print(f"Weather error: {e}")
        return jsonify({
            'success': False,
            'error': 'Failed to get weather data'
        }), 500

@app.route('/api/health')
def health():
    """Health check endpoint"""
    return jsonify({
        'success': True,
        'message': 'World.CSS Python backend is running',
        'timestamp': datetime.utcnow().isoformat()
    })

# Serve static files
@app.route('/')
def index():
    return send_from_directory('..', 'example.html')

@app.route('/<path:filename>')
def serve_static(filename):
    return send_from_directory('..', filename)

def run_cache_cleanup():
    """Run cache cleanup in background"""
    schedule.every().hour.do(clean_cache)
    while True:
        schedule.run_pending()
        time.sleep(60)

if __name__ == '__main__':
    # Start cache cleanup in background thread
    cleanup_thread = threading.Thread(target=run_cache_cleanup, daemon=True)
    cleanup_thread.start()
    
    print(f"🌍 World.CSS Python backend running on port {PORT}")
    print(f"📁 Cache directory: {CACHE_DIR}")
    print(f"🔑 API Key configured: {'Yes' if os.getenv('OPENWEATHER_API_KEY') else 'No'}")
    
    app.run(host='0.0.0.0', port=PORT, debug=False) 