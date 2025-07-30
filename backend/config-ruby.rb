# frozen_string_literal: true

# World.CSS Ruby Configuration
#
# This file contains all configuration options for the Ruby Sinatra backend.
# Copy this file to your project root and customize as needed.

require 'dotenv'
require 'json'

# Load environment variables
Dotenv.load

module WorldCSS
  class Config
    # Server Configuration
    SERVER = {
      port: ENV['PORT'] || 4567,
      host: ENV['HOST'] || '0.0.0.0',
      environment: ENV['RACK_ENV'] || 'development',
      threaded: true
    }.freeze

    # CORS Configuration
    CORS = {
      origins: ENV['CORS_ORIGINS']&.split(',') || ['*'],
      methods: ['GET', 'POST', 'OPTIONS'],
      allow_headers: ['Content-Type', 'Authorization'],
      supports_credentials: true
    }.freeze

    # API Configuration
    API = {
      # OpenWeatherMap API
      openweather: {
        key: ENV['OPENWEATHER_API_KEY'],
        base_url: 'https://api.openweathermap.org/data/2.5',
        units: 'metric', # metric, imperial, kelvin
        timeout: 10
      },

      # IP Geolocation API
      geolocation: {
        provider: 'ip-api.com', # Alternative: 'ipapi.co', 'ipinfo.io'
        base_url: 'http://ip-api.com/json',
        timeout: 5
      },

      # Solar calculation settings
      solar: {
        cache_duration: 300, # 5 minutes for solar calculations
        timezone_estimation: true, # Use longitude-based timezone estimation
        precision: 6 # Decimal places for calculations
      },

      # Weather cache settings
      weather: {
        cache_duration: 1800, # 30 minutes for weather data
        include_forecast: false, # Set to true to include 5-day forecast
        include_alerts: false # Set to true to include weather alerts
      },

      # Location cache settings
      location: {
        cache_duration: 3600, # 1 hour for location data
        fallback_location: {
          latitude: 40.7128,
          longitude: -74.0060,
          city: 'New York',
          country: 'US',
          timezone: 'America/New_York'
        }
      }
    }.freeze

    # Cache Configuration
    CACHE = {
      directory: './backend/cache',
      cleanup_interval: 3600, # 1 hour in seconds
      max_age: 3600, # 1 hour default cache age
      compression: false, # Set to true to compress cache files
      max_size: 100 * 1024 * 1024 # 100MB max cache size
    }.freeze

    # Logging Configuration
    LOGGING = {
      level: ENV['LOG_LEVEL'] || 'INFO', # DEBUG, INFO, WARN, ERROR
      format: '%Y-%m-%d %H:%M:%S %z',
      file: ENV['LOG_FILE'], # Set to file path for file logging
      console: true,
      max_bytes: 10 * 1024 * 1024, # 10MB max log file size
      backup_count: 5
    }.freeze

    # Security Configuration
    SECURITY = {
      rate_limit: {
        enabled: true,
        window: 15 * 60, # 15 minutes in seconds
        max_requests: 100 # limit each IP to 100 requests per window
      },
      cors: {
        enabled: true,
        origins: ['*'],
        methods: ['GET', 'POST', 'OPTIONS']
      },
      headers: {
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block'
      }
    }.freeze

    # Development Configuration
    DEVELOPMENT = {
      debug: true,
      auto_reload: true,
      detailed_errors: true
    }.freeze

    # Production Configuration
    PRODUCTION = {
      debug: false,
      compression: true,
      static_cache: 86_400, # 24 hours for static files
      trust_proxy: true
    }.freeze

    # Custom Routes (optional)
    CUSTOM_ROUTES = {
      # Add your custom API endpoints here
      # '/api/custom' => -> { { message: 'Custom endpoint' } }
    }.freeze

    # Environment-specific overrides
    ENVIRONMENTS = {
      development: {
        LOGGING: {
          level: 'DEBUG'
        },
        CACHE: {
          max_age: 300 # 5 minutes for development
        },
        SECURITY: {
          rate_limit: {
            enabled: false
          }
        }
      },
      production: {
        LOGGING: {
          level: 'WARN'
        },
        SECURITY: {
          rate_limit: {
            max_requests: 1000 # Higher limit for production
          }
        }
      },
      test: {
        CACHE: {
          directory: './backend/cache-test'
        },
        LOGGING: {
          level: 'ERROR'
        }
      }
    }.freeze

    # Helper methods
    class << self
      def environment
        ENV['RACK_ENV'] || 'development'
      end

      def development?
        environment == 'development'
      end

      def production?
        environment == 'production'
      end

      def test?
        environment == 'test'
      end

      def get_config(section)
        config = self.const_get(section.upcase)
        
        # Apply environment-specific overrides
        if ENVIRONMENTS.key?(environment.to_sym)
          env_overrides = ENVIRONMENTS[environment.to_sym]
          if env_overrides.key?(section.upcase.to_sym)
            config = config.merge(env_overrides[section.upcase.to_sym])
          end
        end
        
        config
      end

      def server_config
        get_config('SERVER')
      end

      def api_config
        get_config('API')
      end

      def cache_config
        get_config('CACHE')
      end

      def logging_config
        get_config('LOGGING')
      end

      def security_config
        get_config('SECURITY')
      end

      def development_config
        get_config('DEVELOPMENT')
      end

      def production_config
        get_config('PRODUCTION')
      end

      def custom_routes
        CUSTOM_ROUTES
      end

      # Validation methods
      def validate_config!
        errors = []

        # Check required API keys
        if API[:openweather][:key].nil? || API[:openweather][:key].empty?
          errors << 'OPENWEATHER_API_KEY is required'
        end

        # Check cache directory
        cache_dir = CACHE[:directory]
        unless Dir.exist?(cache_dir)
          begin
            Dir.mkdir(cache_dir)
          rescue StandardError => e
            errors << "Cannot create cache directory: #{e.message}"
          end
        end

        # Check port availability
        port = SERVER[:port]
        if port < 1 || port > 65_535
          errors << "Invalid port number: #{port}"
        end

        raise errors.join(', ') unless errors.empty?
      end

      # Configuration dump for debugging
      def to_json
        {
          server: server_config,
          api: api_config,
          cache: cache_config,
          logging: logging_config,
          security: security_config,
          environment: environment,
          development: development_config,
          production: production_config
        }.to_json
      end
    end
  end
end 