#!/usr/bin/env ruby
# frozen_string_literal: true

# World.CSS Ruby Backend
# Real-time environmental theming system backend using Sinatra

require 'sinatra'
require 'sinatra/cors'
require 'json'
require 'net/http'
require 'uri'
require 'time'
require 'fileutils'
require 'dotenv'

# Load environment variables
Dotenv.load

# Configuration
set :port, ENV['PORT'] || 4567
set :bind, '0.0.0.0'

# Enable CORS
register Sinatra::Cors
set :allow_origin, "*"
set :allow_methods, "GET,POST,OPTIONS"
set :allow_credentials, true

# Cache configuration
CACHE_DURATION = (ENV['CACHE_DURATION'] || 3600).to_i
CACHE_DIR = File.join(__dir__, 'cache')

# Ensure cache directory exists
FileUtils.mkdir_p(CACHE_DIR)

# Cache helper functions
def get_cache_path(key)
  File.join(CACHE_DIR, "#{key}.json")
end

def get_cached_data(key)
  begin
    cache_path = get_cache_path(key)
    if File.exist?(cache_path)
      data = JSON.parse(File.read(cache_path))
      if Time.now.to_i - data['timestamp'] < CACHE_DURATION
        return data['data']
      end
    end
  rescue => e
    puts "Cache read error: #{e}"
  end
  nil
end

def set_cached_data(key, data)
  begin
    cache_path = get_cache_path(key)
    cache_data = {
      'timestamp' => Time.now.to_i,
      'data' => data
    }
    File.write(cache_path, cache_data.to_json)
  rescue => e
    puts "Cache write error: #{e}"
  end
end

def clean_cache
  begin
    current_time = Time.now.to_i
    Dir.entries(CACHE_DIR).each do |filename|
      next unless filename.end_with?('.json')
      
      file_path = File.join(CACHE_DIR, filename)
      file_time = File.mtime(file_path).to_i
      if current_time - file_time > CACHE_DURATION
        File.delete(file_path)
        puts "Cleaned cache file: #{filename}"
      end
    end
  rescue => e
    puts "Cache cleanup error: #{e}"
  end
end

# Clean cache every hour
Thread.new do
  loop do
    sleep 3600 # 1 hour
    clean_cache
  end
end

def get_timezone_offset(lat, lng)
  # Simple timezone estimation based on longitude
  # In a real implementation, you'd use a timezone API
  offset_hours = (lng / 15.0).round
  offset_hours * 60 # Convert to minutes
end

def calculate_solar_position(lat, lng, date = Time.now.utc)
  # Convert to radians
  lat_rad = lat * Math::PI / 180
  lng_rad = lng * Math::PI / 180
  
  # Get timezone offset
  timezone_offset = get_timezone_offset(lat, lng)
  
  # Calculate Julian Day Number
  year = date.year
  month = date.month
  day = date.day
  hour = date.hour
  minute = date.min
  
  jd = 367 * year - (7 * (year + (month + 9) / 12) / 4).floor + 
       (275 * month / 9).floor + day + 1721013.5 + 
       (hour + minute / 60.0) / 24
  
  # Calculate time since J2000 epoch
  t = (jd - 2451545.0) / 36525
  
  # Calculate mean longitude of the sun
  L0 = 280.46645 + 36000.76983 * t + 0.0003032 * t * t
  
  # Calculate mean anomaly of the sun
  M = 357.52910 + 35999.05030 * t - 0.0001559 * t * t - 0.00000048 * t * t * t
  
  # Calculate eccentricity of Earth's orbit
  e = 0.016708617 - 0.000042037 * t - 0.0000001236 * t * t
  
  # Calculate sun's equation of center
  C = (1.914600 - 0.004817 * t - 0.000014 * t * t) * Math.sin(M * Math::PI / 180) +
      (0.019993 - 0.000101 * t) * Math.sin(2 * M * Math::PI / 180) +
      0.000290 * Math.sin(3 * M * Math::PI / 180)
  
  # Calculate true longitude of the sun
  L = L0 + C
  
  # Calculate apparent longitude of the sun
  omega = 125.04 - 1934.136 * t
  lambda = L - 0.00569 - 0.00478 * Math.sin(omega * Math::PI / 180)
  
  # Calculate obliquity of the ecliptic
  epsilon = 23.439 - 0.0000004 * t
  
  # Calculate right ascension and declination
  alpha = Math.atan2(Math.cos(epsilon * Math::PI / 180) * Math.sin(lambda * Math::PI / 180),
                     Math.cos(lambda * Math::PI / 180)) * 180 / Math::PI
  delta = Math.asin(Math.sin(epsilon * Math::PI / 180) * Math.sin(lambda * Math::PI / 180)) * 180 / Math::PI
  
  # Calculate local sidereal time
  lst = 280.46061837 + 360.98564736629 * (jd - 2451545.0) + 0.000387933 * t * t - t * t * t / 38710000 + lng
  
  # Calculate hour angle
  ha = lst - alpha
  ha += 360 while ha < 0
  ha -= 360 while ha > 360
  
  # Calculate azimuth and elevation
  ha_rad = ha * Math::PI / 180
  delta_rad = delta * Math::PI / 180
  
  sin_el = Math.sin(lat_rad) * Math.sin(delta_rad) + 
           Math.cos(lat_rad) * Math.cos(delta_rad) * Math.cos(ha_rad)
  elevation = Math.asin(sin_el) * 180 / Math::PI
  
  cos_az = (Math.sin(delta_rad) - Math.sin(lat_rad) * sin_el) / 
           (Math.cos(lat_rad) * Math.cos(Math.asin(sin_el)))
  azimuth = Math.acos(cos_az) * 180 / Math::PI
  
  # Adjust azimuth based on hour angle
  final_azimuth = ha > 180 ? 360 - azimuth : azimuth
  
  {
    'elevation' => elevation,
    'azimuth' => final_azimuth
  }
end

# API Routes
get '/api/locate' do
  content_type :json
  
  begin
    # Get client IP
    client_ip = request.ip
    cache_key = "locate_#{client_ip}"
    data = get_cached_data(cache_key)
    
    unless data
      # Use IP geolocation service
      uri = URI("http://ip-api.com/json/#{client_ip}")
      response = Net::HTTP.get_response(uri)
      
      if response.is_a?(Net::HTTPSuccess)
        resp_data = JSON.parse(response.body)
        if resp_data['status'] == 'success'
          data = {
            'latitude' => resp_data['lat'],
            'longitude' => resp_data['lon'],
            'city' => resp_data['city'],
            'country' => resp_data['country'],
            'timezone' => resp_data['timezone']
          }
          set_cached_data(cache_key, data)
        else
          # Fallback to default location
          data = {
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'city' => 'New York',
            'country' => 'US',
            'timezone' => 'America/New_York'
          }
        end
      else
        # Fallback to default location
        data = {
          'latitude' => 40.7128,
          'longitude' => -74.0060,
          'city' => 'New York',
          'country' => 'US',
          'timezone' => 'America/New_York'
        }
      end
    end
    
    {
      'success' => true,
      'data' => data
    }.to_json
  rescue => e
    puts "Location error: #{e}"
    status 500
    {
      'success' => false,
      'error' => 'Failed to get location'
    }.to_json
  end
end

get '/api/sun' do
  content_type :json
  
  begin
    lat = params['lat']
    lng = params['lng']
    
    unless lat && lng
      status 400
      return {
        'success' => false,
        'error' => 'Latitude and longitude required'
      }.to_json
    end
    
    # Cache for 5 minutes
    cache_key = "sun_#{lat}_#{lng}_#{Time.now.to_i / 300}"
    data = get_cached_data(cache_key)
    
    unless data
      solar = calculate_solar_position(lat.to_f, lng.to_f)
      
      # Determine phase based on elevation
      elevation = solar['elevation']
      phase = case elevation
              when -Float::INFINITY...-6 then 'night'
              when -6...-3 then 'twilight'
              when -3...5 then 'sunrise'
              when 5...20 then 'day'
              when 20...45 then 'noon'
              when 45...70 then 'sunset'
              when 70...100 then 'evening'
              else 'day'
              end
      
      data = {
        'elevation' => elevation,
        'azimuth' => solar['azimuth'],
        'phase' => phase,
        'timestamp' => (Time.now.to_f * 1000).to_i
      }
      
      set_cached_data(cache_key, data)
    end
    
    {
      'success' => true,
      'data' => data
    }.to_json
  rescue => e
    puts "Solar calculation error: #{e}"
    status 500
    {
      'success' => false,
      'error' => 'Failed to calculate solar position'
    }.to_json
  end
end

get '/api/weather' do
  content_type :json
  
  begin
    lat = params['lat']
    lng = params['lng']
    
    unless lat && lng
      status 400
      return {
        'success' => false,
        'error' => 'Latitude and longitude required'
      }.to_json
    end
    
    api_key = ENV['OPENWEATHER_API_KEY']
    unless api_key
      status 500
      return {
        'success' => false,
        'error' => 'OpenWeatherMap API key not configured'
      }.to_json
    end
    
    cache_key = "weather_#{lat}_#{lng}"
    data = get_cached_data(cache_key)
    
    unless data
      url = "https://api.openweathermap.org/data/2.5/weather"
      uri = URI(url)
      uri.query = URI.encode_www_form({
        'lat' => lat,
        'lon' => lng,
        'appid' => api_key,
        'units' => 'metric'
      })
      
      response = Net::HTTP.get_response(uri)
      if response.is_a?(Net::HTTPSuccess)
        weather_data = JSON.parse(response.body)
        data = {
          'temp' => weather_data['main']['temp'],
          'humidity' => weather_data['main']['humidity'],
          'pressure' => weather_data['main']['pressure'],
          'clouds' => weather_data['clouds']['all'],
          'description' => weather_data['weather'][0]['description'],
          'icon' => weather_data['weather'][0]['icon'],
          'wind_speed' => weather_data['wind']['speed'],
          'wind_deg' => weather_data['wind']['deg']
        }
        set_cached_data(cache_key, data)
      else
        status 500
        return {
          'success' => false,
          'error' => 'Failed to get weather data'
        }.to_json
      end
    end
    
    {
      'success' => true,
      'data' => data
    }.to_json
  rescue => e
    puts "Weather error: #{e}"
    status 500
    {
      'success' => false,
      'error' => 'Failed to get weather data'
    }.to_json
  end
end

get '/api/health' do
  content_type :json
  {
    'success' => true,
    'message' => 'World.CSS Ruby backend is running',
    'timestamp' => Time.now.utc.iso8601
  }.to_json
end

# Serve static files
get '/' do
  send_file File.join(__dir__, '..', 'example.html')
end

get '/*' do
  filename = params[:splat].first
  file_path = File.join(__dir__, '..', filename)
  if File.exist?(file_path)
    send_file file_path
  else
    status 404
    'File not found'
  end
end

# Start server
puts "🌍 World.CSS Ruby backend running on port #{settings.port}"
puts "📁 Cache directory: #{CACHE_DIR}"
puts "🔑 API Key configured: #{ENV['OPENWEATHER_API_KEY'] ? 'Yes' : 'No'}" 