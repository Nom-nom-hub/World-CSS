# World.CSS Backend Implementations

World.CSS provides multiple backend implementations so you can use your preferred technology stack. All backends provide the same API endpoints and functionality.

## 🚀 Available Backends

### **PHP Backend** (Default)
- **File**: `backend/world.php`
- **Requirements**: PHP 7.4+ with cURL extension
- **Setup**: Already configured in the project

### **Node.js Backend**
- **File**: `backend/node-server.js`
- **Requirements**: Node.js 14+
- **Dependencies**: Express, Axios, dotenv

### **Python Backend**
- **File**: `backend/python-app.py`
- **Requirements**: Python 3.7+
- **Dependencies**: Flask, Flask-CORS, requests, python-dotenv, schedule

### **Ruby Backend**
- **File**: `backend/ruby-app.rb`
- **Requirements**: Ruby 2.7+
- **Dependencies**: Sinatra, sinatra-cors, dotenv

## 📋 API Endpoints

All backends provide these endpoints:

- **`GET /api/locate`** - IP-based geolocation
- **`GET /api/sun?lat=X&lng=Y`** - Solar elevation/azimuth calculation
- **`GET /api/weather?lat=X&lng=Y`** - Weather data from OpenWeatherMap
- **`GET /api/health`** - Health check

## 🔧 Setup Instructions

### **1. Environment Configuration**

Create a `.env` file in your project root:

```env
# OpenWeatherMap API Key (required)
OPENWEATHER_API_KEY=your_openweathermap_api_key_here

# Cache duration in seconds (optional)
CACHE_DURATION=3600

# Port (optional, backend-specific defaults)
PORT=3000
```

### **2. PHP Backend Setup**

```bash
# Already configured - just ensure PHP and cURL are installed
php -v
```

**Start server:**
```bash
# Using Apache/Nginx (recommended)
# Point your web server to the project directory

# Or using PHP built-in server
php -S localhost:8000 -t .
```

### **3. Node.js Backend Setup**

```bash
# Navigate to backend directory
cd backend

# Install dependencies
npm install

# Start server
npm start

# Or for development with auto-restart
npm run dev
```

**Configuration:**
- **Port**: 3000 (configurable via `PORT` env var)
- **Static files**: Served from project root
- **Cache**: Automatic cleanup every hour

### **4. Python Backend Setup**

```bash
# Navigate to backend directory
cd backend

# Install dependencies
pip install -r requirements.txt

# Start server
python python-app.py

# Or using gunicorn (production)
pip install gunicorn
gunicorn -w 4 -b 0.0.0.0:5000 python-app:app
```

**Configuration:**
- **Port**: 5000 (configurable via `PORT` env var)
- **Static files**: Served from project root
- **Cache**: Automatic cleanup every hour

### **5. Ruby Backend Setup**

```bash
# Navigate to backend directory
cd backend

# Install dependencies
bundle install

# Start server
ruby ruby-app.rb

# Or using rackup
rackup -p 4567
```

**Configuration:**
- **Port**: 4567 (configurable via `PORT` env var)
- **Static files**: Served from project root
- **Cache**: Automatic cleanup every hour

## 🔄 Switching Backends

To use a different backend, update your `assets/world-config.js`:

```javascript
window.WorldCSSConfig = {
    api: {
        // PHP Backend (default)
        locate: '/backend/world.php?action=locate',
        sun: '/backend/world.php?action=sun',
        weather: '/backend/world.php?action=weather',
        
        // Node.js Backend
        // locate: '/api/locate',
        // sun: '/api/sun',
        // weather: '/api/weather',
        
        // Python Backend
        // locate: '/api/locate',
        // sun: '/api/sun',
        // weather: '/api/weather',
        
        // Ruby Backend
        // locate: '/api/locate',
        // sun: '/api/sun',
        // weather: '/api/weather',
    }
};
```

## 🎯 Framework Integration

### **React + Node.js**
```jsx
// App.js
import React, { useEffect } from 'react';
import './assets/world.css';

function App() {
    useEffect(() => {
        // World.CSS automatically initializes
    }, []);

    return (
        <div className="app">
            <h1>My Ambient React App</h1>
        </div>
    );
}
```

### **Vue.js + Python**
```vue
<template>
    <div class="app">
        <h1>My Ambient Vue App</h1>
    </div>
</template>

<script>
export default {
    mounted() {
        // World.CSS automatically initializes
    }
}
</script>

<style>
@import './assets/world.css';
</style>
```

### **Angular + Ruby**
```typescript
// app.component.ts
import { Component, OnInit } from '@angular/core';

@Component({
    selector: 'app-root',
    template: '<div class="app"><h1>My Ambient Angular App</h1></div>',
    styleUrls: ['./assets/world.css']
})
export class AppComponent implements OnInit {
    ngOnInit() {
        // World.CSS automatically initializes
    }
}
```

## 🔍 Testing Your Backend

### **Health Check**
```bash
# Test if your backend is running
curl http://localhost:3000/api/health
```

### **Location Test**
```bash
# Test geolocation
curl http://localhost:3000/api/locate
```

### **Solar Calculation Test**
```bash
# Test solar calculation (New York coordinates)
curl "http://localhost:3000/api/sun?lat=40.7128&lng=-74.0060"
```

### **Weather Test**
```bash
# Test weather data (New York coordinates)
curl "http://localhost:3000/api/weather?lat=40.7128&lng=-74.0060"
```

## 🚀 Production Deployment

### **Node.js (Heroku)**
```bash
# Create Procfile
echo "web: node backend/node-server.js" > Procfile

# Deploy to Heroku
heroku create your-worldcss-app
git push heroku main
```

### **Python (Heroku)**
```bash
# Create Procfile
echo "web: gunicorn backend.python-app:app" > Procfile

# Deploy to Heroku
heroku create your-worldcss-app
git push heroku main
```

### **Ruby (Heroku)**
```bash
# Create Procfile
echo "web: ruby backend/ruby-app.rb" > Procfile

# Deploy to Heroku
heroku create your-worldcss-app
git push heroku main
```

## 🔧 Troubleshooting

### **Common Issues**

1. **Port already in use**
   ```bash
   # Change port in .env file
   PORT=3001
   ```

2. **API key not found**
   ```bash
   # Ensure .env file exists and has correct API key
   echo "OPENWEATHER_API_KEY=your_key_here" > .env
   ```

3. **CORS errors**
   - All backends include CORS headers
   - Check browser console for specific errors

4. **Cache issues**
   ```bash
   # Clear cache directory
   rm -rf backend/cache/*
   ```

### **Debug Mode**

Enable debug logging in `assets/world.js`:
```javascript
const DEBUG = true; // Set to true for console logging
```

## 📊 Performance Comparison

| Backend | Startup Time | Memory Usage | Cache | Auto-cleanup |
|---------|-------------|--------------|-------|--------------|
| PHP     | Fast        | Low          | ✅    | ✅           |
| Node.js | Medium      | Medium       | ✅    | ✅           |
| Python  | Medium      | Medium       | ✅    | ✅           |
| Ruby    | Fast        | Low          | ✅    | ✅           |

## 🎯 Choose Your Backend

- **PHP**: Best for shared hosting, simple setup
- **Node.js**: Best for modern web apps, React/Vue integration
- **Python**: Best for data science integration, Flask ecosystem
- **Ruby**: Best for Rails developers, simple syntax

All backends provide identical functionality - choose based on your existing tech stack! 