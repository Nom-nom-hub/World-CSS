/**
 * World.CSS Node.js Configuration
 * 
 * This file contains all configuration options for the Node.js backend.
 * Copy this file to your project root and customize as needed.
 */

module.exports = {
    // Server Configuration
    server: {
        port: process.env.PORT || 3000,
        host: process.env.HOST || '0.0.0.0',
        cors: {
            origin: process.env.CORS_ORIGIN || '*',
            methods: ['GET', 'POST', 'OPTIONS'],
            credentials: true
        }
    },

    // API Configuration
    api: {
        // OpenWeatherMap API
        openweather: {
            key: process.env.OPENWEATHER_API_KEY,
            baseUrl: 'https://api.openweathermap.org/data/2.5',
            units: 'metric' // metric, imperial, kelvin
        },

        // IP Geolocation API
        geolocation: {
            provider: 'ip-api.com', // Alternative: 'ipapi.co', 'ipinfo.io'
            baseUrl: 'http://ip-api.com/json'
        },

        // Solar calculation settings
        solar: {
            cacheDuration: 300, // 5 minutes for solar calculations
            timezoneEstimation: true // Use longitude-based timezone estimation
        },

        // Weather cache settings
        weather: {
            cacheDuration: 1800, // 30 minutes for weather data
            includeForecast: false // Set to true to include 5-day forecast
        },

        // Location cache settings
        location: {
            cacheDuration: 3600, // 1 hour for location data
            fallbackLocation: {
                latitude: 40.7128,
                longitude: -74.0060,
                city: 'New York',
                country: 'US',
                timezone: 'America/New_York'
            }
        }
    },

    // Cache Configuration
    cache: {
        directory: './backend/cache',
        cleanupInterval: 3600000, // 1 hour in milliseconds
        maxAge: 3600, // 1 hour default cache age
        compression: false // Set to true to compress cache files
    },

    // Logging Configuration
    logging: {
        level: process.env.LOG_LEVEL || 'info', // error, warn, info, debug
        format: 'combined', // combined, common, dev, short, tiny
        file: process.env.LOG_FILE || null, // Set to file path for file logging
        console: true
    },

    // Security Configuration
    security: {
        rateLimit: {
            windowMs: 15 * 60 * 1000, // 15 minutes
            max: 100 // limit each IP to 100 requests per windowMs
        },
        helmet: {
            enabled: true,
            options: {
                contentSecurityPolicy: false,
                crossOriginEmbedderPolicy: false
            }
        }
    },

    // Development Configuration
    development: {
        debug: process.env.NODE_ENV === 'development',
        hotReload: true,
        autoRestart: true
    },

    // Production Configuration
    production: {
        compression: true,
        staticCache: 86400000, // 24 hours for static files
        trustProxy: true
    },

    // Custom Routes (optional)
    customRoutes: {
        // Add your custom API endpoints here
        // '/api/custom': (req, res) => { /* your logic */ }
    },

    // Environment-specific overrides
    environments: {
        development: {
            logging: {
                level: 'debug'
            },
            cache: {
                maxAge: 300 // 5 minutes for development
            }
        },
        production: {
            logging: {
                level: 'warn'
            },
            security: {
                rateLimit: {
                    max: 1000 // Higher limit for production
                }
            }
        },
        test: {
            cache: {
                directory: './backend/cache-test'
            },
            logging: {
                level: 'error'
            }
        }
    }
}; 