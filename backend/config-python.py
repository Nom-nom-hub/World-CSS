#!/usr/bin/env python3
"""
World.CSS Python Configuration

This file contains all configuration options for the Python Flask backend.
Copy this file to your project root and customize as needed.
"""

import os
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

class Config:
    """Base configuration class"""
    
    # Server Configuration
    SERVER = {
        'port': int(os.environ.get('PORT', 5000)),
        'host': os.environ.get('HOST', '0.0.0.0'),
        'debug': os.environ.get('FLASK_DEBUG', 'False').lower() == 'true',
        'threaded': True
    }
    
    # CORS Configuration
    CORS = {
        'origins': os.environ.get('CORS_ORIGINS', '*').split(','),
        'methods': ['GET', 'POST', 'OPTIONS'],
        'allow_headers': ['Content-Type', 'Authorization'],
        'supports_credentials': True
    }
    
    # API Configuration
    API = {
        # OpenWeatherMap API
        'openweather': {
            'key': os.environ.get('OPENWEATHER_API_KEY'),
            'base_url': 'https://api.openweathermap.org/data/2.5',
            'units': 'metric',  # metric, imperial, kelvin
            'timeout': 10
        },
        
        # IP Geolocation API
        'geolocation': {
            'provider': 'ip-api.com',  # Alternative: 'ipapi.co', 'ipinfo.io'
            'base_url': 'http://ip-api.com/json',
            'timeout': 5
        },
        
        # Solar calculation settings
        'solar': {
            'cache_duration': 300,  # 5 minutes for solar calculations
            'timezone_estimation': True,  # Use longitude-based timezone estimation
            'precision': 6  # Decimal places for calculations
        },
        
        # Weather cache settings
        'weather': {
            'cache_duration': 1800,  # 30 minutes for weather data
            'include_forecast': False,  # Set to True to include 5-day forecast
            'include_alerts': False  # Set to True to include weather alerts
        },
        
        # Location cache settings
        'location': {
            'cache_duration': 3600,  # 1 hour for location data
            'fallback_location': {
                'latitude': 40.7128,
                'longitude': -74.0060,
                'city': 'New York',
                'country': 'US',
                'timezone': 'America/New_York'
            }
        }
    }
    
    # Cache Configuration
    CACHE = {
        'directory': './backend/cache',
        'cleanup_interval': 3600,  # 1 hour in seconds
        'max_age': 3600,  # 1 hour default cache age
        'compression': False,  # Set to True to compress cache files
        'max_size': 100 * 1024 * 1024  # 100MB max cache size
    }
    
    # Logging Configuration
    LOGGING = {
        'level': os.environ.get('LOG_LEVEL', 'INFO'),  # DEBUG, INFO, WARNING, ERROR
        'format': '%(asctime)s - %(name)s - %(levelname)s - %(message)s',
        'file': os.environ.get('LOG_FILE'),  # Set to file path for file logging
        'console': True,
        'max_bytes': 10 * 1024 * 1024,  # 10MB max log file size
        'backup_count': 5
    }
    
    # Security Configuration
    SECURITY = {
        'rate_limit': {
            'enabled': True,
            'window': 15 * 60,  # 15 minutes in seconds
            'max_requests': 100  # limit each IP to 100 requests per window
        },
        'cors': {
            'enabled': True,
            'origins': ['*'],
            'methods': ['GET', 'POST', 'OPTIONS']
        },
        'headers': {
            'X-Content-Type-Options': 'nosniff',
            'X-Frame-Options': 'DENY',
            'X-XSS-Protection': '1; mode=block'
        }
    }
    
    # Development Configuration
    DEVELOPMENT = {
        'debug': True,
        'auto_reload': True,
        'detailed_errors': True
    }
    
    # Production Configuration
    PRODUCTION = {
        'debug': False,
        'compression': True,
        'static_cache': 86400,  # 24 hours for static files
        'trust_proxy': True
    }
    
    # Custom Routes (optional)
    CUSTOM_ROUTES = {
        # Add your custom API endpoints here
        # '/api/custom': lambda: {'message': 'Custom endpoint'}
    }
    
    # Environment-specific overrides
    ENVIRONMENTS = {
        'development': {
            'LOGGING': {
                'level': 'DEBUG'
            },
            'CACHE': {
                'max_age': 300  # 5 minutes for development
            },
            'SECURITY': {
                'rate_limit': {
                    'enabled': False
                }
            }
        },
        'production': {
            'LOGGING': {
                'level': 'WARNING'
            },
            'SECURITY': {
                'rate_limit': {
                    'max_requests': 1000  # Higher limit for production
                }
            }
        },
        'test': {
            'CACHE': {
                'directory': './backend/cache-test'
            },
            'LOGGING': {
                'level': 'ERROR'
            }
        }
    }

class DevelopmentConfig(Config):
    """Development configuration"""
    DEBUG = True
    TESTING = False

class ProductionConfig(Config):
    """Production configuration"""
    DEBUG = False
    TESTING = False

class TestingConfig(Config):
    """Testing configuration"""
    DEBUG = True
    TESTING = True

# Configuration mapping
config = {
    'development': DevelopmentConfig,
    'production': ProductionConfig,
    'testing': TestingConfig,
    'default': DevelopmentConfig
}

def get_config():
    """Get configuration based on environment"""
    env = os.environ.get('FLASK_ENV', 'development')
    return config.get(env, config['default']) 