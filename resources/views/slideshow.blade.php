@extends('app')

@section('title', 'Slideshow - Memora')

@section('content')
<div class="modern-slideshow-container" x-data="modernSlideshowApp()" x-init="init()" @keydown.escape="exitFullscreen()" @keydown.arrow-left="prevSlide()" @keydown.arrow-right="nextSlide()" @keydown.space.prevent="togglePlay()">
    
    <!-- Loading Screen -->
    <div x-show="!imagesLoaded" class="modern-loading">
        <div class="loading-content">
            <div class="loading-rings">
                <div class="ring"></div>
                <div class="ring"></div>
                <div class="ring"></div>
            </div>
            <h3 class="loading-title">Mempersiapkan Slideshow</h3>
            <p class="loading-text" x-text="`Memuat ${loadedCount} dari ${photos.length} foto...`"></p>
            <div class="loading-progress-bar">
                <div class="progress-bar-fill" :style="`width: ${loadingProgress}%`"></div>
            </div>
            <span class="loading-percent" x-text="loadingProgress + '%'"></span>
        </div>
    </div>

    <!-- Slideshow Screen -->
    <div x-show="imagesLoaded" class="modern-slideshow-screen" :class="isFullscreen ? 'fullscreen' : ''">
        
        <!-- Background Blur Effect -->
        <div class="bg-blur-layer">
            <template x-for="(photo, index) in photos" :key="'bg-' + photo.id">
                <div 
                    class="bg-blur-image"
                    :class="currentIndex === index ? 'active' : ''"
                    :style="`background-image: url('${photo.full_url}')`"
                ></div>
            </template>
        </div>

        <!-- Main Photo Slides -->
        <div class="modern-slides-wrapper">
            <template x-for="(photo, index) in photos" :key="photo.id">
                <div 
                    class="modern-slide"
                    :class="[
                        currentIndex === index ? 'active' : '',
                        currentIndex === index ? currentAnimation : ''
                    ]"
                    x-show="currentIndex === index"
                >
                    <img :src="photo.full_url" :alt="'Photo ' + (index + 1)" class="modern-slide-image">
                    
                    <!-- Photo Info Overlay -->
                    <div class="modern-slide-info" x-show="showInfo" x-transition>
                        <div class="info-glass-card">
                            <div class="info-row">
                                <i class="fas fa-camera"></i>
                                <span x-text="`Foto ${index + 1} dari ${photos.length}`"></span>
                            </div>
                            <div class="info-row">
                                <i class="fas fa-clock"></i>
                                <span x-text="photo.uploaded_at"></span>
                            </div>
                            <div class="info-row" x-show="photo.session_code">
                                <i class="fas fa-tag"></i>
                                <span x-text="photo.session_code"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- QR Code - Pojok Kiri Bawah -->
        <div class="qr-code-container" x-show="showQR" x-transition>
            <div class="qr-glass-card">
                <div class="qr-header">
                    <i class="fas fa-qrcode"></i>
                    <span>Scan untuk akses</span>
                </div>
                <div id="qrcode" class="qr-code-display"></div>
                <div class="qr-url" x-text="siteUrl"></div>
            </div>
        </div>

        <!-- Modern Controls Overlay -->
        <div class="modern-controls-overlay" :class="showControls ? 'visible' : 'hidden'" @mousemove="resetControlsTimer()">
            
            <!-- Top Control Bar -->
            <div class="modern-control-bar top">
                <div class="control-left">
                    <button @click="exitSlideshow()" class="modern-btn glass-btn" title="Exit (ESC)">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="slideshow-brand">
                        <i class="fas fa-images"></i>
                        <span>Memora Slideshow</span>
                    </div>
                </div>
                
                <div class="control-right">
                    <button @click="toggleInfo()" class="modern-btn glass-btn" :class="showInfo ? 'active' : ''" title="Info">
                        <i class="fas fa-info-circle"></i>
                    </button>
                    <button @click="toggleQR()" class="modern-btn glass-btn" :class="showQR ? 'active' : ''" title="QR Code">
                        <i class="fas fa-qrcode"></i>
                    </button>
                    <button @click="toggleFullscreen()" class="modern-btn glass-btn" title="Fullscreen (F)">
                        <i class="fas" :class="isFullscreen ? 'fa-compress' : 'fa-expand'"></i>
                    </button>
                </div>
            </div>

            <!-- Bottom Control Bar -->
            <div class="modern-control-bar bottom">
                
                <!-- Main Controls -->
                <div class="main-controls-panel">
                    
                    <!-- Navigation Buttons -->
                    <div class="nav-buttons">
                        <button @click="prevSlide()" class="modern-btn nav-btn" :disabled="currentIndex === 0 && !loop">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <button @click="togglePlay()" class="modern-btn play-btn-large">
                            <i class="fas" :class="isPlaying ? 'fa-pause' : 'fa-play'"></i>
                        </button>
                        
                        <button @click="nextSlide()" class="modern-btn nav-btn" :disabled="currentIndex === photos.length - 1 && !loop">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Progress & Info -->
                    <div class="progress-panel">
                        <!-- Progress Bar -->
                        <div class="modern-progress-track">
                            <div class="progress-bg">
                                <div class="progress-fill" :style="`width: ${progressPercent}%`"></div>
                            </div>
                            <div class="progress-dots">
                                <template x-for="(photo, index) in photos" :key="'dot-' + photo.id">
                                    <button 
                                        @click="goToSlide(index)" 
                                        class="progress-dot"
                                        :class="currentIndex === index ? 'active' : ''"
                                        :title="`Foto ${index + 1}`"
                                    ></button>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Counter -->
                        <div class="slide-counter">
                            <span class="counter-current" x-text="currentIndex + 1"></span>
                            <span class="counter-separator">/</span>
                            <span class="counter-total" x-text="photos.length"></span>
                        </div>
                    </div>

                    <!-- Settings Panel -->
                    <div class="settings-panel">
                        
                        <!-- Duration Control -->
                        <div class="duration-control">
                            <i class="fas fa-clock"></i>
                            <input 
                                type="range" 
                                x-model.number="slideDuration" 
                                min="1" 
                                max="10" 
                                step="0.5"
                                class="modern-slider"
                                @input="restartTimer()"
                            >
                            <span class="duration-value" x-text="slideDuration + 's'"></span>
                        </div>

                        <!-- Animation Selector -->
                        <button @click="toggleAnimationMenu()" class="modern-btn settings-btn" :class="showAnimationMenu ? 'active' : ''">
                            <i class="fas fa-magic"></i>
                            <span x-text="animationType === 'random' ? 'Random' : 'Animation'"></span>
                        </button>

                    </div>
                </div>
            </div>

            <!-- Animation Menu Popup -->
            <div x-show="showAnimationMenu" @click.away="showAnimationMenu = false" class="modern-animation-menu" x-transition>
                <div class="menu-header">
                    <h4>Pilih Animasi</h4>
                    <button @click="showAnimationMenu = false" class="close-menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="animation-grid">
                    <button 
                        @click="setAnimation('random')" 
                        class="animation-card"
                        :class="animationType === 'random' ? 'active' : ''"
                    >
                        <i class="fas fa-random"></i>
                        <span>Random</span>
                        <small>Acak otomatis</small>
                    </button>
                    <button 
                        @click="setAnimation('fade')" 
                        class="animation-card"
                        :class="animationType === 'fade' ? 'active' : ''"
                    >
                        <i class="fas fa-eye"></i>
                        <span>Fade</span>
                        <small>Muncul halus</small>
                    </button>
                    <button 
                        @click="setAnimation('slide-left')" 
                        class="animation-card"
                        :class="animationType === 'slide-left' ? 'active' : ''"
                    >
                        <i class="fas fa-arrow-right"></i>
                        <span>Slide Left</span>
                        <small>Geser kiri</small>
                    </button>
                    <button 
                        @click="setAnimation('slide-top')" 
                        class="animation-card"
                        :class="animationType === 'slide-top' ? 'active' : ''"
                    >
                        <i class="fas fa-arrow-down"></i>
                        <span>Slide Top</span>
                        <small>Geser atas</small>
                    </button>
                    <button 
                        @click="setAnimation('zoom')" 
                        class="animation-card"
                        :class="animationType === 'zoom' ? 'active' : ''"
                    >
                        <i class="fas fa-search-plus"></i>
                        <span>Zoom In</span>
                        <small>Perbesar</small>
                    </button>
                    <button 
                        @click="setAnimation('rotate')" 
                        class="animation-card"
                        :class="animationType === 'rotate' ? 'active' : ''"
                    >
                        <i class="fas fa-sync"></i>
                        <span>Rotate</span>
                        <small>Putar</small>
                    </button>
                </div>
            </div>

        </div>

        <!-- Touch Navigation Areas -->
        <div class="touch-area left" @click="prevSlide()"></div>
        <div class="touch-area right" @click="nextSlide()"></div>
        <div class="touch-area center" @click="toggleControls()"></div>
    </div>

</div>
@endsection

@push('scripts')
<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
function modernSlideshowApp() {
    return {
        photos: @json($photos),
        currentIndex: 0,
        isPlaying: false,
        isFullscreen: false,
        showControls: true,
        showInfo: false,
        showQR: false,
        showAnimationMenu: false,
        slideDuration: 3,
        timer: null,
        controlsTimer: null,
        imagesLoaded: false,
        loadingProgress: 0,
        loadedCount: 0,
        animationType: 'random',
        currentAnimation: '',
        loop: true,
        siteUrl: window.location.origin,
        
        animations: [
            'slide-top', 'slide-bottom', 'slide-left', 'slide-right',
            'fade', 'zoom', 'zoom-out', 'rotate', 'rotate-reverse',
            'flip-h', 'flip-v', 'bounce', 'roll', 'swing'
        ],
        
        init() {
            this.preloadImages();
            this.setupKeyboardShortcuts();
            
            this.$watch('imagesLoaded', (value) => {
                if (value) {
                    setTimeout(() => {
                        this.generateQRCode();
                        this.play();
                        this.enterFullscreen();
                    }, 500);
                }
            });
        },
        
        preloadImages() {
            let loaded = 0;
            const total = this.photos.length;
            
            if (total === 0) {
                this.imagesLoaded = true;
                return;
            }
            
            this.photos.forEach((photo) => {
                const img = new Image();
                img.onload = () => {
                    loaded++;
                    this.loadedCount = loaded;
                    this.loadingProgress = Math.round((loaded / total) * 100);
                    
                    if (loaded === total) {
                        this.imagesLoaded = true;
                    }
                };
                img.onerror = () => {
                    loaded++;
                    this.loadedCount = loaded;
                    this.loadingProgress = Math.round((loaded / total) * 100);
                    
                    if (loaded === total) {
                        this.imagesLoaded = true;
                    }
                };
                img.src = photo.full_url;
            });
        },
        
        generateQRCode() {
            // Clear existing QR code
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';
            
            // Generate new QR code
            new QRCode(qrContainer, {
                text: this.siteUrl,
                width: 120,
                height: 120,
                colorDark: "#1a202c",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        },
        
        play() {
            this.isPlaying = true;
            this.startTimer();
        },
        
        pause() {
            this.isPlaying = false;
            this.stopTimer();
        },
        
        togglePlay() {
            if (this.isPlaying) {
                this.pause();
            } else {
                this.play();
            }
        },
        
        startTimer() {
            this.stopTimer();
            this.timer = setInterval(() => {
                this.nextSlide();
            }, this.slideDuration * 1000);
        },
        
        stopTimer() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },
        
        restartTimer() {
            if (this.isPlaying) {
                this.startTimer();
            }
        },
        
        nextSlide() {
            if (this.currentIndex < this.photos.length - 1) {
                this.currentIndex++;
            } else if (this.loop) {
                this.currentIndex = 0;
            }
            this.updateAnimation();
        },
        
        prevSlide() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else if (this.loop) {
                this.currentIndex = this.photos.length - 1;
            }
            this.updateAnimation();
        },
        
        goToSlide(index) {
            this.currentIndex = index;
            this.updateAnimation();
        },
        
        updateAnimation() {
            if (this.animationType === 'random') {
                this.currentAnimation = this.getRandomAnimation();
            } else {
                this.currentAnimation = this.animationType;
            }
        },
        
        getRandomAnimation() {
            const randomIndex = Math.floor(Math.random() * this.animations.length);
            return this.animations[randomIndex];
        },
        
        setAnimation(type) {
            this.animationType = type;
            this.currentAnimation = type === 'random' ? this.getRandomAnimation() : type;
            this.showAnimationMenu = false;
        },
        
        toggleInfo() {
            this.showInfo = !this.showInfo;
        },
        
        toggleQR() {
            this.showQR = !this.showQR;
        },
        
        toggleAnimationMenu() {
            this.showAnimationMenu = !this.showAnimationMenu;
        },
        
        toggleControls() {
            this.showControls = !this.showControls;
            if (this.showControls) {
                this.resetControlsTimer();
            }
        },
        
        resetControlsTimer() {
            clearTimeout(this.controlsTimer);
            this.showControls = true;
            this.controlsTimer = setTimeout(() => {
                this.showControls = false;
            }, 3000);
        },
        
        enterFullscreen() {
            const elem = this.$el.querySelector('.modern-slideshow-screen');
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
            this.isFullscreen = true;
        },
        
        exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            this.isFullscreen = false;
        },
        
        toggleFullscreen() {
            if (this.isFullscreen) {
                this.exitFullscreen();
            } else {
                this.enterFullscreen();
            }
        },
        
        exitSlideshow() {
            this.exitFullscreen();
            this.pause();
            window.location.href = '{{ route("gallery") }}';
        },
        
        setupKeyboardShortcuts() {
            window.addEventListener('keydown', (e) => {
                if (e.key === 'f' || e.key === 'F') {
                    this.toggleFullscreen();
                }
            });
        },
        
        get progressPercent() {
            return ((this.currentIndex + 1) / this.photos.length) * 100;
        }
    }
}
</script>
@endpush

@push('styles')
<style>
/* Modern Slideshow Container */
.modern-slideshow-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: #000;
    z-index: 9999;
    overflow: hidden;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}

/* Modern Loading Screen */
.modern-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
}

.loading-content {
    text-align: center;
    color: white;
}

.loading-rings {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
}

.ring {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 3px solid transparent;
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

.ring:nth-child(2) {
    width: 80%;
    height: 80%;
    top: 10%;
    left: 10%;
    border-top-color: #764ba2;
    animation-delay: -0.5s;
}

.ring:nth-child(3) {
    width: 60%;
    height: 60%;
    top: 20%;
    left: 20%;
    border-top-color: #f093fb;
    animation-delay: -1s;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.loading-text {
    font-size: 1rem;
    opacity: 0.8;
    margin-bottom: 1.5rem;
}

.loading-progress-bar {
    width: 300px;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
    margin: 0 auto 0.5rem;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s;
    box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
}

.loading-percent {
    font-size: 0.875rem;
    opacity: 0.6;
}

/* Modern Slideshow Screen */
.modern-slideshow-screen {
    position: relative;
    width: 100%;
    height: 100vh;
    background: #000;
}

/* Background Blur Layer */
.bg-blur-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
}

.bg-blur-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    filter: blur(40px) brightness(0.4);
    transform: scale(1.1);
    opacity: 0;
    transition: opacity 1s ease;
}

.bg-blur-image.active {
    opacity: 1;
}

/* Modern Slides */
.modern-slides-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.modern-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    padding: 4rem 2rem;
}

.modern-slide.active {
    opacity: 1;
    z-index: 1;
}

.modern-slide-image {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 1rem;
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.5);
}

/* Photo Info Overlay */
.modern-slide-info {
    position: absolute;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.info-glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1rem;
    padding: 1rem 1.5rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.info-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-row i {
    opacity: 0.7;
}

/* QR Code Container - POJOK KIRI BAWAH */
.qr-code-container {
    position: fixed;
    bottom: 2rem;
    left: 2rem;
    z-index: 100;
}

.qr-glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 1rem;
    padding: 1rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    text-align: center;
}

.qr-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    color: #1a202c;
    font-size: 0.875rem;
    font-weight: 600;
}

.qr-header i {
    color: #667eea;
}

.qr-code-display {
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-code-display img {
    border-radius: 0.5rem;
}

.qr-url {
    font-size: 0.75rem;
    color: #64748b;
    word-break: break-all;
    padding: 0.5rem;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
}

/* Modern Controls */
.modern-controls-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    transition: opacity 0.3s;
    z-index: 10;
}

.modern-controls-overlay.visible {
    opacity: 1;
}

.modern-controls-overlay.hidden {
    opacity: 0;
}

.modern-controls-overlay > * {
    pointer-events: auto;
}

.modern-control-bar {
    position: absolute;
    left: 0;
    right: 0;
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    z-index: 20;
}

.modern-control-bar.top {
    top: 0;
    justify-content: space-between;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), transparent);
}

.modern-control-bar.bottom {
    bottom: 0;
    justify-content: center;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
}

.control-left,
.control-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.slideshow-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
    font-size: 1rem;
    font-weight: 600;
}

.slideshow-brand i {
    font-size: 1.25rem;
    color: #667eea;
}

/* Modern Buttons */
.modern-btn {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.75rem;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 1rem;
}

.modern-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.modern-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.modern-btn.active {
    background: rgba(102, 126, 234, 0.9);
    border-color: rgba(102, 126, 234, 1);
}

.glass-btn {
    width: 45px;
    height: 45px;
    padding: 0;
}

.nav-btn {
    width: 50px;
    height: 50px;
    padding: 0;
}

.play-btn-large {
    width: 60px;
    height: 60px;
    padding: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
    border: 2px solid rgba(255, 255, 255, 0.3);
    font-size: 1.25rem;
}

.play-btn-large:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 1), rgba(118, 75, 162, 1));
    transform: scale(1.1);
}

.settings-btn {
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Main Controls Panel */
.main-controls-panel {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1.5rem;
    padding: 1.5rem 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    max-width: 900px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.nav-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

/* Progress Panel */
.progress-panel {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.modern-progress-track {
    position: relative;
    width: 100%;
}

.progress-bg {
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s;
    box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
}

.progress-dots {
    position: absolute;
    top: -6px;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-between;
}

.progress-dot {
    width: 14px;
    height: 14px;
    background: rgba(255, 255, 255, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0;
}

.progress-dot:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: scale(1.2);
}

.progress-dot.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: white;
    transform: scale(1.3);
    box-shadow: 0 0 10px rgba(102, 126, 234, 0.8);
}

.slide-counter {
    text-align: center;
    color: white;
    font-weight: 600;
}

.counter-current {
    font-size: 1.5rem;
}

.counter-separator {
    font-size: 1.25rem;
    opacity: 0.5;
    margin: 0 0.5rem;
}

.counter-total {
    font-size: 1.25rem;
    opacity: 0.7;
}

/* Settings Panel */
.settings-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
}

.duration-control {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem 1.25rem;
    border-radius: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.modern-slider {
    width: 100px;
    -webkit-appearance: none;
    appearance: none;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
    outline: none;
}

.modern-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 16px;
    height: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.modern-slider::-moz-range-thumb {
    width: 16px;
    height: 16px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.duration-value {
    font-size: 0.875rem;
    font-weight: 600;
    min-width: 35px;
}

/* Animation Menu */
.modern-animation-menu {
    position: absolute;
    bottom: 160px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 1.5rem;
    padding: 1.5rem;
    min-width: 400px;
    max-width: 90vw;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

.menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.menu-header h4 {
    margin: 0;
    color: #1a202c;
    font-size: 1.125rem;
}

.close-menu {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.05);
    border: none;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.close-menu:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #1a202c;
}

.animation-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.animation-card {
    padding: 1rem;
    background: rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 0.75rem;
    color: #1a202c;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    text-align: center;
}

.animation-card:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: rgba(102, 126, 234, 0.3);
    transform: translateY(-2px);
}

.animation-card.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    color: white;
}

.animation-card i {
    font-size: 1.5rem;
}

.animation-card span {
    font-size: 0.875rem;
    font-weight: 600;
}

.animation-card small {
    font-size: 0.75rem;
    opacity: 0.8;
}

/* Touch Areas */
.touch-area {
    position: absolute;
    top: 0;
    bottom: 0;
    z-index: 5;
}

.touch-area.left {
    left: 0;
    width: 20%;
}

.touch-area.right {
    right: 0;
    width: 20%;
}

.touch-area.center {
    left: 20%;
    right: 20%;
    width: 60%;
}

/* Animations */
.slide-top {
    animation: slideFromTop 0.6s ease-out;
}

@keyframes slideFromTop {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.slide-bottom {
    animation: slideFromBottom 0.6s ease-out;
}

@keyframes slideFromBottom {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.slide-left {
    animation: slideFromLeft 0.6s ease-out;
}

@keyframes slideFromLeft {
    from {
        transform: translateX(-100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.slide-right {
    animation: slideFromRight 0.6s ease-out;
}

@keyframes slideFromRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.fade {
    animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.zoom {
    animation: zoomIn 0.8s ease-out;
}

@keyframes zoomIn {
    from {
        transform: scale(0.5);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.zoom-out {
    animation: zoomOut 0.8s ease-out;
}

@keyframes zoomOut {
    from {
        transform: scale(1.5);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.rotate {
    animation: rotateIn 0.8s ease-out;
}

@keyframes rotateIn {
    from {
        transform: rotate(-180deg) scale(0);
        opacity: 0;
    }
    to {
        transform: rotate(0) scale(1);
        opacity: 1;
    }
}

.rotate-reverse {
    animation: rotateReverse 0.8s ease-out;
}

@keyframes rotateReverse {
    from {
        transform: rotate(180deg) scale(0);
        opacity: 0;
    }
    to {
        transform: rotate(0) scale(1);
        opacity: 1;
    }
}

.flip-h {
    animation: flipHorizontal 0.6s ease-out;
}

@keyframes flipHorizontal {
    from {
        transform: rotateY(-180deg);
        opacity: 0;
    }
    to {
        transform: rotateY(0);
        opacity: 1;
    }
}

.flip-v {
    animation: flipVertical 0.6s ease-out;
}

@keyframes flipVertical {
    from {
        transform: rotateX(-180deg);
        opacity: 0;
    }
    to {
        transform: rotateX(0);
        opacity: 1;
    }
}

.bounce {
    animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes bounceIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.roll {
    animation: rollIn 0.8s ease-out;
}

@keyframes rollIn {
    from {
        transform: translateX(-100%) rotate(-360deg);
        opacity: 0;
    }
    to {
        transform: translateX(0) rotate(0);
        opacity: 1;
    }
}

.swing {
    animation: swingIn 0.8s ease-out;
}

@keyframes swingIn {
    0% {
        transform: rotate(-15deg);
        opacity: 0;
    }
    50% {
        transform: rotate(10deg);
    }
    100% {
        transform: rotate(0);
        opacity: 1;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .modern-control-bar {
        padding: 1rem;
    }
    
    .main-controls-panel {
        padding: 1rem;
        gap: 1rem;
        max-width: calc(100% - 2rem);
    }
    
    .nav-btn {
        width: 45px;
        height: 45px;
    }
    
    .play-btn-large {
        width: 55px;
        height: 55px;
    }
    
    .settings-panel {
        flex-direction: column;
        gap: 1rem;
    }
    
    .modern-animation-menu {
        min-width: 300px;
        padding: 1rem;
    }
    
    .animation-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .qr-code-container {
        bottom: 1rem;
        left: 1rem;
    }
    
    .qr-glass-card {
        padding: 0.75rem;
    }
    
    .modern-slide {
        padding: 2rem 1rem;
    }
}

@media (max-width: 480px) {
    .slideshow-brand span {
        display: none;
    }
    
    .duration-control {
        padding: 0.5rem 0.75rem;
    }
    
    .modern-slider {
        width: 80px;
    }
}
</style>
@endpush