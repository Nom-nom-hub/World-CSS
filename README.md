# World.CSS - Living Ambient Themes

A revolutionary CSS/JavaScript system that creates real-time, environment-based theming for websites. World.CSS automatically adjusts colors based on your location, time of day, weather conditions, and solar position to create truly living, ambient themes.

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
- PHP 7.4+ with cURL extension
- OpenWeatherMap API key (free)

### **Installation**

1. **Clone or download** the World.CSS files to your web server
2. **Get an OpenWeatherMap API key** from [openweathermap.org](https://openweathermap.org/api)
3. **Configure the API key** in `backend/config.php`:
   ```php
   <?php
   define('OPENWEATHER_API_KEY', 'your_api_key_here');
   ?>
   ```
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

## 📁 Project Structure

```
World-CSS/
├── assets/
│   ├── world.css          # Main CSS with themes and variables
│   ├── world.js           # Core JavaScript engine
│   └── world-config.js    # User configuration file
├── backend/
│   ├── world.php          # PHP API endpoints
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
  // Phase-specific colors
  phases: {
    night: {
      background: { hue: 240, saturation: 85, lightness: 15 },
      accent: { hue: 200, saturation: 85, lightness: 65 },
      text: { primary: "#ffffff", secondary: "#e0e0e0" }
    },
    // ... other phases
  },
  
  // Manual themes
  themes: {
    light: { /* light mode colors */ },
    dark: { /* dark mode colors */ }
  },
  
  // Behavior settings
  behavior: {
    updateInterval: 5,        // Update frequency (minutes)
    transitionDuration: 1.2,  // Transition duration (seconds)
    features: {
      weatherEffects: true,
      locationDetection: true,
      automaticUpdates: true,
      smoothTransitions: true
    }
  }
};
```

### **API Configuration**

#### **Option 1: Environment File (Recommended)**

Create a `.env` file in your project root:

```bash
# Copy the example file
cp env.example .env

# Edit the .env file
nano .env
```

Add your API key to `.env`:

```env
# OpenWeatherMap API Key (required)
OPENWEATHER_API_KEY=your_openweathermap_api_key_here

# Cache duration in seconds (optional)
CACHE_DURATION=3600
```

#### **Option 2: Direct Configuration**

Configure your OpenWeatherMap API in `backend/config.php`:

```php
<?php
define('OPENWEATHER_API_KEY', 'your_api_key_here');
define('CACHE_DURATION', 3600); // Cache for 1 hour
?>
```

#### **Environment Variable Priority**

The system checks for API keys in this order:
1. **Environment variables** (`$_ENV['OPENWEATHER_API_KEY']`)
2. **`.env` file** (project root)
3. **`config.php` constants** (backward compatibility)

## 🎨 CSS Custom Properties

World.CSS provides these CSS custom properties:

```css
:root {
  --background-hue: 210;           /* Background color hue */
  --background-saturation: 60;     /* Background saturation */
  --background-lightness: 92;      /* Background lightness */
  --accent-color: hsl(200,85%,65%); /* Accent color */
  --text-color: #222222;          /* Primary text color */
  --text-secondary: #666666;      /* Secondary text color */
}
```

### **Phase-Specific Classes**

The system adds classes to the document root:

- `.worldcss-night` - Night phase styling
- `.worldcss-twilight` - Twilight phase styling
- `.worldcss-sunrise` - Sunrise phase styling
- `.worldcss-day` - Day phase styling
- `.worldcss-noon` - Noon phase styling
- `.worldcss-sunset` - Sunset phase styling
- `.worldcss-evening` - Evening phase styling

## 🔧 API Endpoints

### **Backend Services**

The PHP backend provides these endpoints:

- **`/backend/world.php?action=locate`** - IP-based geolocation
- **`/backend/world.php?action=sun`** - Solar elevation/azimuth calculation
- **`/backend/world.php?action=weather`** - Weather data from OpenWeatherMap

### **Response Format**

```json
{
  "success": true,
  "data": {
    "elevation": 15.2,
    "azimuth": 180.5,
    "phase": "day",
    "weather": {
      "temp": 22,
      "clouds": 30,
      "description": "partly cloudy"
    }
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
   - Verify `backend/world.php` is accessible
   - Check PHP cURL extension is enabled

3. **Weather not loading**
   - Verify OpenWeatherMap API key
   - Check API rate limits
   - Ensure cache directory is writable

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
4. Test thoroughly
5. Submit a pull request

## 📞 Support

For questions, issues, or feature requests:
- Check the demo pages for examples
- Review the configuration options
- Open an issue on GitHub

## 🎨 Customization Examples

### **Custom Phase Colors**

```javascript
// In world-config.js
phases: {
  night: {
    background: { hue: 260, saturation: 90, lightness: 10 },
    accent: { hue: 180, saturation: 85, lightness: 70 },
    text: { primary: "#ffffff", secondary: "#e8e8e8" }
  }
}
```

### **Custom Weather Effects**

```javascript
weather: {
  rainy: {
    lightnessAdjustment: -25,
    saturationAdjustment: -30,
    overlay: "rgba(100,100,120,0.4)"
  }
}
```

### **Custom CSS Variables**

```css
:root {
  --my-custom-color: hsl(var(--background-hue), 85%, 65%);
  --my-border-radius: 12px;
  --my-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
```

---

**World.CSS** - Making the web feel alive, one sunrise at a time. 🌅
