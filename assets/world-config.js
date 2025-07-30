// World.CSS Configuration File
// Customize your website's colors, themes, and behavior

window.WorldCSSConfig = {
  // ===== TIME-BASED COLOR PHASES =====
  phases: {
    night: {
      name: "Night",
      background: { hue: 250, saturation: 90, lightness: 12 },
      accent: { hue: 220, saturation: 85, lightness: 75 },
      text: { primary: "#ffffff", secondary: "#e8e8e8" },
      overlay: "rgba(30,20,100,0.85)",
      description: "Rich indigo night sky with stars"
    },
    
    twilight: {
      name: "Twilight", 
      background: { hue: 280, saturation: 75, lightness: 25 },
      accent: { hue: 60, saturation: 85, lightness: 75 },
      text: { primary: "#ffffff", secondary: "#f0f0f0" },
      overlay: "rgba(100,50,180,0.7)",
      description: "Purple-pink dawn/dusk atmosphere"
    },
    
    sunrise: {
      name: "Sunrise",
      background: { hue: 25, saturation: 95, lightness: 55 },
      accent: { hue: 35, saturation: 90, lightness: 60 },
      text: { primary: "#000000", secondary: "#222222" },
      overlay: "rgba(255,150,80,0.3)",
      description: "Warm orange-pink morning light"
    },
    
    day: {
      name: "Daylight",
      background: { hue: 50, saturation: 85, lightness: 65 },
      accent: { hue: 40, saturation: 90, lightness: 55 },
      text: { primary: "#000000", secondary: "#222222" },
      overlay: "rgba(255,230,150,0.2)",
      description: "Bright golden daylight"
    },
    
    noon: {
      name: "Noon",
      background: { hue: 210, saturation: 70, lightness: 70 },
      accent: { hue: 200, saturation: 80, lightness: 60 },
      text: { primary: "#000000", secondary: "#333333" },
      overlay: "rgba(150,200,255,0.15)",
      description: "Bright cool blue sky at peak sun"
    },
    
    sunset: {
      name: "Sunset",
      background: { hue: 20, saturation: 100, lightness: 65 },
      accent: { hue: 30, saturation: 95, lightness: 70 },
      text: { primary: "#000000", secondary: "#222222" },
      overlay: "rgba(255,120,60,0.4)",
      description: "Vibrant orange-red sunset glow"
    },
    
    evening: {
      name: "Evening",
      background: { hue: 260, saturation: 80, lightness: 20 },
      accent: { hue: 50, saturation: 85, lightness: 70 },
      text: { primary: "#ffffff", secondary: "#f0f0f0" },
      overlay: "rgba(80,40,160,0.8)",
      description: "Deep purple evening atmosphere"
    }
  },

  // ===== WEATHER EFFECTS =====
  weather: {
    cloudy: {
      lightnessAdjustment: -15,
      saturationAdjustment: -20,
      description: "Darker, less saturated colors when cloudy"
    },
    rainy: {
      lightnessAdjustment: -25,
      saturationAdjustment: -30,
      overlay: "rgba(100,100,120,0.4)",
      description: "Much darker, muted colors for rain"
    },
    sunny: {
      lightnessAdjustment: 5,
      saturationAdjustment: 10,
      description: "Brighter, more vibrant colors for clear sky"
    }
  },

  // ===== MANUAL THEMES =====
  themes: {
    light: {
      name: "Light Mode",
      background: { hue: 55, saturation: 90, lightness: 85 },
      accent: { hue: 50, saturation: 85, lightness: 65 },
      text: { primary: "#000000", secondary: "#333333" },
      description: "Bright, clean light theme"
    },
    
    dark: {
      name: "Dark Mode", 
      background: { hue: 240, saturation: 85, lightness: 8 },
      accent: { hue: 200, saturation: 85, lightness: 65 },
      text: { primary: "#ffffff", secondary: "#e0e0e0" },
      description: "Deep, rich dark theme"
    },
    
    custom: {
      name: "Custom Theme",
      background: { hue: 180, saturation: 70, lightness: 50 },
      accent: { hue: 45, saturation: 85, lightness: 65 },
      text: { primary: "#000000", secondary: "#333333" },
      description: "Your custom theme - modify these values!"
    }
  },

  // ===== BEHAVIOR SETTINGS =====
  behavior: {
    // Update frequency in minutes
    updateInterval: 5,
    
    // Transition duration in seconds
    transitionDuration: 1.2,
    
    // Enable/disable features
    features: {
      weatherEffects: true,
      locationDetection: true,
      automaticUpdates: true,
      smoothTransitions: true
    },
    
    // Location fallback (if geolocation fails)
    fallbackLocation: {
      lat: 40.7128,  // New York
      lon: -74.0060
    }
  },

  // ===== CUSTOM CSS VARIABLES =====
  // Add your own CSS custom properties here
  customVariables: {
    '--my-primary-color': 'hsl(200, 85%, 65%)',
    '--my-secondary-color': 'hsl(45, 85%, 65%)',
    '--my-border-radius': '12px',
    '--my-shadow': '0 4px 12px rgba(0,0,0,0.1)',
    '--my-font-family': '"Inter", sans-serif'
  },

  // ===== ADVANCED SETTINGS =====
  advanced: {
    // Minimum contrast ratio for text (WCAG AA = 4.5:1)
    minimumContrastRatio: 4.5,
    
    // Force specific text colors (override automatic calculation)
    forceTextColors: false,
    forcedTextColor: null, // Set to "#000000" or "#ffffff" to force
    
    // Custom phase transitions
    phaseTransitions: {
      nightToTwilight: -6,    // degrees
      twilightToSunrise: 0,   // degrees  
      sunriseToDay: 15,       // degrees
      dayToNoon: 45,          // degrees
      noonToDay: 45,          // degrees (reverse)
      dayToSunrise: 15,       // degrees (reverse)
      sunriseToTwilight: 0,   // degrees (reverse)
      twilightToNight: -6     // degrees (reverse)
    }
  }
};

// ===== USAGE EXAMPLES =====
/*
// To customize colors, modify the phases object above:

// Example: Make night phase more purple
WorldCSSConfig.phases.night.background.hue = 270;
WorldCSSConfig.phases.night.background.saturation = 90;

// Example: Make sunrise more red
WorldCSSConfig.phases.sunrise.background.hue = 15;
WorldCSSConfig.phases.sunrise.background.saturation = 100;

// Example: Add your own custom phase
WorldCSSConfig.phases.midnight = {
  name: "Midnight",
  background: { hue: 250, saturation: 90, lightness: 5 },
  accent: { hue: 180, saturation: 85, lightness: 65 },
  text: { primary: "#ffffff", secondary: "#e0e0e0" },
  overlay: "rgba(10,20,80,0.9)",
  description: "Deepest night phase"
};

// Example: Customize weather effects
WorldCSSConfig.weather.snowy = {
  lightnessAdjustment: -10,
  saturationAdjustment: -15,
  overlay: "rgba(255,255,255,0.3)",
  description: "Bright, white overlay for snow"
};

// Example: Add custom CSS variables
WorldCSSConfig.customVariables['--my-gradient'] = 'linear-gradient(45deg, var(--accent-color), var(--background-color))';
WorldCSSConfig.customVariables['--my-spacing'] = '1.5rem';
*/ 