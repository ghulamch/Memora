<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MEMORA - Welcome</title>
    <link rel="icon" href="{{ asset('memora_logo.png') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600&family=Great+Vibes&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #ccb36c;
            --primary-dark: #c8ae68;
            --gold: #d4af37;
            --gold-light: #f4e4c1;
            --cream: #faf8f3;
            --dark: #2c2416;
            --shadow: rgba(204, 179, 108, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #faf8f3 0%, #f5f1e8 50%, #faf8f3 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Decorations */
        .bg-decoration {
            position: fixed;
            pointer-events: none;
            z-index: 0;
            width: 100%;
            height: 100%;
        }

        .floral-corner {
            position: absolute;
            opacity: 0.12;
            color: var(--primary);
        }

        .floral-top-left {
            top: -50px;
            left: -50px;
            font-size: 300px;
            transform: rotate(-15deg);
        }

        .floral-top-right {
            top: -30px;
            right: -30px;
            font-size: 250px;
            transform: rotate(15deg);
        }

        .floral-bottom-left {
            bottom: -50px;
            left: -30px;
            font-size: 280px;
            transform: rotate(25deg);
        }

        .floral-bottom-right {
            bottom: -40px;
            right: -50px;
            font-size: 260px;
            transform: rotate(-20deg);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .ornament {
            position: absolute;
            font-size: 40px;
            color: var(--primary);
            opacity: 0.2;
            animation: float 6s ease-in-out infinite;
        }

        .ornament-1 { top: 15%; left: 10%; animation-delay: 0s; }
        .ornament-2 { top: 25%; right: 15%; animation-delay: 1s; }
        .ornament-3 { top: 60%; left: 8%; animation-delay: 2s; }
        .ornament-4 { top: 70%; right: 12%; animation-delay: 1.5s; }
        .ornament-5 { top: 40%; left: 5%; animation-delay: 0.5s; }
        .ornament-6 { top: 50%; right: 8%; animation-delay: 2.5s; }

        /* Main Container */
        .landing-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .landing-container {
            width: 100%;
            max-width: 1600px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: start;
        }

        /* QR Section */
        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: sticky;
            top: 2rem;
        }

        .logo-section {
            margin-bottom: 2rem;
        }

        .logo-img {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 12px var(--shadow));
            margin-bottom: 1rem;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 0.3em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .brand-tagline {
            font-family: 'Great Vibes', cursive;
            font-size: 2.5rem;
            color: var(--primary-dark);
            margin-bottom: 2rem;
        }

        .divider {
            width: 300px;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--primary), transparent);
            margin: 2rem auto;
            position: relative;
        }

        .divider::before {
            content: '❖';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--cream);
            padding: 0 1rem;
            color: var(--primary);
            font-size: 1.2rem;
        }

        /* QR Code Container */
        .qr-container {
            position: relative;
            display: inline-block;
            padding: 2rem;
        }

        .qr-frame {
            position: relative;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                0 10px 40px rgba(204, 179, 108, 0.3),
                0 0 0 1px rgba(204, 179, 108, 0.1),
                inset 0 0 0 8px white,
                inset 0 0 0 10px var(--primary);
        }

        .qr-frame::before,
        .qr-frame::after {
            content: '✦';
            position: absolute;
            font-size: 2rem;
            color: var(--primary);
        }

        .qr-frame::before {
            top: -15px;
            left: -15px;
        }

        .qr-frame::after {
            bottom: -15px;
            right: -15px;
        }

        .corner-decoration {
            position: absolute;
            color: var(--primary);
            font-size: 3rem;
            opacity: 0.4;
        }

        .corner-tl { top: -20px; left: -20px; }
        .corner-tr { top: -20px; right: -20px; transform: scaleX(-1); }
        .corner-bl { bottom: -20px; left: -20px; transform: scaleY(-1); }
        .corner-br { bottom: -20px; right: -20px; transform: scale(-1); }

        #qrcode {
            display: block;
            width: 350px;
            height: 350px;
            background: white;
        }

        .qr-instruction {
            margin-top: 2rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            color: var(--dark);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: center;
        }

        .qr-instruction i {
            color: var(--primary);
            font-size: 1.5rem;
        }

        /* Content Section */
        .content-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* Session Card */
        .session-card {
            background: white;
            border-radius: 30px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border: 2px solid var(--gold-light);
            position: relative;
            overflow: hidden;
        }

        .session-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--gold), var(--primary));
        }

        .session-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .session-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--gold));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 5px 20px var(--shadow);
        }

        .session-info h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .session-time {
            font-size: 1rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .session-time i {
            color: var(--primary);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px dashed var(--gold-light);
        }

        .stat-box {
            text-align: center;
        }

        .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            display: block;
            margin-bottom: 0.3rem;
            transition: all 0.3s ease;
        }

        .stat-value.updating {
            transform: scale(1.1);
            color: var(--gold);
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 500;
        }

        /* Photos Grid */
        .photos-showcase {
            background: white;
            border-radius: 30px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border: 2px solid var(--gold-light);
        }

        .showcase-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .showcase-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .showcase-title i {
            color: var(--primary);
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border: 2px solid rgba(239, 68, 68, 0.3);
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #dc2626;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.9); }
        }

        /* Photos Grid */
        .photos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .photo-item {
            position: relative;
            aspect-ratio: 4/3;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .photo-item.new-photo {
            animation: newPhotoAnimation 0.8s ease;
        }

        @keyframes newPhotoAnimation {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(20px);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .photo-item:hover img {
            transform: scale(1.05);
        }

        .photo-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            color: white;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .photo-item:hover .photo-overlay {
            transform: translateY(0);
        }

        .photo-overlay i {
            color: var(--gold);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--gold-light);
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
        }

        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid var(--gold-light);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .landing-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            
            .qr-section {
                position: relative;
                top: 0;
            }
            
            .brand-name {
                font-size: 3rem;
            }
            
            .brand-tagline {
                font-size: 2rem;
            }
            
            #qrcode {
                width: 300px;
                height: 300px;
            }
        }

        @media (max-width: 768px) {
            .landing-wrapper {
                padding: 1rem;
            }
            
            .brand-name {
                font-size: 2rem;
                letter-spacing: 0.2em;
            }
            
            .brand-tagline {
                font-size: 1.5rem;
            }
            
            #qrcode {
                width: 250px;
                height: 250px;
            }
            
            .qr-frame {
                padding: 1.5rem;
            }
            
            .stats-grid {
                gap: 1rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .photos-grid {
                grid-template-columns: 1fr;
            }
            
            .ornament {
                display: none;
            }
            
            .floral-corner {
                opacity: 0.08;
            }
        }

        @media (max-width: 480px) {
            .brand-name {
                font-size: 1.5rem;
                letter-spacing: 0.15em;
            }
            
            .brand-tagline {
                font-size: 1.2rem;
            }
            
            #qrcode {
                width: 220px;
                height: 220px;
            }
            
            .session-card,
            .photos-showcase {
                padding: 1.5rem;
            }
            
            .session-info h2 {
                font-size: 1.5rem;
            }
            
            .stat-value {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background Decorations -->
    <div class="bg-decoration">
        <div class="floral-corner floral-top-left">
            <i class="fas fa-leaf"></i>
        </div>
        <div class="floral-corner floral-top-right">
            <i class="fas fa-spa"></i>
        </div>
        <div class="floral-corner floral-bottom-left">
            <i class="fas fa-seedling"></i>
        </div>
        <div class="floral-corner floral-bottom-right">
            <i class="fas fa-leaf"></i>
        </div>
        
        <div class="ornament ornament-1"><i class="fas fa-star"></i></div>
        <div class="ornament ornament-2"><i class="fas fa-sparkles"></i></div>
        <div class="ornament ornament-3"><i class="fas fa-heart"></i></div>
        <div class="ornament ornament-4"><i class="fas fa-flower"></i></div>
        <div class="ornament ornament-5"><i class="fas fa-star"></i></div>
        <div class="ornament ornament-6"><i class="fas fa-sparkles"></i></div>
    </div>

    <div class="landing-wrapper">
        <div class="landing-container">
            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="logo-section">
                    <img src="{{ asset('memora_logo.png') }}" alt="MEMORA Logo" class="logo-img">
                    <h1 class="brand-name">MEMORA</h1>
                    <p class="brand-tagline">Capture Your Precious Moments</p>
                </div>

                <div class="divider"></div>

                <div class="qr-container">
                    <div class="qr-frame">
                        <div class="corner-decoration corner-tl">❦</div>
                        <div class="corner-decoration corner-tr">❦</div>
                        <div class="corner-decoration corner-bl">❦</div>
                        <div class="corner-decoration corner-br">❦</div>
                        <canvas id="qrcode"></canvas>
                    </div>
                </div>

                <div class="qr-instruction">
                    <i class="fas fa-mobile-screen"></i>
                    <span>Scan to view your beautiful memories</span>
                </div>
            </div>

            <!-- Content Section -->
            <div class="content-section">
                <!-- Session Info -->
                <div class="session-card">
                    <div class="session-header">
                        <div class="session-icon">
                            <i class="fas fa-calendar-heart"></i>
                        </div>
                        <div class="session-info">
                            <h2 id="sessionName">{{ $sessionCode ?? 'Current Session' }}</h2>
                            <div class="session-time">
                                <i class="fas fa-clock"></i>
                                <span id="currentTime"></span>
                            </div>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-box">
                            <span class="stat-value" id="statTotal">{{ $stats['total'] ?? 0 }}</span>
                            <span class="stat-label">Total Photos</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-value" id="statToday">{{ $stats['today'] ?? 0 }}</span>
                            <span class="stat-label">Today</span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-value" id="statSession">{{ $stats['session'] ?? 0 }}</span>
                            <span class="stat-label">This Session</span>
                        </div>
                    </div>
                </div>

                <!-- Photos Grid -->
                <div class="photos-showcase">
                    <div class="showcase-header">
                        <h3 class="showcase-title">
                            <i class="fas fa-images"></i>
                            Latest Captures
                        </h3>
                        <div class="live-indicator">
                            <div class="live-dot"></div>
                            <span>LIVE</span>
                        </div>
                    </div>

                    <div id="photosContainer">
                        @if(!empty($photos) && count($photos) > 0)
                            <div class="photos-grid" id="photosGrid">
                                @foreach($photos as $photo)
                                <div class="photo-item" data-photo-id="{{ $photo['id'] }}">
                                    <img src="{{ $photo['url'] }}" alt="{{ $photo['name'] }}">
                                    <div class="photo-overlay">
                                        <i class="fas fa-camera"></i>
                                        <span>{{ $photo['time'] }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state" id="emptyState">
                                <i class="fas fa-camera-retro"></i>
                                <p>Start capturing beautiful moments...</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        // Configuration
        const CONFIG = {
            sessionCode: @json($sessionCode ?? ''),
            lastPhotoId: @json($lastPhotoId ?? 0),
            qrCodeUrl: '',
            pollInterval: 3000, // 3 seconds
            maxPhotos: 8 // Maximum photos to display
        };

        // Update time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        // Build gallery URL
        function buildGalleryUrl() {
            const baseUrl = window.location.origin + '/gallery';
            if (CONFIG.sessionCode) {
                return `${baseUrl}?session_code=${encodeURIComponent(CONFIG.sessionCode)}`;
            }
            return baseUrl;
        }

        // Generate QR Code
        function generateQRCode() {
            CONFIG.qrCodeUrl = buildGalleryUrl();
            
            new QRCode(document.getElementById("qrcode"), {
                text: CONFIG.qrCodeUrl,
                width: 350,
                height: 350,
                colorDark: "#ccb36c",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        // Update stats with animation
        function updateStats(stats) {
            const statElements = {
                total: document.getElementById('statTotal'),
                today: document.getElementById('statToday'),
                session: document.getElementById('statSession')
            };

            Object.keys(stats).forEach(key => {
                const element = statElements[key];
                if (element && element.textContent !== stats[key].toString()) {
                    element.classList.add('updating');
                    element.textContent = stats[key];
                    
                    setTimeout(() => {
                        element.classList.remove('updating');
                    }, 300);
                }
            });
        }

        // Add photo to grid
        function addPhotoToGrid(photo) {
            const photosGrid = document.getElementById('photosGrid');
            const emptyState = document.getElementById('emptyState');
            
            // Remove empty state if exists
            if (emptyState) {
                emptyState.remove();
            }
            
            // Create grid if not exists
            if (!photosGrid) {
                const container = document.getElementById('photosContainer');
                const newGrid = document.createElement('div');
                newGrid.className = 'photos-grid';
                newGrid.id = 'photosGrid';
                container.appendChild(newGrid);
            }
            
            const grid = document.getElementById('photosGrid');
            
            // Check if photo already exists
            if (grid.querySelector(`[data-photo-id="${photo.id}"]`)) {
                return;
            }
            
            // Create photo element
            const photoItem = document.createElement('div');
            photoItem.className = 'photo-item new-photo';
            photoItem.setAttribute('data-photo-id', photo.id);
            photoItem.innerHTML = `
                <img src="${photo.url}" alt="${photo.name}">
                <div class="photo-overlay">
                    <i class="fas fa-camera"></i>
                    <span>${photo.time}</span>
                </div>
            `;
            
            // Add to beginning of grid
            grid.insertBefore(photoItem, grid.firstChild);
            
            // Remove animation class after animation completes
            setTimeout(() => {
                photoItem.classList.remove('new-photo');
            }, 800);
            
            // Remove oldest photo if exceeds max
            const photos = grid.querySelectorAll('.photo-item');
            if (photos.length > CONFIG.maxPhotos) {
                photos[photos.length - 1].remove();
            }
        }

        // Check for new photos via AJAX
        async function checkForNewPhotos() {
            try {
                const url = `/api/photos/latest-single?last_id=${CONFIG.lastPhotoId}` + 
                           (CONFIG.sessionCode ? `&session_code=${CONFIG.sessionCode}` : '');
                
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.success && data.has_new && data.photo) {
                    // Add new photo
                    addPhotoToGrid(data.photo);
                    
                    // Update last photo ID
                    CONFIG.lastPhotoId = data.photo.id;
                    
                    // Update stats
                    if (data.stats) {
                        updateStats(data.stats);
                    }
                    
                    console.log('New photo added:', data.photo.name);
                }
            } catch (error) {
                console.error('Error checking for new photos:', error);
            }
        }

        // Initialize
        function init() {
            // Generate QR Code
            generateQRCode();
            
            // Update time
            updateTime();
            setInterval(updateTime, 1000);
            
            // Start polling for new photos
            setInterval(checkForNewPhotos, CONFIG.pollInterval);
            
            console.log('Landing page initialized');
            console.log('Session:', CONFIG.sessionCode || 'All');
            console.log('Polling interval:', CONFIG.pollInterval + 'ms');
        }

        // Start when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    </script>
</body>
</html>