# World.CSS - Living Ambient Themes

A revolutionary CSS/JavaScript system that creates real-time, environment-based theming for websites. World.CSS automatically adjusts colors based on your location, time of day, weather conditions, and solar position to create truly living, ambient themes.

**Works with any web technology** - React, Vue, Angular, Node.js, Python, Ruby, .NET, or plain HTML!

## 🌟 Features

### **Real-Time Environmental Theming**
- **Solar-based colors** that change throughout the day
- **Weather integration** with OpenWeatherMap API
- **Geolocation detection** for accurate local conditions
- **Smooth transitions** between different times of day
- **Automatic updates** every 5 minutes

### **Seven Distinct Time Phases**
- **🌙 Night** (-6° and below) - Deep indigo with subtle highlights
- **🌆 Twilight** (-6° to -3°) - Purple gradients with soft lighting
- **🌅 Sunrise** (-3° to 5°) - Warm oranges and yellows
- **☀️ Day** (5° to 20°) - Bright, vibrant daylight colors
- **🌞 Noon** (20° to 45°) - Intense blue sky and bright accents
- **🌇 Sunset** (45° to 70°) - Rich oranges and warm tones
- **🌃 Evening** (70° to 100°) - Deep purples and evening atmosphere

### **Advanced Features**
- **HSL Color Model** for smooth color interpolation
- **WCAG Compliant** contrast ratios for accessibility
- **Manual Override Modes** (Auto/Light/Dark)
- **Customizable Configuration** via `world-config.js`
- **Responsive Design** that works on all devices
- **Professional UI Components** with glassmorphism effects

## 🚀 Quick Start

### **Prerequisites**
- Web server (Apache, Nginx, etc.)
- **Backend API** (PHP, Node.js, Python, Ruby, .NET, etc.)
- OpenWeatherMap API key (free)

### **Installation**

1. **Clone or download** the World.CSS files to your web server
2. **Get an OpenWeatherMap API key** from [openweathermap.org](https://openweathermap.org/api)
3. **Set up your backend API** (see examples below)
4. **Include World.CSS** in your HTML:
   ```html
   <link rel="stylesheet" href="assets/world.css">
   <script src="assets/world-config.js"></script>
   <script src="assets/world.js"></script>
   ```

### **Basic Usage**

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My World.CSS Site</title>
    <link rel="stylesheet" href="assets/world.css">
    <script src="assets/world-config.js"></script>
    <script src="assets/world.js"></script>
</head>
<body>
    <h1>Welcome to my ambient website!</h1>
    <p>This page automatically adapts to your environment.</p>
</body>
</html>
```

## 🔧 Backend API Options

World.CSS works with **any backend technology**. Here are examples:

### **PHP Backend** (Included)
```php
// backend/world.php - Already included
// Provides: locate, sun, weather endpoints
```

### **Node.js Backend**
```javascript
// server.js
const express = require('express');
const axios = require('axios');

app.get('/api/locate', async (req, res) => {
    // IP geolocation logic
});

app.get('/api/sun', async (req, res) => {
    // Solar calculation logic
});

app.get('/api/weather', async (req, res) => {
    // Weather API logic
});
```

### **Python Backend**
```python
# app.py
from flask import Flask, jsonify
import requests

app = Flask(__name__)

@app.route('/api/locate')
def locate():
    # IP geolocation logic
    pass

@app.route('/api/sun')
def sun():
    # Solar calculation logic
    pass

@app.route('/api/weather')
def weather():
    # Weather API logic
    pass
```

### **Ruby Backend**
```ruby
# app.rb
require 'sinatra'
require 'net/http'

get '/api/locate' do
  # IP geolocation logic
end

get '/api/sun' do
  # Solar calculation logic
end

get '/api/weather' do
  # Weather API logic
end
```

### **Custom API Endpoints**
You can also use any existing API or create your own endpoints. World.CSS just needs:
- **Geolocation** (latitude/longitude)
- **Solar data** (elevation/azimuth)
- **Weather data** (temperature, conditions)

## 📁 Project Structure

```
World-CSS/
├── assets/
│   ├── world.css          # Main CSS with themes and variables
│   ├── world.js           # Core JavaScript engine
│   └── world-config.js    # User configuration file
├── backend/
│   ├── world.php          # PHP API endpoints (example)
│   ├── config.php         # API configuration
│   ├── cache/             # Cached API responses
│   └── clean-cache.php    # Cache cleanup script
├── demo/
│   └── index.php          # Interactive demo page
├── example.html           # Basic integration example
├── .gitignore             # Git ignore rules
└── README.md              # This file
```

## ⚙️ Configuration

### **Customizing Colors and Behavior**

Edit `assets/world-config.js` to customize:

```javascript
window.WorldCSSConfig = {
    // API endpoints (change to match your backend)
    api: {
        locate: '/backend/world.php?action=locate',
        sun: '/backend/world.php?action=sun',
        weather: '/backend/world.php?action=weather'
    },
    
    // Color phases
    phases: {
        night: {
            background: { hue: 240, saturation: 70, lightness: 15 },
            accent: { hue: 200, saturation: 80, lightness: 60 },
            text: '#ffffff'
        },
        // ... other phases
    }
};
```

### **Framework Integration Examples**

#### **React**
```jsx
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

#### **Vue.js**
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

#### **Angular**
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

## 🎯 Demo Pages

### **Interactive Demo** (`demo/index.php`)
- **Real-time timeline** showing current solar position
- **Clickable phase indicators** for testing different times
- **Live color palette** showing current theme colors
- **Information cards** with location, weather, and time data
- **Mode toggle** (Auto/Light/Dark)
- **Beautiful glassmorphism UI** with particle animations

### **Basic Example** (`example.html`)
- **Simple integration** example
- **Timeline visualization** of day progression
- **Color swatches** showing current theme
- **Information display** with solar and weather data

## 🌐 Browser Support

- **Chrome/Edge** 90+
- **Firefox** 88+
- **Safari** 14+
- **Mobile browsers** (iOS Safari, Chrome Mobile)

## 🔒 Privacy & Security

- **No tracking** - All data stays on your server
- **IP geolocation** - Uses your server's IP for location
- **Secure API calls** - All external requests go through your backend
- **Cache system** - Reduces API calls and improves performance

## 🚀 Performance

- **Efficient caching** - API responses cached for 1 hour
- **Smooth transitions** - CSS transitions for color changes
- **Minimal JavaScript** - Lightweight core engine
- **Optimized updates** - Only updates when necessary

## 🛠️ Troubleshooting

### **Common Issues**

1. **Colors not changing**
   - Check browser console for errors
   - Verify API endpoints are accessible
   - Ensure OpenWeatherMap API key is valid

2. **Location not detected**
   - Check server's IP geolocation
   - Verify your backend API is accessible
   - Check your backend's geolocation service

3. **Weather not loading**
   - Verify OpenWeatherMap API key
   - Check API rate limits
   - Ensure your backend can make external requests

### **Debug Mode**

Enable debug logging in `assets/world.js`:

```javascript
const DEBUG = true; // Set to true for console logging
```

## 📝 License

This project is open source and available under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/Nom-nom-hub/World-CSS/issues)
- **Documentation**: [Wiki](https://github.com/Nom-nom-hub/World-CSS/wiki)
- **Demo**: [Live Demo](demo/index.php)

---

**World.CSS** - Making the web feel alive, one sunrise at a time. 🌅
