const express = require('express');
const axios = require('axios');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = process.env.PORT || 3000;

// Load environment variables
require('dotenv').config();

// Middleware
app.use(express.json());
app.use(express.static(path.join(__dirname, '..')));

// Cache directory
const CACHE_DIR = path.join(__dirname, 'cache');
const CACHE_DURATION = process.env.CACHE_DURATION || 3600; // 1 hour

// Ensure cache directory exists
if (!fs.existsSync(CACHE_DIR)) {
    fs.mkdirSync(CACHE_DIR, { recursive: true });
}

// Cache helper functions
function getCachePath(key) {
    return path.join(CACHE_DIR, `${key}.json`);
}

function getCachedData(key) {
    try {
        const cachePath = getCachePath(key);
        if (fs.existsSync(cachePath)) {
            const data = JSON.parse(fs.readFileSync(cachePath, 'utf8'));
            if (Date.now() - data.timestamp < CACHE_DURATION * 1000) {
                return data.data;
            }
        }
    } catch (error) {
        console.error('Cache read error:', error);
    }
    return null;
}

function setCachedData(key, data) {
    try {
        const cachePath = getCachePath(key);
        const cacheData = {
            timestamp: Date.now(),
            data: data
        };
        fs.writeFileSync(cachePath, JSON.stringify(cacheData));
    } catch (error) {
        console.error('Cache write error:', error);
    }
}

// Clean old cache files
function cleanCache() {
    try {
        const files = fs.readdirSync(CACHE_DIR);
        const now = Date.now();
        
        files.forEach(file => {
            if (file.endsWith('.json')) {
                const filePath = path.join(CACHE_DIR, file);
                const stats = fs.statSync(filePath);
                if (now - stats.mtime.getTime() > CACHE_DURATION * 1000) {
                    fs.unlinkSync(filePath);
                    console.log(`Cleaned cache file: ${file}`);
                }
            }
        });
    } catch (error) {
        console.error('Cache cleanup error:', error);
    }
}

// Clean cache every hour
setInterval(cleanCache, 60 * 60 * 1000);

// Get timezone offset for a location
function getTimezoneOffset(lat, lng) {
    // Simple timezone estimation based on longitude
    // In a real implementation, you'd use a timezone API
    const offsetHours = Math.round(lng / 15);
    return offsetHours * 60; // Convert to minutes
}

// Solar calculation functions
function calculateSolarPosition(lat, lng, date = new Date()) {
    // Convert to radians
    const latRad = lat * Math.PI / 180;
    const lngRad = lng * Math.PI / 180;
    
    // Get timezone offset
    const timezoneOffset = getTimezoneOffset(lat, lng);
    
    // Calculate Julian Day Number
    const year = date.getUTCFullYear();
    const month = date.getUTCMonth() + 1;
    const day = date.getUTCDate();
    const hour = date.getUTCHours();
    const minute = date.getUTCMinutes();
    
    let jd = 367 * year - Math.floor(7 * (year + Math.floor((month + 9) / 12)) / 4) + 
             Math.floor(275 * month / 9) + day + 1721013.5 + 
             (hour + minute / 60) / 24;
    
    // Calculate time since J2000 epoch
    const t = (jd - 2451545.0) / 36525;
    
    // Calculate mean longitude of the sun
    const L0 = 280.46645 + 36000.76983 * t + 0.0003032 * t * t;
    
    // Calculate mean anomaly of the sun
    const M = 357.52910 + 35999.05030 * t - 0.0001559 * t * t - 0.00000048 * t * t * t;
    
    // Calculate eccentricity of Earth's orbit
    const e = 0.016708617 - 0.000042037 * t - 0.0000001236 * t * t;
    
    // Calculate sun's equation of center
    const C = (1.914600 - 0.004817 * t - 0.000014 * t * t) * Math.sin(M * Math.PI / 180) +
              (0.019993 - 0.000101 * t) * Math.sin(2 * M * Math.PI / 180) +
              0.000290 * Math.sin(3 * M * Math.PI / 180);
    
    // Calculate true longitude of the sun
    const L = L0 + C;
    
    // Calculate apparent longitude of the sun
    const omega = 125.04 - 1934.136 * t;
    const lambda = L - 0.00569 - 0.00478 * Math.sin(omega * Math.PI / 180);
    
    // Calculate obliquity of the ecliptic
    const epsilon = 23.439 - 0.0000004 * t;
    
    // Calculate right ascension and declination
    const alpha = Math.atan2(Math.cos(epsilon * Math.PI / 180) * Math.sin(lambda * Math.PI / 180),
                             Math.cos(lambda * Math.PI / 180)) * 180 / Math.PI;
    const delta = Math.asin(Math.sin(epsilon * Math.PI / 180) * Math.sin(lambda * Math.PI / 180)) * 180 / Math.PI;
    
    // Calculate local sidereal time
    const lst = 280.46061837 + 360.98564736629 * (jd - 2451545.0) + 0.000387933 * t * t - t * t * t / 38710000 + lng;
    
    // Calculate hour angle
    let ha = lst - alpha;
    while (ha < 0) ha += 360;
    while (ha > 360) ha -= 360;
    
    // Calculate azimuth and elevation
    const haRad = ha * Math.PI / 180;
    const deltaRad = delta * Math.PI / 180;
    
    const sinEl = Math.sin(latRad) * Math.sin(deltaRad) + 
                  Math.cos(latRad) * Math.cos(deltaRad) * Math.cos(haRad);
    const elevation = Math.asin(sinEl) * 180 / Math.PI;
    
    const cosAz = (Math.sin(deltaRad) - Math.sin(latRad) * sinEl) / 
                  (Math.cos(latRad) * Math.cos(Math.asin(sinEl)));
    const azimuth = Math.acos(cosAz) * 180 / Math.PI;
    
    // Adjust azimuth based on hour angle
    const finalAzimuth = ha > 180 ? 360 - azimuth : azimuth;
    
    return {
        elevation: elevation,
        azimuth: finalAzimuth
    };
}

// API Routes
app.get('/api/locate', async (req, res) => {
    try {
        const cacheKey = 'locate_' + req.ip;
        let data = getCachedData(cacheKey);
        
        if (!data) {
            // Use IP geolocation service
            const response = await axios.get(`http://ip-api.com/json/${req.ip}`);
            
            if (response.data.status === 'success') {
                data = {
                    latitude: response.data.lat,
                    longitude: response.data.lon,
                    city: response.data.city,
                    country: response.data.country,
                    timezone: response.data.timezone
                };
                setCachedData(cacheKey, data);
            } else {
                // Fallback to default location
                data = {
                    latitude: 40.7128,
                    longitude: -74.0060,
                    city: 'New York',
                    country: 'US',
                    timezone: 'America/New_York'
                };
            }
        }
        
        res.json({
            success: true,
            data: data
        });
    } catch (error) {
        console.error('Location error:', error);
        res.status(500).json({
            success: false,
            error: 'Failed to get location'
        });
    }
});

app.get('/api/sun', async (req, res) => {
    try {
        const { lat, lng } = req.query;
        
        if (!lat || !lng) {
            return res.status(400).json({
                success: false,
                error: 'Latitude and longitude required'
            });
        }
        
        const cacheKey = `sun_${lat}_${lng}_${Math.floor(Date.now() / (5 * 60 * 1000))}`; // Cache for 5 minutes
        let data = getCachedData(cacheKey);
        
        if (!data) {
            const solar = calculateSolarPosition(parseFloat(lat), parseFloat(lng));
            
            // Determine phase based on elevation
            let phase = 'day';
            if (solar.elevation < -6) phase = 'night';
            else if (solar.elevation < -3) phase = 'twilight';
            else if (solar.elevation < 5) phase = 'sunrise';
            else if (solar.elevation < 20) phase = 'day';
            else if (solar.elevation < 45) phase = 'noon';
            else if (solar.elevation < 70) phase = 'sunset';
            else if (solar.elevation < 100) phase = 'evening';
            
            data = {
                elevation: solar.elevation,
                azimuth: solar.azimuth,
                phase: phase,
                timestamp: Date.now()
            };
            
            setCachedData(cacheKey, data);
        }
        
        res.json({
            success: true,
            data: data
        });
    } catch (error) {
        console.error('Solar calculation error:', error);
        res.status(500).json({
            success: false,
            error: 'Failed to calculate solar position'
        });
    }
});

app.get('/api/weather', async (req, res) => {
    try {
        const { lat, lng } = req.query;
        
        if (!lat || !lng) {
            return res.status(400).json({
                success: false,
                error: 'Latitude and longitude required'
            });
        }
        
        const apiKey = process.env.OPENWEATHER_API_KEY;
        if (!apiKey) {
            return res.status(500).json({
                success: false,
                error: 'OpenWeatherMap API key not configured'
            });
        }
        
        const cacheKey = `weather_${lat}_${lng}`;
        let data = getCachedData(cacheKey);
        
        if (!data) {
            const response = await axios.get(
                `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lng}&appid=${apiKey}&units=metric`
            );
            
            data = {
                temp: response.data.main.temp,
                humidity: response.data.main.humidity,
                pressure: response.data.main.pressure,
                clouds: response.data.clouds.all,
                description: response.data.weather[0].description,
                icon: response.data.weather[0].icon,
                wind_speed: response.data.wind.speed,
                wind_deg: response.data.wind.deg
            };
            
            setCachedData(cacheKey, data);
        }
        
        res.json({
            success: true,
            data: data
        });
    } catch (error) {
        console.error('Weather error:', error);
        res.status(500).json({
            success: false,
            error: 'Failed to get weather data'
        });
    }
});

// Health check
app.get('/api/health', (req, res) => {
    res.json({
        success: true,
        message: 'World.CSS Node.js backend is running',
        timestamp: new Date().toISOString()
    });
});

// Start server
app.listen(PORT, () => {
    console.log(`🌍 World.CSS Node.js backend running on port ${PORT}`);
    console.log(`📁 Cache directory: ${CACHE_DIR}`);
    console.log(`🔑 API Key configured: ${process.env.OPENWEATHER_API_KEY ? 'Yes' : 'No'}`);
});

module.exports = app; 