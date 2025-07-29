// World.CSS JS Engine - Living Ambient System
(function(){
const API = window.location.pathname.includes('/demo/') ? '../backend/world.php' : 'backend/world.php';
const CSS_ROOT = document.documentElement;
const LS_KEY = 'worldcss_mode';
let state = {
    mode: 'auto',
    lat: null,
    lon: null,
    sun: null,
    weather: null,
    colors: {},
};

// Add .sky-overlay for atmospheric/phase effects
function initSkyOverlay() {
  if (!document.querySelector('.sky-overlay') && document.body) {
    const overlay = document.createElement('div');
    overlay.className = 'sky-overlay';
    overlay.style = 'pointer-events:none;position:fixed;inset:0;z-index:0;transition:all 1s ease-in-out;mix-blend-mode:screen;';
    document.body.appendChild(overlay);
  }
}
function setOverlay(color, opacity) {
  const overlay = document.querySelector('.sky-overlay');
  overlay.style.background = color;
  overlay.style.opacity = opacity;
}

function setMode(mode) {
    state.mode = mode;
    localStorage.setItem(LS_KEY, mode);
    applyTheme();
}
function getMode() {
    return localStorage.getItem(LS_KEY) || 'auto';
}
function fetchJSON(url) { return fetch(url).then(r => r.json()); }
function detectLocation() {
    return new Promise((resolve) => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => resolve({lat: pos.coords.latitude, lon: pos.coords.longitude}),
                () => fallbackLocate(resolve),
                {timeout: 5000}
            );
        } else { fallbackLocate(resolve); }
    });
}
function fallbackLocate(cb) {
    fetchJSON(API + '?action=locate').then(data => {
        if (data && data.lat && data.lon) cb({lat: data.lat, lon: data.lon});
        else cb({lat: 0, lon: 0});
    });
}
function fetchSun(lat, lon, time) {
    let url = `${API}?action=sun&lat=${lat}&lon=${lon}`;
    if (time) url += `&time=${time}`;
    return fetchJSON(url);
}
function fetchWeather(lat, lon) {
    return fetchJSON(`${API}?action=weather&lat=${lat}&lon=${lon}`);
}
function interpolate(a, b, t) { return a + (b - a) * t; }
function clamp(x, min, max) { return Math.max(min, Math.min(max, x)); }

// Phase-based mapping for solar elevation
function themeFromSunWeather(sun, weather) {
    let elev = sun.elevation;
    let clouds = weather.clouds ? weather.clouds.all/100 : 0;
    // Define phases
    let phase, t;
    if (elev < -6) { // Night
        phase = 'night'; t = clamp((elev + 18) / 12, 0, 1); // -18 to -6
    } else if (elev < 0) { // Twilight
        phase = 'twilight'; t = (elev + 6) / 6;
    } else if (elev < 10) { // Sunrise
        phase = 'sunrise'; t = elev / 10;
    } else if (elev < 30) { // Daylight
        phase = 'day'; t = (elev - 10) / 20;
    } else if (elev < 60) { // Noon
        phase = 'noon'; t = (elev - 30) / 30;
    } else if (elev < 80) { // Sunset
        phase = 'sunset'; t = (elev - 60) / 20;
    } else { // Evening
        phase = 'evening'; t = clamp((elev - 80) / 40, 0, 1);
    }
    // Use configuration or fallback to defaults
    const config = window.WorldCSSConfig || {};
    const phases = config.phases || {
              night: { background: { hue: 250, saturation: 90, lightness: 12 }, accent: { hue: 220, saturation: 85, lightness: 75 }, text: { primary: "#ffffff", secondary: "#e8e8e8" } },
        twilight: { background: { hue: 280, saturation: 75, lightness: 25 }, accent: { hue: 60, saturation: 85, lightness: 75 }, text: { primary: "#ffffff", secondary: "#f0f0f0" } },
        sunrise: { background: { hue: 25, saturation: 95, lightness: 70 }, accent: { hue: 35, saturation: 90, lightness: 70 }, text: { primary: "#000000", secondary: "#222222" } },
        day: { background: { hue: 50, saturation: 85, lightness: 85 }, accent: { hue: 40, saturation: 90, lightness: 65 }, text: { primary: "#000000", secondary: "#222222" } },
        noon: { background: { hue: 210, saturation: 70, lightness: 90 }, accent: { hue: 200, saturation: 80, lightness: 70 }, text: { primary: "#000000", secondary: "#333333" } },
        sunset: { background: { hue: 20, saturation: 100, lightness: 65 }, accent: { hue: 30, saturation: 95, lightness: 70 }, text: { primary: "#000000", secondary: "#222222" } },
        evening: { background: { hue: 260, saturation: 80, lightness: 20 }, accent: { hue: 50, saturation: 85, lightness: 70 }, text: { primary: "#ffffff", secondary: "#f0f0f0" } }
    };
    
    // Convert config format to internal format
    const stops = {
      night: { hue: phases.night.background.hue, light: phases.night.background.lightness, sat: phases.night.background.saturation, overlay: 'rgba(30,20,80,0.85)', overlayO: 0.85, accent: phases.night.accent.hue, text: phases.night.text },
      twilight: { hue: phases.twilight.background.hue, light: phases.twilight.background.lightness, sat: phases.twilight.background.saturation, overlay: 'rgba(120,60,200,0.6)', overlayO: 0.6, accent: phases.twilight.accent.hue, text: phases.twilight.text },
      sunrise: { hue: phases.sunrise.background.hue, light: phases.sunrise.background.lightness, sat: phases.sunrise.background.saturation, overlay: 'rgba(255,140,60,0.3)', overlayO: 0.3, accent: phases.sunrise.accent.hue, text: phases.sunrise.text },
      day: { hue: phases.day.background.hue, light: phases.day.background.lightness, sat: phases.day.background.saturation, overlay: 'rgba(255,200,100,0.2)', overlayO: 0.2, accent: phases.day.accent.hue, text: phases.day.text },
      noon: { hue: phases.noon.background.hue, light: phases.noon.background.lightness, sat: phases.noon.background.saturation, overlay: 'rgba(150,200,255,0.15)', overlayO: 0.15, accent: phases.noon.accent.hue, text: phases.noon.text },
      sunset: { hue: phases.sunset.background.hue, light: phases.sunset.background.lightness, sat: phases.sunset.background.saturation, overlay: 'rgba(255,100,50,0.4)', overlayO: 0.4, accent: phases.sunset.accent.hue, text: phases.sunset.text },
      evening: { hue: phases.evening.background.hue, light: phases.evening.background.lightness, sat: phases.evening.background.saturation, overlay: 'rgba(80,40,160,0.7)', overlayO: 0.7, accent: phases.evening.accent.hue, text: phases.evening.text }
    };
    // Interpolate between phases
    let from, to, phaseT;
    if (phase === 'night') { from = stops.night; to = stops.twilight; phaseT = t; }
    else if (phase === 'twilight') { from = stops.twilight; to = stops.sunrise; phaseT = t; }
    else if (phase === 'sunrise') { from = stops.sunrise; to = stops.day; phaseT = t; }
    else if (phase === 'day') { from = stops.day; to = stops.noon; phaseT = t; }
    else if (phase === 'noon') { from = stops.noon; to = stops.sunset; phaseT = t; }
    else if (phase === 'sunset') { from = stops.sunset; to = stops.evening; phaseT = t; }
    else { from = stops.evening; to = stops.night; phaseT = t; }
    let hue = interpolate(from.hue, to.hue, phaseT);
    let lightness = interpolate(from.light, to.light, phaseT);
    let sat = interpolate(from.sat, to.sat, phaseT);
    // Weather overlays
    if (clouds > 0.5) {
      lightness = interpolate(lightness, lightness-20, clouds);
      sat = interpolate(sat, sat-30, clouds);
    }
    // Text color (perceived luminance)
    function hslToRgb(h, s, l) {
      s /= 100; l /= 100;
      let c = (1 - Math.abs(2 * l - 1)) * s;
      let x = c * (1 - Math.abs((h / 60) % 2 - 1));
      let m = l - c/2;
      let r=0,g=0,b=0;
      if (h < 60) { r=c; g=x; b=0; }
      else if (h < 120) { r=x; g=c; b=0; }
      else if (h < 180) { r=0; g=c; b=x; }
      else if (h < 240) { r=0; g=x; b=c; }
      else if (h < 300) { r=x; g=0; b=c; }
      else { r=c; g=0; b=x; }
      return [Math.round((r+m)*255), Math.round((g+m)*255), Math.round((b+m)*255)];
    }
    function luminance(r,g,b) {
      let a = [r,g,b].map(function(v) {
        v /= 255;
        return v <= 0.03928 ? v/12.92 : Math.pow((v+0.055)/1.055,2.4);
      });
      return 0.2126*a[0] + 0.7152*a[1] + 0.0722*a[2];
    }
    let rgb = hslToRgb(hue, sat, lightness);
    let lum = luminance(rgb[0], rgb[1], rgb[2]);
    // Use configured text colors or calculate based on contrast
    let text, textSecondary;
    
    // Check if forced text colors are enabled
    if (config.advanced && config.advanced.forceTextColors && config.advanced.forcedTextColor) {
      text = config.advanced.forcedTextColor;
      textSecondary = config.advanced.forcedTextColor === '#000000' ? '#333333' : '#e0e0e0';
    } else {
      // Use configured text colors for the current phase
      let currentPhase = phase === 'night' ? 'night' : 
                        phase === 'twilight' ? 'twilight' : 
                        phase === 'sunrise' ? 'sunrise' : 
                        phase === 'day' ? 'day' : 'noon';
      
      if (stops[currentPhase] && stops[currentPhase].text) {
        text = stops[currentPhase].text.primary;
        textSecondary = stops[currentPhase].text.secondary;
      } else {
        // Fallback to contrast calculation
        function getContrastColor(backgroundLum) {
          let blackLum = 0.2126 * Math.pow(0/255, 2.4) + 0.7152 * Math.pow(0/255, 2.4) + 0.0722 * Math.pow(0/255, 2.4);
          let whiteLum = 0.2126 * Math.pow(255/255, 2.4) + 0.7152 * Math.pow(255/255, 2.4) + 0.0722 * Math.pow(255/255, 2.4);
          
          let blackContrast = (backgroundLum + 0.05) / (blackLum + 0.05);
          let whiteContrast = (whiteLum + 0.05) / (backgroundLum + 0.05);
          
          if (blackContrast > whiteContrast) {
            return { text: '#000000', secondary: '#333333' };
          } else {
            return { text: '#ffffff', secondary: '#e0e0e0' };
          }
        }
        
        let contrastColors = getContrastColor(lum);
        text = contrastColors.text;
        textSecondary = contrastColors.secondary;
      }
    }
    // Overlay
    let overlayColor = interpolateColor(from.overlay, to.overlay, phaseT);
    let overlayO = interpolate(from.overlayO, to.overlayO, phaseT);
    setOverlay(overlayColor, overlayO);
    // Enhanced accent color interpolation using phase-specific values
    let accentHue = interpolate(from.accent, to.accent, phaseT);
    return {
        '--background-hue': hue,
        '--background-lightness': lightness,
        '--background-saturation': sat,
        '--text-color': text,
        '--text-secondary': textSecondary,
        '--accent-color': `hsl(${accentHue},85%,65%)`,
        '--atmosphere-overlay-opacity': overlayO,
        '--worldcss-night': elev < -6 ? '1' : '0',
    };
}
// Helper: interpolate rgba color strings
function interpolateColor(a, b, t) {
  function parse(c) {
    let m = c.match(/rgba?\((\d+), ?(\d+), ?(\d+)(?:, ?([\d.]+))?\)/);
    return m ? [parseInt(m[1]),parseInt(m[2]),parseInt(m[3]),parseFloat(m[4]||1)] : [0,0,0,1];
  }
  let ca = parse(a), cb = parse(b);
  return `rgba(${Math.round(interpolate(ca[0],cb[0],t))},${Math.round(interpolate(ca[1],cb[1],t))},${Math.round(interpolate(ca[2],cb[2],t))},${interpolate(ca[3],cb[3],t)})`;
}

function applyTheme() {
    let mode = state.mode;
    if (mode === 'auto' && state.sun && state.weather) {
        state.colors = themeFromSunWeather(state.sun, state.weather);
    } else if (mode === 'light') {
        state.colors = {
            '--background-hue': 55,
            '--background-lightness': 85,
            '--background-saturation': 90,
            '--text-color': '#000000',
            '--text-secondary': '#333333',
            '--accent-color': 'hsl(50,85%,65%)',
            '--worldcss-night': '0',
        };
        setOverlay('rgba(255,255,200,0.15)', 0.15);
    } else if (mode === 'dark') {
        state.colors = {
            '--background-hue': 240,
            '--background-lightness': 8,
            '--background-saturation': 85,
            '--text-color': '#ffffff',
            '--text-secondary': '#e0e0e0',
            '--accent-color': 'hsl(200,85%,65%)',
            '--worldcss-night': '1',
        };
        setOverlay('rgba(20,30,100,0.8)', 0.8);
    }
    for (let k in state.colors) {
        CSS_ROOT.style.setProperty(k, state.colors[k]);
    }
    CSS_ROOT.classList.toggle('worldcss-dark', state.colors['--worldcss-night']==='1');
    CSS_ROOT.classList.toggle('worldcss-light', state.colors['--worldcss-night']==='0');
    
    // Add phase-specific classes for better styling control
    CSS_ROOT.classList.remove('worldcss-night', 'worldcss-twilight', 'worldcss-sunrise', 'worldcss-day', 'worldcss-noon', 'worldcss-sunset', 'worldcss-evening');
    if (state.sun && state.sun.elevation !== undefined) {
        const elev = state.sun.elevation;
        if (elev < -6) CSS_ROOT.classList.add('worldcss-night');
        else if (elev < 0) CSS_ROOT.classList.add('worldcss-twilight');
        else if (elev < 10) CSS_ROOT.classList.add('worldcss-sunrise');
        else if (elev < 30) CSS_ROOT.classList.add('worldcss-day');
        else if (elev < 60) CSS_ROOT.classList.add('worldcss-noon');
        else if (elev < 80) CSS_ROOT.classList.add('worldcss-sunset');
        else CSS_ROOT.classList.add('worldcss-evening');
    }
}

function updateAll() {
    detectLocation().then(({lat, lon}) => {
        state.lat = lat;
        state.lon = lon;
        Promise.all([
            fetchSun(lat, lon),
            fetchWeather(lat, lon)
        ]).then(([sun, weather]) => {
            state.sun = sun;
            state.weather = weather;
            applyTheme();
            document.dispatchEvent(new CustomEvent('worldcss:update', {detail: state}));
        });
    });
}

function addToggleUI() {
    if (!document.body) return;
    
    let ui = document.createElement('div');
    ui.className = 'worldcss-toggle';
    ui.innerHTML = `<button data-mode="auto">Auto</button><button data-mode="light">Light</button><button data-mode="dark">Dark</button>`;
    ui.style = 'position:fixed;bottom:1em;right:1em;z-index:9999;display:flex;gap:4px;';
    ui.querySelectorAll('button').forEach(btn => {
        btn.onclick = () => { setMode(btn.dataset.mode); };
    });
    document.body.appendChild(ui);
    
    // Update active button state
    function updateActiveButton() {
        ui.querySelectorAll('button').forEach(btn => {
            btn.classList.remove('active');
        });
        const activeBtn = ui.querySelector(`[data-mode="${state.mode}"]`);
        if (activeBtn) activeBtn.classList.add('active');
    }
    
    // Update active state initially and on mode changes
    updateActiveButton();
    document.addEventListener('worldcss:update', updateActiveButton);
}

// Live progression: update every 5 minutes
setInterval(() => { if(state.mode==='auto') updateAll(); }, 5*60*1000);

// Init
function initWorldCSS() {
    initSkyOverlay();
    state.mode = getMode();
    document.documentElement.classList.add('worldcss-loading');
    updateAll();
    window.worldcss = { state, setMode, updateAll, applyTheme };
    // addToggleUI(); // Disabled floating toggle UI
}

// Remove loading class after first theme applied
function removeLoading() {
  document.documentElement.classList.remove('worldcss-loading');
  document.removeEventListener('worldcss:update', removeLoading);
}
document.addEventListener('worldcss:update', removeLoading);

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWorldCSS);
} else {
    initWorldCSS();
}
})();
