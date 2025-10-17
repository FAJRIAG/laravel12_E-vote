<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Temporarily Unavailable</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0e27;
            --bg-secondary: #141829;
            --accent-orange: #ff6b35;
            --accent-pink: #ff006e;
            --accent-yellow: #ffbe0b;
            --accent-cyan: #00f5ff;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b8;
            --surface: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Unique animated background */
        .noise {
            position: fixed;
            top: -50%;
            left: -50%;
            right: -50%;
            bottom: -50%;
            width: 200%;
            height: 200vh;
            background: transparent url('data:image/svg+xml,%3Csvg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="4" /%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)" opacity="0.05"/%3E%3C/svg%3E') repeat;
            animation: grain 8s steps(10) infinite;
            pointer-events: none;
        }

        @keyframes grain {
            0%, 100% { transform: translate(0, 0); }
            10% { transform: translate(-5%, -10%); }
            20% { transform: translate(-15%, 5%); }
            30% { transform: translate(7%, -25%); }
            40% { transform: translate(-5%, 25%); }
            50% { transform: translate(-15%, 10%); }
            60% { transform: translate(15%, 0%); }
            70% { transform: translate(0%, 15%); }
            80% { transform: translate(3%, 25%); }
            90% { transform: translate(-10%, 10%); }
        }

        /* Geometric shapes floating */
        .shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.4;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--accent-orange), transparent 70%);
            top: 10%;
            right: 5%;
            animation: morphing 15s infinite alternate;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }

        .shape-2 {
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, var(--accent-pink), transparent 70%);
            bottom: 15%;
            left: 10%;
            animation: morphing 12s infinite alternate-reverse;
            border-radius: 70% 30% 30% 70% / 60% 40% 60% 40%;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, var(--accent-yellow), transparent 70%);
            top: 60%;
            right: 30%;
            animation: morphing 18s infinite alternate;
            border-radius: 40% 60% 60% 40% / 70% 30% 70% 30%;
        }

        @keyframes morphing {
            0% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
                transform: rotate(0deg) scale(1);
            }
            50% {
                border-radius: 70% 30% 50% 50% / 30% 70% 30% 70%;
                transform: rotate(180deg) scale(1.2);
            }
            100% {
                border-radius: 50% 50% 30% 70% / 70% 30% 70% 30%;
                transform: rotate(360deg) scale(1);
            }
        }

        /* Main container */
        .container {
            position: relative;
            z-index: 10;
            max-width: 900px;
            width: 100%;
        }

        /* Asymmetric layout */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 60px;
            align-items: center;
            background: var(--surface);
            backdrop-filter: blur(40px);
            border: 1px solid var(--border);
            border-radius: 40px;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .content-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, 
                var(--accent-orange), 
                var(--accent-pink), 
                var(--accent-yellow), 
                var(--accent-cyan));
            animation: gradientShift 3s infinite;
        }

        @keyframes gradientShift {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Left side - Visual */
        .visual-side {
            position: relative;
        }

        .main-icon {
            width: 280px;
            height: 280px;
            position: relative;
            margin: 0 auto;
        }

        .icon-circle {
            position: absolute;
            border-radius: 50%;
            border: 2px solid;
        }

        .circle-1 {
            width: 100%;
            height: 100%;
            border-color: var(--accent-orange);
            animation: rotateClockwise 20s linear infinite;
            opacity: 0.3;
        }

        .circle-2 {
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            border-color: var(--accent-pink);
            animation: rotateCounterClockwise 15s linear infinite;
            opacity: 0.3;
        }

        .circle-3 {
            width: 60%;
            height: 60%;
            top: 20%;
            left: 20%;
            border-color: var(--accent-cyan);
            animation: rotateClockwise 10s linear infinite;
            opacity: 0.3;
        }

        @keyframes rotateClockwise {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotateCounterClockwise {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        .icon-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 80px;
            animation: levitate 4s ease-in-out infinite;
            filter: drop-shadow(0 10px 30px rgba(255, 107, 53, 0.3));
        }

        @keyframes levitate {
            0%, 100% { transform: translate(-50%, -50%) translateY(0); }
            50% { transform: translate(-50%, -50%) translateY(-20px); }
        }

        /* Right side - Content */
        .content-side {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-pink));
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 56px;
            font-weight: 700;
            line-height: 1.1;
            margin: 0;
            background: linear-gradient(135deg, 
                var(--text-primary), 
                var(--accent-orange), 
                var(--accent-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .description {
            font-size: 18px;
            line-height: 1.7;
            color: var(--text-secondary);
            font-weight: 400;
        }

        /* Unique progress indicator */
        .progress-container {
            margin: 30px 0;
        }

        .progress-dots {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .dot {
            width: 12px;
            height: 12px;
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: 50%;
            position: relative;
        }

        .dot.active {
            background: var(--accent-orange);
            border-color: var(--accent-orange);
            box-shadow: 0 0 20px var(--accent-orange);
        }

        .dot:nth-child(1).active { animation: dotPulse 1.5s infinite 0s; }
        .dot:nth-child(2).active { animation: dotPulse 1.5s infinite 0.2s; }
        .dot:nth-child(3).active { animation: dotPulse 1.5s infinite 0.4s; }
        .dot:nth-child(4).active { animation: dotPulse 1.5s infinite 0.6s; }
        .dot:nth-child(5).active { animation: dotPulse 1.5s infinite 0.8s; }

        @keyframes dotPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.5); }
        }

        .progress-label {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 12px;
            font-weight: 500;
        }

        /* Action buttons */
        .actions {
            display: flex;
            gap: 16px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 18px 32px;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-pink));
            color: white;
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 107, 53, 0.5);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--bg-secondary);
            border-color: var(--accent-orange);
        }

        /* Info cards */
        .info-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 40px;
            grid-column: 1 / -1;
        }

        .info-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-orange), var(--accent-pink));
            transform: translateX(-100%);
            transition: transform 0.3s;
        }

        .info-item:hover::before {
            transform: translateX(0);
        }

        .info-item:hover {
            transform: translateY(-5px);
            border-color: var(--accent-orange);
        }

        .info-icon {
            font-size: 32px;
            margin-bottom: 12px;
            display: block;
        }

        .info-label {
            font-size: 13px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Footer */
        .footer {
            grid-column: 1 / -1;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .footer-time {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
            font-feature-settings: 'tnum';
        }

        /* Responsive */
        @media (max-width: 968px) {
            .content-wrapper {
                grid-template-columns: 1fr;
                gap: 40px;
                padding: 40px 30px;
            }

            .main-icon {
                width: 200px;
                height: 200px;
            }

            .icon-center {
                font-size: 60px;
            }

            h1 {
                font-size: 42px;
            }

            .info-section {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>
<body>
    <div class="noise"></div>
    
    <div class="shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container">
        <div class="content-wrapper">
            <!-- Visual Side -->
            <div class="visual-side">
                <div class="main-icon">
                    <div class="icon-circle circle-1"></div>
                    <div class="icon-circle circle-2"></div>
                    <div class="icon-circle circle-3"></div>
                    <div class="icon-center">⚙️</div>
                </div>
            </div>

            <!-- Content Side -->
            <div class="content-side">
                <div class="status-pill">
                    <span class="status-dot"></span>
                    <span>Under Maintenance</span>
                </div>

                <h1>Sedang Kami Tingkatkan</h1>

                <p class="description">
                    Kami sedang melakukan peningkatan sistem untuk memberikan performa dan pengalaman yang lebih baik. Mohon tunggu sebentar, kami akan segera kembali.
                </p>

                <div class="progress-container">
                    <div class="progress-dots">
                        <div class="dot active"></div>
                        <div class="dot active"></div>
                        <div class="dot active"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                    <div class="progress-label" id="statusText">Memproses pembaruan sistem...</div>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="location.reload()">
                        <span>↻</span>
                        <span>Coba Lagi</span>
                    </button>
                    <button class="btn btn-secondary" onclick="window.history.back()">
                        <span>←</span>
                        <span>Kembali</span>
                    </button>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <div class="info-item">
                    <span class="info-icon">⏱️</span>
                    <div class="info-label">Estimasi</div>
                    <div class="info-value">~15 menit</div>
                </div>
                <div class="info-item">
                    <span class="info-icon">🔒</span>
                    <div class="info-label">Keamanan Data</div>
                    <div class="info-value">100% Aman</div>
                </div>
                <div class="info-item">
                    <span class="info-icon">🔄</span>
                    <div class="info-label">Auto Reload</div>
                    <div class="info-value" id="countdown">30s</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-time">
                    <span>⏰</span>
                    <span id="currentTime">Memuat...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update time
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
        
        updateTime();
        setInterval(updateTime, 1000);

        // Auto refresh countdown
        let countdown = 30;
        const countdownEl = document.getElementById('countdown');
        const statusEl = document.getElementById('statusText');
        
        setInterval(() => {
            countdown--;
            countdownEl.textContent = `${countdown}s`;
            
            if (countdown <= 10) {
                statusEl.textContent = `Refresh otomatis dalam ${countdown} detik...`;
            }
            
            if (countdown <= 0) {
                location.reload();
            }
        }, 1000);

        // Animate progress dots
        let activeDots = 3;
        setInterval(() => {
            const dots = document.querySelectorAll('.dot');
            dots.forEach(dot => dot.classList.remove('active'));
            
            activeDots = (activeDots % 5) + 1;
            for (let i = 0; i < activeDots; i++) {
                dots[i].classList.add('active');
            }
        }, 2000);
    </script>
</body>
</html>