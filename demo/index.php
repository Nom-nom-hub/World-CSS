<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World.CSS - Living Ambient Themes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/world.css">
    <script src="../assets/world-config.js"></script>
    <script src="../assets/world.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Ensure logo has no box effects */
        .logo, .logo-container, .header {
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }

        html, body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }

        /* Premium Background with Particles */
        .demo-container {
            min-height: 100vh;
            position: relative;
            background: linear-gradient(135deg, 
                hsl(var(--background-hue), var(--background-saturation), var(--background-lightness)),
                hsl(calc(var(--background-hue) + 20), calc(var(--background-saturation) - 10), calc(var(--background-lightness) + 5)));
            transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Premium Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        /* Premium Header */
        .header {
            position: relative;
            z-index: 10;
            padding: 2rem 0;
            text-align: center;
            background: transparent;
            backdrop-filter: none;
            border-bottom: none;
        }

        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
            background: none;
            border: none;
            box-shadow: none;
            padding: 0;
        }
        
        .logo {
            font-size: 4.5rem;
            font-weight: 900;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-variant: small-caps;
            position: relative;
            z-index: 10;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.3),
                0 4px 8px rgba(0,0,0,0.2);
        }
        

        
        /* Sun Animation */
        .logo-sun {
            position: absolute;
            top: -20px;
            right: -30px;
            width: 60px;
            height: 60px;
            background: radial-gradient(circle at 30% 30%, #fff8e1 0%, #ffd700 25%, #ff8c00 50%, #ff4500 75%);
            border-radius: 50%;
            animation: sunRise 8s ease-in-out infinite;
            z-index: 5;
        }
        
        /* Moon Animation */
        .logo-moon {
            position: absolute;
            top: -15px;
            left: -25px;
            width: 50px;
            height: 50px;
            background: radial-gradient(circle at 70% 30%, #f0f8ff 0%, #e6e6fa 50%, #d3d3d3 100%);
            border-radius: 50%;
            animation: moonGlow 8s ease-in-out infinite;
            z-index: 5;
        }
        
        /* Stars Animation */
        .logo-stars {
            position: absolute;
            top: -40px;
            left: -60px;
            right: -60px;
            height: 80px;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #fff, transparent),
                radial-gradient(2px 2px at 40px 70px, #fff, transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(1px 1px at 130px 80px, #fff, transparent),
                radial-gradient(2px 2px at 160px 30px, #fff, transparent);
            background-repeat: repeat;
            animation: starsTwinkle 4s ease-in-out infinite;
            z-index: 3;
        }
        
        /* Clouds Animation */
        .logo-clouds {
            position: absolute;
            top: -30px;
            left: -80px;
            right: -80px;
            height: 60px;
            background-image: 
                radial-gradient(20px 20px at 30px 30px, rgba(255,255,255,0.8), transparent),
                radial-gradient(25px 25px at 80px 40px, rgba(255,255,255,0.6), transparent),
                radial-gradient(15px 15px at 120px 35px, rgba(255,255,255,0.7), transparent);
            background-repeat: repeat;
            animation: cloudsFloat 12s ease-in-out infinite;
            z-index: 4;
        }
        

        

        
        @keyframes sunRise {
            0%, 100% { 
                transform: translateY(0px) scale(1);
                opacity: 0.8;
            }
            50% { 
                transform: translateY(-5px) scale(1.05);
                opacity: 1;
            }
        }
        
        @keyframes moonGlow {
            0%, 100% { 
                transform: translateY(0px) scale(1);
                opacity: 0.8;
            }
            50% { 
                transform: translateY(-3px) scale(1.03);
                opacity: 1;
            }
        }
        
        @keyframes starsTwinkle {
            0%, 100% { 
                opacity: 0.4; 
                transform: scale(1);
            }
            50% { 
                opacity: 1; 
                transform: scale(1.1);
            }
        }
        
        @keyframes cloudsFloat {
            0%, 100% { 
                transform: translateX(-10px); 
                opacity: 0.7;
            }
            50% { 
                transform: translateX(10px); 
                opacity: 1;
            }
        }
        
        /* Phase-specific logo animations */
        .worldcss-night .logo-sun {
            opacity: 0;
            transform: translateY(20px) scale(0.8);
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-night .logo-moon {
            opacity: 1;
            transform: translateY(0px) scale(1);
            transition: all 1.5s ease-in-out;
            animation: moonGlow 4s ease-in-out infinite;
        }
        
        .worldcss-night .logo-stars {
            opacity: 1;
            animation: starsTwinkle 3s ease-in-out infinite;
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-night .logo-clouds {
            opacity: 0.2;
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-twilight .logo-sun {
            opacity: 0.6;
            transform: translateY(5px) scale(0.9);
            transition: all 1.5s ease-in-out;
            animation: sunRise 6s ease-in-out infinite;
        }
        
        .worldcss-twilight .logo-moon {
            opacity: 0.7;
            transform: translateY(0px) scale(1);
            transition: all 1.5s ease-in-out;
            animation: moonGlow 5s ease-in-out infinite;
        }
        
        .worldcss-twilight .logo-stars {
            opacity: 0.5;
            transition: all 1.5s ease-in-out;
            animation: starsTwinkle 4s ease-in-out infinite;
        }
        
        .worldcss-sunrise .logo-sun {
            opacity: 1;
            transform: translateY(0px) scale(1);
            transition: all 1.5s ease-in-out;
            animation: sunRise 5s ease-in-out infinite;
        }
        
        .worldcss-sunrise .logo-moon {
            opacity: 0.2;
            transform: translateY(15px) scale(0.8);
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-sunrise .logo-stars {
            opacity: 0.1;
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-day .logo-sun {
            opacity: 1;
            transform: translateY(0px) scale(1);
            transition: all 1.5s ease-in-out;
            animation: sunRise 6s ease-in-out infinite;
        }
        
        .worldcss-day .logo-moon {
            opacity: 0;
            transform: translateY(20px) scale(0.8);
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-day .logo-stars {
            opacity: 0;
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-day .logo-clouds {
            opacity: 0.6;
            transition: all 1.5s ease-in-out;
            animation: cloudsFloat 12s ease-in-out infinite;
        }
        
        .worldcss-noon .logo-sun {
            opacity: 1;
            transform: translateY(0px) scale(1);
            transition: all 1.5s ease-in-out;
            animation: sunRise 5s ease-in-out infinite;
        }
        
        .worldcss-noon .logo-clouds {
            opacity: 0.4;
            transition: all 1.5s ease-in-out;
            animation: cloudsFloat 15s ease-in-out infinite;
        }
        
        .worldcss-sunset .logo-sun {
            opacity: 0.8;
            transform: translateY(5px) scale(0.95);
            transition: all 1.5s ease-in-out;
            animation: sunRise 6s ease-in-out infinite;
        }
        
        .worldcss-sunset .logo-moon {
            opacity: 0.3;
            transform: translateY(10px) scale(0.9);
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-evening .logo-sun {
            opacity: 0.2;
            transform: translateY(15px) scale(0.8);
            transition: all 1.5s ease-in-out;
        }
        
        .worldcss-evening .logo-moon {
            opacity: 0.7;
            transform: translateY(0px) scale(1);
            transition: all 1.5s ease-in-out;
            animation: moonGlow 4s ease-in-out infinite;
        }
        
        .worldcss-evening .logo-stars {
            opacity: 0.6;
            transition: all 1.5s ease-in-out;
            animation: starsTwinkle 3s ease-in-out infinite;
        }
        
        /* Phase-specific logo text enhancements */
        .worldcss-night .logo {
            color: #ffffff;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.5),
                0 4px 8px rgba(0,0,0,0.3),
                0 0 20px rgba(255,255,255,0.3);
        }
        
        .worldcss-twilight .logo {
            color: #ffffff;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.4),
                0 4px 8px rgba(0,0,0,0.2),
                0 0 15px rgba(255,255,255,0.4);
        }
        
        .worldcss-sunrise .logo {
            color: #ffffff;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.3),
                0 4px 8px rgba(0,0,0,0.2),
                0 0 25px rgba(255,215,0,0.5);
        }
        
        .worldcss-day .logo {
            color: #000000;
            text-shadow: 
                0 2px 4px rgba(255,255,255,0.8),
                0 4px 8px rgba(255,255,255,0.6),
                0 0 20px rgba(255,255,255,0.4);
        }
        
        .worldcss-noon .logo {
            color: #000000;
            text-shadow: 
                0 2px 4px rgba(255,255,255,0.9),
                0 4px 8px rgba(255,255,255,0.7),
                0 0 25px rgba(150,200,255,0.6);
        }
        
        .worldcss-sunset .logo {
            color: #ffffff;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.4),
                0 4px 8px rgba(0,0,0,0.3),
                0 0 20px rgba(255,140,60,0.5);
        }
        
        .worldcss-evening .logo {
            color: #ffffff;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.5),
                0 4px 8px rgba(0,0,0,0.3),
                0 0 15px rgba(200,150,255,0.4);
        }
        
        /* Light theme enhancements for better visibility */
        .worldcss-light .logo-sun,
        .worldcss-day .logo-sun,
        .worldcss-noon .logo-sun {
            /* Enhanced visibility without box effects */
        }
        
        .worldcss-light .logo-moon,
        .worldcss-day .logo-moon,
        .worldcss-noon .logo-moon {
            /* Enhanced visibility without box effects */
        }
        
        .worldcss-light .logo-stars,
        .worldcss-day .logo-stars,
        .worldcss-noon .logo-stars {
            background-image: 
                radial-gradient(3px 3px at 20px 30px, #0066cc, transparent),
                radial-gradient(3px 3px at 40px 70px, #0066cc, transparent),
                radial-gradient(2px 2px at 90px 40px, #0066cc, transparent),
                radial-gradient(2px 2px at 130px 80px, #0066cc, transparent),
                radial-gradient(3px 3px at 160px 30px, #0066cc, transparent);
        }
        
        .worldcss-light .logo-clouds,
        .worldcss-day .logo-clouds,
        .worldcss-noon .logo-clouds {
            background-image: 
                radial-gradient(20px 20px at 30px 30px, rgba(100, 150, 255, 0.9), transparent),
                radial-gradient(25px 25px at 80px 40px, rgba(150, 200, 255, 0.8), transparent),
                radial-gradient(15px 15px at 120px 35px, rgba(120, 180, 255, 0.9), transparent);
        }
        
        /* Enhanced visibility for sunrise/sunset phases */
        .worldcss-sunrise .logo-sun,
        .worldcss-sunset .logo-sun {
            /* Enhanced visibility without box effects */
        }
        
        .worldcss-sunrise .logo-clouds,
        .worldcss-sunset .logo-clouds {
            background-image: 
                radial-gradient(20px 20px at 30px 30px, rgba(255, 200, 100, 0.9), transparent),
                radial-gradient(25px 25px at 80px 40px, rgba(255, 180, 120, 0.8), transparent),
                radial-gradient(15px 15px at 120px 35px, rgba(255, 160, 80, 0.9), transparent);
        }

        .tagline {
            font-size: 1.2rem;
            font-weight: 400;
            color: var(--text-secondary);
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .status {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-color);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            z-index: 100;
            transition: all 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Premium Timeline Section */
        .timeline-section {
            position: relative;
            z-index: 10;
            padding: 4rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .timeline-container {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(30px);
            border-radius: 32px;
            padding: 3rem;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 
                0 32px 64px rgba(0,0,0,0.1),
                inset 0 1px 0 rgba(255,255,255,0.2);
            transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .timeline-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-color);
            text-align: center;
            margin-bottom: 3rem;
            letter-spacing: -0.02em;
        }

        /* Ultra-Premium Timeline Bar */
        .timeline-bar {
            position: relative;
            height: 180px;
            background: 
                linear-gradient(135deg, 
                    rgba(255,255,255,0.12), 
                    rgba(255,255,255,0.06)),
                radial-gradient(circle at 50% 50%, 
                    rgba(255,255,255,0.1) 0%, 
                    transparent 70%);
            border-radius: 90px;
            margin: 3rem 0;
            cursor: pointer;
            border: 3px solid rgba(255,255,255,0.2);
            overflow: hidden;
            box-shadow: 
                0 32px 64px rgba(0,0,0,0.12),
                inset 0 1px 0 rgba(255,255,255,0.3),
                0 0 0 1px rgba(255,255,255,0.08),
                0 0 40px rgba(255,255,255,0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(20px);
        }

        .timeline-bar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: 
                linear-gradient(90deg, 
                    transparent 0%, 
                    rgba(255,255,255,0.1) 50%, 
                    transparent 100%);
            animation: timelineGlow 8s ease-in-out infinite;
        }

        @keyframes timelineGlow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.8; }
        }

        .timeline-bar:hover {
            transform: scale(1.03);
            box-shadow: 
                0 48px 96px rgba(0,0,0,0.18),
                inset 0 1px 0 rgba(255,255,255,0.4),
                0 0 0 1px rgba(255,255,255,0.15),
                0 0 60px rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.3);
        }

        .timeline-track {
            position: absolute;
            top: 50%;
            left: 20px;
            right: 20px;
            height: 12px;
            background: 
                linear-gradient(90deg, 
                    rgba(255,255,255,0.4), 
                    rgba(255,255,255,0.15), 
                    rgba(255,255,255,0.4));
            border-radius: 6px;
            transform: translateY(-50%);
            overflow: hidden;
            box-shadow: 
                inset 0 2px 4px rgba(0,0,0,0.1),
                0 1px 2px rgba(255,255,255,0.3);
        }

        .timeline-track::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255,255,255,0.8), 
                transparent);
            animation: shimmer 4s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .timeline-sun {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 120px;
            height: 120px;
            background: 
                radial-gradient(circle at 30% 30%, 
                    #ffffff 0%, 
                    #fff8e1 10%, 
                    #ffd700 25%, 
                    #ff8c00 50%, 
                    #ff4500 75%, 
                    #ff2200 100%);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 
                0 0 60px 30px rgba(255, 215, 0, 0.7),
                0 0 120px 60px rgba(255, 140, 60, 0.5),
                0 0 180px 90px rgba(255, 100, 0, 0.3),
                0 12px 48px rgba(0,0,0,0.4),
                inset 0 3px 12px rgba(255,255,255,0.95),
                inset 0 -3px 12px rgba(255,100,0,0.4);
            animation: sunPulse 6s ease-in-out infinite;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 4px solid rgba(255,255,255,0.8);
            filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.6));
        }

        .timeline-sun::after {
            content: '';
            position: absolute;
            top: -40px; left: -40px; right: -40px; bottom: -40px;
            background: 
                radial-gradient(circle, 
                    rgba(255, 255, 255, 0.4) 0%, 
                    rgba(255, 215, 0, 0.3) 20%, 
                    rgba(255, 140, 60, 0.2) 40%, 
                    rgba(255, 100, 0, 0.1) 60%, 
                    transparent 80%);
            border-radius: 50%;
            animation: sunRays 15s linear infinite;
            filter: blur(2px);
        }

        .timeline-sun:hover {
            transform: translate(-50%, -50%) scale(1.15);
            box-shadow: 
                0 0 80px 40px rgba(255, 215, 0, 0.8),
                0 0 160px 80px rgba(255, 140, 60, 0.6),
                0 0 240px 120px rgba(255, 100, 0, 0.4),
                0 16px 64px rgba(0,0,0,0.4);
            filter: drop-shadow(0 0 30px rgba(255, 215, 0, 0.8));
        }

        @keyframes sunPulse {
            0%, 100% { 
                box-shadow: 
                    0 0 40px 20px rgba(255, 215, 0, 0.6),
                    0 0 80px 40px rgba(255, 140, 60, 0.4),
                    0 0 120px 60px rgba(255, 100, 0, 0.2),
                    0 8px 32px rgba(0,0,0,0.3),
                    inset 0 2px 8px rgba(255,255,255,0.9),
                    inset 0 -2px 8px rgba(255,100,0,0.3);
                filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5));
            }
            50% { 
                box-shadow: 
                    0 0 80px 40px rgba(255, 215, 0, 0.8),
                    0 0 160px 80px rgba(255, 140, 60, 0.6),
                    0 0 240px 120px rgba(255, 100, 0, 0.4),
                    0 12px 48px rgba(0,0,0,0.4),
                    inset 0 4px 16px rgba(255,255,255,0.95),
                    inset 0 -4px 16px rgba(255,100,0,0.5);
                filter: drop-shadow(0 0 25px rgba(255, 215, 0, 0.7));
            }
        }

        @keyframes sunRays {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .timeline-label {
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translate(-50%, 0);
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-color);
            background: 
                linear-gradient(135deg, 
                    rgba(255,255,255,0.98), 
                    rgba(255,255,255,0.95)),
                radial-gradient(circle at 50% 50%, 
                    rgba(255,255,255,0.1) 0%, 
                    transparent 70%);
            backdrop-filter: blur(25px);
            border-radius: 25px;
            padding: 1.2rem 2.5rem;
            margin-top: 2rem;
            box-shadow: 
                0 20px 60px rgba(0,0,0,0.25),
                0 8px 24px rgba(0,0,0,0.15),
                inset 0 1px 0 rgba(255,255,255,0.9),
                0 0 0 1px rgba(255,255,255,0.2);
            pointer-events: none;
            z-index: 3;
            transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            border: 2px solid rgba(255,255,255,0.4);
            letter-spacing: 0.03em;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .worldcss-dark .timeline-label {
            background: linear-gradient(135deg, 
                rgba(0,0,0,0.95), 
                rgba(20,20,20,0.9));
            color: var(--text-color, #fff);
            border: 1px solid rgba(255,255,255,0.1);
        }

        /* Phase-specific timeline label styles for better visibility */
        .worldcss-twilight .timeline-label {
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.98), 
                rgba(255,255,255,0.95)) !important;
            color: #222 !important;
            border: 1px solid rgba(255,255,255,0.4) !important;
            box-shadow: 
                0 16px 48px rgba(0,0,0,0.25),
                0 4px 12px rgba(0,0,0,0.15),
                inset 0 1px 0 rgba(255,255,255,0.8) !important;
            animation: label-glow 3s ease-in-out infinite alternate;
        }

        @keyframes label-glow {
            0% { 
                box-shadow: 
                    0 16px 48px rgba(0,0,0,0.25),
                    0 4px 12px rgba(0,0,0,0.15),
                    inset 0 1px 0 rgba(255,255,255,0.8);
            }
            100% { 
                box-shadow: 
                    0 16px 48px rgba(0,0,0,0.25),
                    0 4px 12px rgba(0,0,0,0.15),
                    0 0 30px rgba(255,255,255,0.4),
                    inset 0 1px 0 rgba(255,255,255,0.8);
            }
        }

        .worldcss-night .timeline-label {
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.98), 
                rgba(255,255,255,0.95)) !important;
            color: #222 !important;
            border: 2px solid rgba(255,255,255,0.4) !important;
            box-shadow: 
                0 20px 60px rgba(0,0,0,0.3),
                0 8px 24px rgba(0,0,0,0.2),
                inset 0 1px 0 rgba(255,255,255,0.9) !important;
            animation: label-glow 3s ease-in-out infinite alternate;
        }

        /* Premium Phase Indicators */
        .timeline-phases {
            position: relative;
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            pointer-events: none;
            z-index: 2;
        }

        .phase-indicator {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: 
                radial-gradient(circle at 30% 30%, 
                    rgba(255,255,255,0.98), 
                    rgba(255,255,255,0.9));
            backdrop-filter: blur(20px);
            border: 3px solid rgba(255,255,255,0.9);
            box-shadow: 
                0 16px 40px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,1),
                0 0 0 2px rgba(255,255,255,0.5),
                0 0 20px rgba(255,255,255,0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            pointer-events: auto;
            z-index: 5;
        }

        .phase-indicator:hover {
            transform: scale(1.1);
            box-shadow: 
                0 12px 36px rgba(0,0,0,0.2),
                inset 0 1px 0 rgba(255,255,255,0.9);
        }

        .phase-indicator.active {
            background: 
                radial-gradient(circle at 30% 30%, 
                    rgba(255,255,255,1), 
                    rgba(255,255,255,0.98));
            box-shadow: 
                0 0 60px rgba(255,255,255,0.9),
                0 20px 50px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,1),
                0 0 0 3px rgba(255,255,255,0.8),
                0 0 30px rgba(255,255,255,0.4);
            animation: phasePulse 2s ease-in-out infinite;
            border: 3px solid rgba(255,255,255,1);
            z-index: 10;
        }

        @keyframes phasePulse {
            0%, 100% { 
                box-shadow: 
                    0 0 20px rgba(255,255,255,0.4),
                    0 8px 24px rgba(0,0,0,0.2),
                    inset 0 1px 0 rgba(255,255,255,1);
            }
            50% { 
                box-shadow: 
                    0 0 40px rgba(255,255,255,0.8),
                    0 8px 24px rgba(0,0,0,0.2),
                    inset 0 1px 0 rgba(255,255,255,1);
            }
        }

        .phase-dot {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            background: var(--accent-color, #007bff);
            box-shadow: 
                0 4px 12px rgba(0,0,0,0.3),
                0 0 0 1px rgba(255,255,255,0.5);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.8);
        }

        .phase-indicator.active .phase-dot {
            width: 18px;
            height: 18px;
            box-shadow: 
                0 0 25px rgba(255,255,255,0.9),
                0 4px 12px rgba(0,0,0,0.3),
                0 0 0 2px rgba(255,255,255,0.8);
            border: 2px solid rgba(255,255,255,1);
        }

        .phase-label {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-top: 0.5rem;
            font-size: 0.8rem;
            font-weight: 800;
            color: #000 !important;
            background: rgba(255,255,255,1) !important;
            backdrop-filter: blur(20px);
            padding: 0.5rem 1.2rem;
            border-radius: 15px;
            white-space: nowrap;
            box-shadow: 
                0 8px 25px rgba(0,0,0,0.3),
                0 3px 12px rgba(0,0,0,0.2),
                inset 0 1px 0 rgba(255,255,255,1),
                0 0 0 2px rgba(255,255,255,0.8);
            border: 3px solid rgba(255,255,255,1);
            transition: all 0.3s ease;
            opacity: 1;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
            z-index: 15;
            letter-spacing: 0.5px;
        }

        /* Dark theme overrides for better visibility */
        .worldcss-night .phase-label,
        .worldcss-twilight .phase-label,
        .worldcss-evening .phase-label {
            background: rgba(0,0,0,0.95) !important;
            color: #ffffff !important;
            border: 3px solid rgba(255,255,255,0.8);
            box-shadow: 
                0 8px 25px rgba(0,0,0,0.5),
                0 3px 12px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,0.3),
                0 0 0 2px rgba(255,255,255,0.6),
                0 0 20px rgba(255,255,255,0.2);
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }

        .worldcss-night .phase-label:hover,
        .worldcss-twilight .phase-label:hover,
        .worldcss-evening .phase-label:hover {
            background: rgba(0,0,0,1) !important;
            color: #ffffff !important;
            box-shadow: 
                0 12px 32px rgba(0,0,0,0.6),
                0 4px 16px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.5),
                0 0 0 3px rgba(255,255,255,0.8),
                0 0 30px rgba(255,255,255,0.3);
        }

        .worldcss-night .phase-label.active,
        .worldcss-twilight .phase-label.active,
        .worldcss-evening .phase-label.active {
            background: rgba(0,0,0,1) !important;
            color: #ffffff !important;
            box-shadow: 
                0 12px 32px rgba(0,0,0,0.6),
                0 4px 16px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.5),
                0 0 0 3px rgba(255,255,255,1),
                0 0 40px rgba(255,255,255,0.4);
            animation: labelGlow 2s ease-in-out infinite alternate;
        }

        .phase-indicator:hover .phase-label {
            opacity: 1;
            transform: translateX(-50%) scale(1.1);
            box-shadow: 
                0 12px 32px rgba(0,0,0,0.35),
                0 4px 16px rgba(0,0,0,0.25),
                inset 0 1px 0 rgba(255,255,255,1),
                0 0 0 3px rgba(255,255,255,0.9);
            background: rgba(255,255,255,1) !important;
        }

        .phase-indicator.active .phase-label {
            opacity: 1;
            background: rgba(255,255,255,1) !important;
            color: #000 !important;
            box-shadow: 
                0 12px 32px rgba(0,0,0,0.4),
                0 4px 16px rgba(0,0,0,0.3),
                inset 0 1px 0 rgba(255,255,255,1),
                0 0 0 3px rgba(255,255,255,1),
                0 0 30px rgba(255,255,255,0.5);
            border: 3px solid rgba(255,255,255,1);
            animation: labelGlow 2s ease-in-out infinite alternate;
            transform: translateX(-50%) scale(1.05);
        }

        @keyframes labelGlow {
            0% { 
                box-shadow: 
                    0 8px 24px rgba(0,0,0,0.3),
                    0 3px 12px rgba(0,0,0,0.2),
                    inset 0 1px 0 rgba(255,255,255,1);
            }
            100% { 
                box-shadow: 
                    0 8px 24px rgba(0,0,0,0.3),
                    0 3px 12px rgba(0,0,0,0.2),
                    0 0 30px rgba(255,255,255,0.5),
                    inset 0 1px 0 rgba(255,255,255,1);
            }
        }

        .worldcss-sunrise .timeline-label {
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.95), 
                rgba(255,255,255,0.9)) !important;
            color: #222 !important;
            border: 1px solid rgba(0,0,0,0.15) !important;
        }

        .worldcss-day .timeline-label {
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.95), 
                rgba(255,255,255,0.9)) !important;
            color: #222 !important;
            border: 1px solid rgba(0,0,0,0.15) !important;
        }

        .worldcss-noon .timeline-label {
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.95), 
                rgba(255,255,255,0.9)) !important;
            color: #222 !important;
            border: 1px solid rgba(0,0,0,0.15) !important;
        }

        .worldcss-sunset .timeline-label {
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.95), 
                rgba(255,255,255,0.9)) !important;
            color: #222 !important;
            border: 1px solid rgba(0,0,0,0.15) !important;
        }

        .worldcss-evening .timeline-label {
            background: linear-gradient(135deg, 
                rgba(255,255,255,0.98), 
                rgba(255,255,255,0.95)) !important;
            color: #222 !important;
            border: 1px solid rgba(255,255,255,0.4) !important;
            box-shadow: 
                0 16px 48px rgba(0,0,0,0.25),
                0 4px 12px rgba(0,0,0,0.15),
                inset 0 1px 0 rgba(255,255,255,0.8) !important;
            animation: label-glow 3s ease-in-out infinite alternate;
        }

        .timeline-sun:hover + .timeline-label,
        .timeline-label:hover {
            transform: translate(-50%, -8px);
            box-shadow: 
                0 24px 64px rgba(0,0,0,0.25),
                0 8px 16px rgba(0,0,0,0.15),
                inset 0 1px 0 rgba(255,255,255,0.9);
        }

        /* Premium Phase Indicators */
        .timeline-phases {
            position: relative;
            margin-top: 3rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
            height: 60px;
        }

        .phase-indicator {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1rem;
            border-radius: 16px;
            user-select: none;
        }

        .phase-indicator:hover {
            transform: translateY(-4px);
            background: rgba(255,255,255,0.15);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .phase-indicator:active {
            transform: translateY(-2px) scale(0.98);
        }

        .phase-indicator span {
            transition: all 0.3s ease;
        }

        .phase-indicator:hover span {
            color: var(--accent-color);
            font-weight: 700;
        }

        .phase-indicator:hover {
            transform: translateY(-4px);
            background: rgba(255,255,255,0.1);
        }

        .phase-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .phase-dot.night { background: linear-gradient(135deg, #4a148c, #7b1fa2); }
        .phase-dot.twilight { background: linear-gradient(135deg, #8e24aa, #ba68c8); }
        .phase-dot.sunrise { background: linear-gradient(135deg, #ff6f00, #ff9800); }
        .phase-dot.day { background: linear-gradient(135deg, #f57c00, #ffb74d); }
        .phase-dot.noon { background: linear-gradient(135deg, #1976d2, #42a5f5); }
        .phase-dot.sunset { background: linear-gradient(135deg, #ff5722, #ff7043); }
        .phase-dot.evening { background: linear-gradient(135deg, #6a1b9a, #9c27b0); }

        .phase-dot.active {
            transform: scale(2);
            box-shadow: 
                0 8px 24px rgba(0,0,0,0.4),
                0 0 0 3px rgba(255,255,255,0.8);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(2); }
            50% { transform: scale(2.2); }
        }

        /* Premium Color Palette */
        .color-palette {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .color-swatch {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(25px);
            border-radius: 28px;
            padding: 2.5rem;
            text-align: center;
            border: 2px solid rgba(255,255,255,0.25);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 16px 32px rgba(0,0,0,0.1),
                inset 0 1px 0 rgba(255,255,255,0.3);
        }

        .color-swatch::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), hsl(calc(var(--background-hue) + 60), 85%, 65%));
            border-radius: 28px 28px 0 0;
        }

        .color-swatch::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--accent-color);
            border-radius: 24px 24px 0 0;
        }

        .color-swatch:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px rgba(0,0,0,0.15);
        }

        .swatch-bg {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            margin: 0 auto 1rem;
            background: hsl(var(--background-hue), var(--background-saturation), var(--background-lightness));
            border: 2px solid rgba(255,255,255,0.3);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .swatch-accent {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            margin: 0 auto 1rem;
            background: var(--accent-color);
            border: 2px solid rgba(255,255,255,0.3);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .swatch-text {
            width: 80px;
            height: 60px;
            border-radius: 20px;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border: 2px solid rgba(255,255,255,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--text-color);
            box-shadow: 
                0 8px 24px rgba(0,0,0,0.2),
                inset 0 1px 0 rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .swatch-text::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), hsl(calc(var(--background-hue) + 60), 85%, 65%));
            border-radius: 20px 20px 0 0;
        }

        .text-sample {
            color: var(--text-color);
            font-weight: 700;
            margin: 0.5rem 0;
            font-size: 1rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            letter-spacing: 0.02em;
        }

        .worldcss-dark .swatch-text {
            background: linear-gradient(135deg, rgba(40,40,40,0.95), rgba(30,30,30,0.9));
            border-color: rgba(255,255,255,0.2);
            box-shadow: 
                0 8px 24px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.1);
        }

        .worldcss-dark .text-sample {
            color: var(--text-color);
        }

        .color-swatch h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }

        .color-swatch p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Premium Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .info-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(25px);
            border-radius: 28px;
            padding: 3rem;
            border: 2px solid rgba(255,255,255,0.25);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 20px 40px rgba(0,0,0,0.1),
                inset 0 1px 0 rgba(255,255,255,0.3);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), hsl(calc(var(--background-hue) + 60), 85%, 65%));
            border-radius: 28px 28px 0 0;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), hsl(calc(var(--background-hue) + 60), 85%, 65%));
            border-radius: 24px 24px 0 0;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px rgba(0,0,0,0.15);
            background: rgba(255,255,255,0.12);
        }

        .worldcss-dark .info-card {
            background: rgba(0,0,0,0.2);
            border-color: rgba(255,255,255,0.1);
        }

        .info-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 1rem;
            letter-spacing: 0.02em;
        }

        .info-card p {
            font-size: 1rem;
            color: var(--text-secondary);
            line-height: 1.6;
            font-weight: 400;
        }

        /* Premium Controls */
        .controls {
            text-align: center;
            margin: 3rem 0;
        }

        .btn {
            background: linear-gradient(135deg, var(--accent-color), hsl(calc(var(--background-hue) + 60), 85%, 65%));
            color: white;
            border: none;
            border-radius: 20px;
            padding: 1.2rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 0.5rem;
            box-shadow: 
                0 12px 32px rgba(0,0,0,0.2),
                0 4px 8px rgba(0,0,0,0.1);
            letter-spacing: 0.02em;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            font-family: 'Inter', sans-serif;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px rgba(0,0,0,0.25);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.15);
            color: var(--text-color);
            border: 1px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
        }

        .worldcss-dark .btn-secondary {
            background: rgba(0,0,0,0.3);
            color: var(--text-color);
            border-color: rgba(255,255,255,0.2);
        }

        /* Enhanced dark mode visibility for demo elements */
        .worldcss-dark .timeline-container {
            background: rgba(24,28,38,0.96) !important;
            border-color: #333 !important;
        }

        .worldcss-dark .color-swatch {
            background: rgba(24,28,38,0.9) !important;
            border-color: #444 !important;
        }

        .worldcss-dark .info-card {
            background: rgba(24,28,38,0.8) !important;
            border-color: #444 !important;
        }

        /* Phase-specific status styles for better visibility */
        .worldcss-twilight .status,
        .worldcss-night .status {
            background: rgba(255,255,255,0.95) !important;
            color: #222 !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2) !important;
            border: 1px solid rgba(255,255,255,0.3) !important;
        }

        /* Premium Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: opacity 0.5s ease-out;
        }

        .loading-spinner {
            width: 80px;
            height: 80px;
            border: 6px solid rgba(255,255,255,0.3);
            border-top: 6px solid white;
            border-radius: 50%;
            animation: spin 1.2s linear infinite;
            box-shadow: 0 0 30px rgba(255,255,255,0.3);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header { padding: 1.5rem; }
            .logo { font-size: 2.5rem; }
            .timeline-section { padding: 2rem 1rem; }
            .timeline-container { padding: 2rem; }
            .color-palette { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
            .info-grid { grid-template-columns: 1fr; }
            .timeline-bar { height: 80px; }
            .timeline-sun { width: 60px; height: 60px; }
        }

        /* Premium Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: opacity 0.5s ease-out;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Interactive Timeline */
        .timeline-interactive {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .time-tooltip {
            position: absolute;
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            pointer-events: none;
            z-index: 100;
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* Stars for dark phases */
        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
            opacity: 0;
            transition: opacity 2s ease-in-out;
        }

        .star {
            position: absolute;
            background: #ffffff;
            border-radius: 50%;
            animation: twinkle linear infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        /* Clouds for light phases */
        .clouds {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
            opacity: 0;
            transition: opacity 2s ease-in-out;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50px;
            animation: floatCloud linear infinite;
        }

        @keyframes floatCloud {
            0% { transform: translateX(-200px); }
            100% { transform: translateX(calc(100vw + 200px)); }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="demo-container">
        <div class="particles" id="particles"></div>
        
        <!-- Stars for dark phases -->
        <div class="stars" id="stars"></div>
        
        <!-- Clouds for light phases -->
        <div class="clouds" id="clouds"></div>
        
        
        
        <div class="header">
            <div class="logo-container">
                <div class="logo-sun"></div>
                <div class="logo-moon"></div>
                <div class="logo-stars"></div>
                <div class="logo-clouds"></div>
                <div class="logo">World.CSS</div>
            </div>
            <div class="tagline">Living Ambient Themes</div>
            <div class="mode-toggle">
                <button class="btn" onclick="toggleMode()">🌙 Toggle Mode</button>
            </div>
        </div>

        <div class="timeline-section">
            <div class="timeline-container">
                <h2 class="timeline-title">Real-Time Color Progression</h2>
                
                <div class="timeline-bar" id="timelineBar">
                    <div class="timeline-track"></div>
                    <div class="timeline-sun" id="timelineSun"></div>
                    <div class="timeline-label" id="timelineLabel">Loading...</div>
                    <div class="timeline-interactive" id="timelineInteractive"></div>
                    <div class="time-tooltip" id="timeTooltip"></div>
                </div>

                <div class="timeline-phases" id="timelinePhases">
                    <div class="phase-indicator" data-phase="night" data-elevation="-12">
                        <div class="phase-dot night"></div>
                        <div class="phase-label">Night</div>
                    </div>
                    <div class="phase-indicator" data-phase="twilight" data-elevation="-3">
                        <div class="phase-dot twilight"></div>
                        <div class="phase-label">Twilight</div>
                    </div>
                    <div class="phase-indicator" data-phase="sunrise" data-elevation="5">
                        <div class="phase-dot sunrise"></div>
                        <div class="phase-label">Sunrise</div>
                    </div>
                    <div class="phase-indicator" data-phase="day" data-elevation="20">
                        <div class="phase-dot day"></div>
                        <div class="phase-label">Day</div>
                    </div>
                    <div class="phase-indicator" data-phase="noon" data-elevation="45">
                        <div class="phase-dot noon"></div>
                        <div class="phase-label">Noon</div>
                    </div>
                    <div class="phase-indicator" data-phase="sunset" data-elevation="70">
                        <div class="phase-dot sunset"></div>
                        <div class="phase-label">Sunset</div>
                    </div>
                    <div class="phase-indicator" data-phase="evening" data-elevation="100">
                        <div class="phase-dot evening"></div>
                        <div class="phase-label">Evening</div>
                    </div>
                </div>

                <div class="color-palette" id="colorPalette">
                    <div class="color-swatch">
                        <div class="swatch-bg"></div>
                        <h4>Background</h4>
                        <p>var(--background-color)</p>
                    </div>
                    <div class="color-swatch">
                        <div class="swatch-accent"></div>
                        <h4>Accent</h4>
                        <p>var(--accent-color)</p>
                    </div>
                    <div class="color-swatch">
                        <div class="swatch-text">
                            <div class="text-sample">Sample Text</div>
                        </div>
                        <h4>Text</h4>
                        <p>var(--text-color)</p>
                    </div>
                </div>

                <div class="info-grid" id="infoGrid">
                    <div class="info-card">
                        <h3>🌍 Location</h3>
                        <p id="locationInfo">Detecting your location...</p>
                    </div>
                    <div class="info-card">
                        <h3>☀️ Solar Data</h3>
                        <p id="solarInfo">Calculating solar position...</p>
                    </div>
                    <div class="info-card">
                        <h3>🌤️ Weather</h3>
                        <p id="weatherInfo">Fetching weather data...</p>
                    </div>
                    <div class="info-card">
                        <h3>🕐 Current Time</h3>
                        <p id="themeInfo">Loading...</p>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
        // Create floating particles
        function createParticles() {
            const particles = document.getElementById('particles');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particles.appendChild(particle);
            }
        }

        // Create stars for dark phases
        function createStars() {
            const stars = document.getElementById('stars');
            stars.innerHTML = '';
            for (let i = 0; i < 100; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.width = Math.random() * 3 + 1 + 'px';
                star.style.height = star.style.width;
                star.style.animationDelay = Math.random() * 5 + 's';
                star.style.animationDuration = (Math.random() * 3 + 2) + 's';
                stars.appendChild(star);
            }
        }

        // Create clouds for light phases
        function createClouds() {
            const clouds = document.getElementById('clouds');
            clouds.innerHTML = '';
            for (let i = 0; i < 8; i++) {
                const cloud = document.createElement('div');
                cloud.className = 'cloud';
                cloud.style.top = Math.random() * 60 + 10 + '%';
                cloud.style.width = Math.random() * 100 + 50 + 'px';
                cloud.style.height = Math.random() * 30 + 20 + 'px';
                cloud.style.animationDelay = Math.random() * 20 + 's';
                cloud.style.animationDuration = (Math.random() * 30 + 60) + 's';
                clouds.appendChild(cloud);
            }
        }

        // Update background elements based on phase
        function updateBackgroundElements(phase) {
            const stars = document.getElementById('stars');
            const clouds = document.getElementById('clouds');
            
            // Dark phases: show stars
            if (phase === 'night' || phase === 'twilight' || phase === 'evening') {
                stars.style.opacity = '1';
                clouds.style.opacity = '0';
            }
            // Light phases: show clouds with appropriate opacity
            else if (phase === 'day' || phase === 'noon' || phase === 'sunrise' || phase === 'sunset') {
                stars.style.opacity = '0';
                clouds.style.opacity = '0.3'; // Much more subtle
            }
            // Transition phases: fade both
            else {
                stars.style.opacity = '0';
                clouds.style.opacity = '0';
            }
        }

        // Update timeline with premium animations
        function updateTimeline() {
            const state = window.worldcss?.state;
            
            if (!state || !state.sun) {
                return;
            }

            const sun = document.getElementById('timelineSun');
            const label = document.getElementById('timelineLabel');
            const phases = document.querySelectorAll('.phase-dot');
            
            const elev = state.sun.elevation;
            
            if (typeof elev === 'undefined' || elev === null) {
                return;
            }
            
            // Map elevation to intuitive day cycle positions
            let progress;
            if (elev < -6) {
                // Night: left side (0-10%)
                progress = Math.max(0, (elev + 18) / 12) * 0.1;
            } else if (elev < 0) {
                // Twilight: 10-20%
                progress = 0.1 + ((elev + 6) / 6) * 0.1;
            } else if (elev < 10) {
                // Sunrise: 20-35%
                progress = 0.2 + (elev / 10) * 0.15;
            } else if (elev < 30) {
                // Day: 35-50%
                progress = 0.35 + ((elev - 10) / 20) * 0.15;
            } else if (elev < 60) {
                // Noon: 50-65%
                progress = 0.5 + ((elev - 30) / 30) * 0.15;
            } else if (elev < 80) {
                // Sunset: 65-80%
                progress = 0.65 + ((elev - 60) / 20) * 0.15;
            } else {
                // Evening: 80-90%
                progress = 0.8 + Math.min(1, (elev - 80) / 40) * 0.1;
            }
            
            // Smooth sun movement
            sun.style.left = (progress * 80 + 10) + '%';
            
            // Determine phase
            let phase, phaseClass;
            if (elev < -6) { phase = 'Night'; phaseClass = 'night'; }
            else if (elev < -3) { phase = 'Twilight'; phaseClass = 'twilight'; }
            else if (elev < 5) { phase = 'Sunrise'; phaseClass = 'sunrise'; }
            else if (elev < 20) { phase = 'Day'; phaseClass = 'day'; }
            else if (elev < 45) { phase = 'Noon'; phaseClass = 'noon'; }
            else if (elev < 70) { phase = 'Sunset'; phaseClass = 'sunset'; }
            else if (elev < 100) { phase = 'Evening'; phaseClass = 'evening'; }
            else { phase = 'Night'; phaseClass = 'night'; }
            
            // Update label with current time
            const now = new Date();
            const currentTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            label.textContent = `${phase} (${elev.toFixed(1)}°) - ${currentTime}`;
            
            // Update phase indicators
            updatePhaseIndicators(phaseClass);
            
            // Update background elements based on phase
            updateBackgroundElements(phaseClass);
        }

        function updatePhaseIndicators(activePhase) {
            const phases = document.querySelectorAll('.phase-dot');
            
            phases.forEach(dot => {
                dot.classList.remove('active');
                if (dot.classList.contains(activePhase)) {
                    dot.classList.add('active');
                }
            });
        }

        function updateInfoCards() {
            const state = window.worldcss?.state;
            if (!state) return;

            // Current time info
            const now = new Date();
            const currentTime = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            });
            const currentDate = now.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });

            // Location info
            const locationInfo = document.getElementById('locationInfo');
            if (state.lat && state.lon) {
                locationInfo.textContent = `Lat: ${state.lat.toFixed(4)}\nLon: ${state.lon.toFixed(4)}`;
            }

            // Solar info
            const solarInfo = document.getElementById('solarInfo');
            if (state.sun) {
                const elev = state.sun.elevation;
                const azim = state.sun.azimuth;
                if (typeof elev === 'number' && typeof azim === 'number') {
                    solarInfo.textContent = `Elevation: ${elev.toFixed(1)}°\nAzimuth: ${azim.toFixed(1)}°`;
                } else {
                    solarInfo.textContent = 'Solar data unavailable';
                }
            }

            // Weather info
            const weatherInfo = document.getElementById('weatherInfo');
            if (state.weather) {
                const temp = state.weather.temp;
                const desc = state.weather.description;
                if (typeof temp === 'number' && desc) {
                    weatherInfo.textContent = `${temp.toFixed(1)}°C, ${desc}`;
                } else {
                    weatherInfo.textContent = 'Weather data unavailable';
                }
            }

            // Theme info with time
            const themeInfo = document.getElementById('themeInfo');
            themeInfo.innerHTML = `
                <div style="font-size: 1.2em; font-weight: 600; margin-bottom: 0.5rem;">${currentTime}</div>
                <div style="font-size: 0.9em; opacity: 0.8;">${currentDate}</div>
            `;
        }

        // Interactive timeline
        function setupTimelineInteractions() {
            const interactive = document.getElementById('timelineInteractive');
            const tooltip = document.getElementById('timeTooltip');
            
            interactive.addEventListener('mousemove', (e) => {
                const rect = interactive.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const progress = x / rect.width;
                const elev = (progress * 180) - 90;
                
                tooltip.style.left = e.clientX + 'px';
                tooltip.style.top = (e.clientY - 30) + 'px';
                tooltip.style.opacity = '1';
                tooltip.textContent = `Elevation: ${elev.toFixed(1)}°`;
            });
            
            interactive.addEventListener('mouseleave', () => {
                tooltip.style.opacity = '0';
            });
            
            interactive.addEventListener('click', (e) => {
                const rect = interactive.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const progress = x / rect.width;
                const elev = (progress * 180) - 90;
                
                simulateTimeChange(elev);
            });
            
            // Setup phase indicator clicks
            const phaseIndicators = document.querySelectorAll('.phase-indicator');
            phaseIndicators.forEach(indicator => {
                indicator.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent timeline click
                    const elevation = parseFloat(indicator.dataset.elevation);
                    const phase = indicator.dataset.phase;
                    
                    simulateTimeChange(elevation);
                    
                    // Add visual feedback
                    indicator.style.transform = 'translateY(-6px) scale(1.05)';
                    setTimeout(() => {
                        indicator.style.transform = '';
                    }, 200);
                });
            });
        }

        function getTimeFromElevation(elev) {
            // Convert elevation to time (approximate)
            const hour = Math.floor(((elev + 90) / 180) * 24);
            return `${hour.toString().padStart(2, '0')}:00`;
        }

        function simulateTimeChange(elev) {
            const state = window.worldcss?.state;
            if (!state || !state.sun) return;
            
            state.sun.elevation = elev;
            
            // Determine phase based on elevation - match the HTML data-elevation values
            let phase, phaseClass;
            if (elev <= -12) { phase = 'Night'; phaseClass = 'night'; }
            else if (elev <= -3) { phase = 'Twilight'; phaseClass = 'twilight'; }
            else if (elev <= 5) { phase = 'Sunrise'; phaseClass = 'sunrise'; }
            else if (elev <= 20) { phase = 'Day'; phaseClass = 'day'; }
            else if (elev <= 45) { phase = 'Noon'; phaseClass = 'noon'; }
            else if (elev <= 70) { phase = 'Sunset'; phaseClass = 'sunset'; }
            else if (elev <= 100) { phase = 'Evening'; phaseClass = 'evening'; }
            else { phase = 'Night'; phaseClass = 'night'; }
            
            // Update phase indicators
            updatePhaseIndicators(phaseClass);
            
            // Update background elements
            updateBackgroundElements(phaseClass);
            
            if (window.worldcss && window.worldcss.applyTheme) {
                window.worldcss.applyTheme();
                document.dispatchEvent(new CustomEvent('worldcss:update'));
            }
            
            updateTimeline();
            updateInfoCards();
        }

        function updateModeButton() {
            const button = document.querySelector('button[onclick="toggleMode()"]');
            if (button && window.worldcss && window.worldcss.state) {
                const currentMode = window.worldcss.state.mode;
                const modeIcons = { auto: '🌤️', light: '☀️', dark: '🌙' };
                const modeTexts = { auto: 'Auto', light: 'Light', dark: 'Dark' };
                button.innerHTML = `${modeIcons[currentMode]} ${modeTexts[currentMode]} Mode`;
            }
        }

        function toggleMode() {
            if (window.worldcss && window.worldcss.setMode) {
                const currentMode = window.worldcss.state.mode;
                const newMode = currentMode === 'auto' ? 'light' : 
                               currentMode === 'light' ? 'dark' : 'auto';
                
                window.worldcss.setMode(newMode);
                
                // Update the button text
                updateModeButton();
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            createParticles();
            createStars();
            createClouds();
            setupTimelineInteractions();
            
            // Hide loading overlay after theme is applied
            document.addEventListener('worldcss:update', () => {
                setTimeout(() => {
                    document.getElementById('loadingOverlay').style.opacity = '0';
                    setTimeout(() => {
                        document.getElementById('loadingOverlay').style.display = 'none';
                    }, 500);
                }, 1000);
                
                // Update mode button text
                updateModeButton();
            });
            
            // Update timeline and info cards
            setInterval(() => {
                updateTimeline();
                updateInfoCards();
            }, 1000);
        });
    </script>
</body>
</html>
