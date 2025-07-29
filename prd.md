 Product Requirements Document – Daylight.CSS v2
🛠️ Project Overview
Daylight.CSS is a dynamic theming SDK and service that adjusts a website’s visual theme in real-time based on the actual daylight conditions and weather at the user’s physical location. It provides developers with a plug-and-play solution to deliver a living, responsive design that evolves with nature — no user interaction required.

🎯 Goal
Enable any website — static, PHP-based, or React/Next.js — to dynamically shift its color palette and ambient design in response to solar position, time of day, and weather, using a lightweight backend (PHP) and zero-dependency frontend (JS/CSS).

📦 Deliverables
1. PHP Backend API
Located at /backend/daylight.php. Must handle:

GET /?action=locate → Returns user's lat/lon via IP lookup

GET /?action=sun → Returns solar elevation, azimuth, golden hour times

GET /?action=weather&lat=..&lon=.. → Returns current weather conditions

Optional: caching layer to avoid API overuse (file-based or memcached)

2. Frontend SDK (daylight.js + daylight.css)
Detects user’s browser geolocation (with IP fallback to backend)

Fetches sun position and weather from backend

Maps environmental data → HSL/opacity/tone variables in :root

Smooth transitions based on real-time values (CSS variables + animation)

Manual override control: Auto / Light / Dark / Custom

Stores user choice in localStorage

Works standalone with any HTML page

3. Demo App
A self-hosted /demo/index.php or /demo/index.html

UI controls for simulating different times of day, lat/lon, and weather conditions

Debug panel to inspect computed values (solar elevation, hue shift, weather status)

4. Documentation
/README.md with:

How it works

How to install and integrate

API structure

Weather + solar sources

Config options

🧠 Key Features
Feature	Description
Real-time theming	Uses live solar + weather data to drive site colors
Progressive transitions	Not just dark/light — smooth ambient drift throughout the day
Weather effects	Modulates tone, fog, brightness, color temp based on live conditions
Moon phases (v2)	Adds ambient changes based on lunar data
Manual override	Floating toggle: Auto / Light / Dark, with persistence
Offline fallback	Works with cached defaults when offline
Framework-agnostic	Works with PHP, static HTML, React, Next.js, etc.

🧪 API Details (Backend)
Endpoint: GET /backend/daylight.php?action=locate
Uses IP lookup to return: { lat, lon, city, region, country }

Endpoint: GET /backend/daylight.php?action=sun&lat=..&lon=..
Returns: { elevation, azimuth, sunrise, sunset, golden_hour_start, golden_hour_end }

Endpoint: GET /backend/daylight.php?action=weather&lat=..&lon=..
Uses OpenWeatherMap or similar

Returns: { condition, clouds, temp, fog, rain, snow, visibility }

🌈 CSS/JS Theme Logic
Dynamic CSS Variables
css
Copy
Edit
:root {
  --background-hue: 200;
  --background-lightness: 95%;
  --accent-color: hsl(var(--background-hue), 70%, 50%);
}
Time → Theme Mapping
Solar elevation drives:

Hue rotation

Lightness curve

Overlay opacity

Weather drives:

Fog/smoke filters

Rain overlays

Snow sparkle or blur

✅ Acceptance Criteria
Requirement	Status
Full day-night transition based on sun	✅ Required
Fallback IP location lookup	✅ Required
Live weather integration (w/ fallback)	✅ Required
Manual override toggle UI	✅ Required
Cross-framework integration (React etc.)	✅ Required
Offline-safe CSS defaults	✅ Required
Moon phase extension (planned)	🔜 Optional

🧩 Integration Example
In HTML:

html
Copy
Edit
<link rel="stylesheet" href="/assets/daylight.css">
<script src="/assets/daylight.js"></script>
In Next.js (Client Side):

js
Copy
Edit
useEffect(() => {
  import('/assets/daylight.js').then(() => {
    window.Daylight.init();
  });
}, []);
📍 Future Enhancements (Post-v2)
Moon phase overlays

Seasonal palettes

AI-enhanced scene matching (via optional OpenAI/Gemini plugin)

Chrome extension to theme the entire browser

API for user mood → ambient styling