@extends('app')

@section('title', 'Editor - Memora')

@section('content')
<div class="editor-container" x-data="editorApp()" x-init="init()">
    
    <!-- Header -->
    <div class="editor-header">
        <div class="editor-header-content">
            <div>
                <h1 class="editor-title">
                    <i class="fas fa-wand-magic-sparkles"></i> Photo Editor
                </h1>
                <p class="editor-subtitle">Pilih template dan atur foto Anda</p>
            </div>
            <div class="editor-actions">
                <a href="{{ route('gallery') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> 
                    <span class="btn-text">Kembali</span>
                </a>
                <button @click="shareResult()" class="btn btn-success" :disabled="!selectedTemplate">
                    <i class="fas fa-share-nodes"></i> 
                    <span class="btn-text">Bagikan</span>
                </button>
                <button @click="downloadResult()" class="btn btn-primary" :disabled="!selectedTemplate">
                    <i class="fas fa-download"></i> 
                    <span class="btn-text">Download</span>
                </button>
            </div>
        </div>
    </div>

    <div class="editor-layout">
        
        <!-- Sidebar - Photo Selection -->
        <div class="editor-sidebar">
            <div class="sidebar-section">
                <h3 class="sidebar-title">
                    <i class="fas fa-images"></i> Foto Anda (<span x-text="photos.length"></span>)
                </h3>
                
                <div class="photo-list">
                    <template x-for="(photo, index) in photos" :key="photo.id">
                        <div 
                            class="photo-item"
                            draggable="true"
                            @dragstart="handleDragStart($event, photo)"
                            @dragend="handleDragEnd($event)"
                        >
                            <img :src="photo.full_url" :alt="'Photo ' + photo.id">
                            <div class="photo-item-overlay">
                                <span x-text="index + 1"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="sidebar-tip">
                    <i class="fas fa-info-circle"></i>
                    <p>Drag foto ke slot yang tersedia pada template</p>
                </div>
            </div>

            <!-- LUT Filter Selection -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">
                    <i class="fas fa-palette"></i> Color Filter (LUT)
                </h3>
                
                <div class="lut-list">
                    <!-- No Filter Option -->
                    <div 
                        class="lut-item"
                        :class="!selectedLut ? 'active' : ''"
                        @click="selectLut(null)"
                    >
                        <div class="lut-preview">
                            <i class="fas fa-ban"></i>
                        </div>
                        <span class="lut-name">Tanpa Filter</span>
                    </div>

                    <!-- LUT Options -->
                    <template x-for="lut in luts" :key="lut.id">
                        <div 
                            class="lut-item"
                            :class="selectedLut?.id === lut.id ? 'active' : ''"
                            @click="selectLut(lut)"
                        >
                            <div class="lut-preview">
                                <template x-if="lut.thumbnail_url">
                                    <img :src="lut.thumbnail_url" :alt="lut.name">
                                </template>
                                <template x-if="!lut.thumbnail_url">
                                    <i class="fas fa-palette"></i>
                                </template>
                            </div>
                            <span class="lut-name" x-text="lut.name"></span>
                        </div>
                    </template>
                </div>

                <div class="sidebar-tip" x-show="selectedLut">
                    <i class="fas fa-info-circle"></i>
                    <p x-text="selectedLut?.description || 'Filter color grading aktif'"></p>
                </div>
            </div>

            <!-- Template Selection -->
            <div class="sidebar-section">
                <h3 class="sidebar-title">
                    <i class="fas fa-layer-group"></i> Pilih Template
                </h3>
                
                <div class="template-list">
                    <template x-for="template in templates" :key="template.id">
                        <div 
                            class="template-item"
                            :class="selectedTemplate?.id === template.id ? 'active' : ''"
                            @click="selectTemplate(template)"
                        >
                            <div class="template-preview">
                                <!-- Frame Preview (jika ada frame_url atau background_url) -->
                                <template x-if="template.frame_url || template.background_url">
                                    <img 
                                        :src="template.frame_url || template.background_url" 
                                        :alt="template.name"
                                        class="template-frame-preview"
                                    >
                                </template>
                                
                                <!-- Slots Preview (fallback jika tidak ada frame) -->
                                <template x-if="!template.frame_url && !template.background_url">
                                    <div class="template-slots-preview">
                                        <template x-for="slot in template.slots" :key="slot.id">
                                            <div 
                                                class="slot-preview"
                                                :style="`
                                                    left: ${(slot.x / template.canvas_width) * 100}%;
                                                    top: ${(slot.y / template.canvas_height) * 100}%;
                                                    width: ${(slot.width / template.canvas_width) * 100}%;
                                                    height: ${(slot.height / template.canvas_height) * 100}%;
                                                `"
                                            ></div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            <div class="template-info">
                                <h4 x-text="template.name"></h4>
                                <p x-text="`${template.slots.length} slot`"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Canvas Area -->
        <div class="editor-canvas-wrapper">
            <div x-show="!selectedTemplate" class="empty-canvas">
                <i class="fas fa-image"></i>
                <h3>Pilih Template untuk Memulai</h3>
                <p>Pilih salah satu template dari sidebar</p>
            </div>

            <div x-show="selectedTemplate" class="canvas-container">
                <!-- Canvas Controls - Dipindah ke atas -->
                <div class="canvas-controls">
                    <button @click="zoomOut()" class="control-btn" :disabled="zoomLevel <= 25" title="Zoom out">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <span class="zoom-level" x-text="`${zoomLevel}%`"></span>
                    <button @click="zoomIn()" class="control-btn" :disabled="zoomLevel >= 150" title="Zoom in">
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button @click="resetZoom()" class="control-btn" title="Reset zoom">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>

                <!-- Canvas Scroll Wrapper -->
                <div class="canvas-scroll-wrapper">
                    <div 
                        class="canvas" 
                        x-ref="canvas"
                        :style="`
                            width: ${canvasWidth}px;
                            height: ${canvasHeight}px;
                        `"
                    >
                        <!-- Slots Layer (Behind) -->
                        <template x-if="selectedTemplate">
                            <template x-for="slot in selectedTemplate.slots" :key="slot.id">
                                <div 
                                    class="canvas-slot"
                                    :class="slotPhotos[slot.id] ? 'filled' : ''"
                                    :style="`
                                        left: ${slot.x * canvasScale}px;
                                        top: ${slot.y * canvasScale}px;
                                        width: ${slot.width * canvasScale}px;
                                        height: ${slot.height * canvasScale}px;
                                        transform: rotate(${slot.rotation}deg);
                                        border: ${slot.border_width}px ${slot.border_style} ${slot.border_color};
                                        border-radius: ${slot.border_radius}px;
                                    `"
                                    @dragover.prevent
                                    @drop.prevent="handleDrop($event, slot)"
                                >
                                    <template x-if="!slotPhotos[slot.id]">
                                        <div class="slot-placeholder">
                                            <i class="fas fa-image"></i>
                                            <span>Drop foto di sini</span>
                                        </div>
                                    </template>
                                    
                                    <template x-if="slotPhotos[slot.id]">
                                        <div class="slot-photo" :class="selectedLut ? 'lut-applied' : ''">
                                            <img :src="slotPhotos[slot.id].full_url" alt="Photo">
                                            <button 
                                                class="remove-photo-btn"
                                                @click="removePhotoFromSlot(slot.id)"
                                                title="Hapus foto"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </template>
                        
                        <!-- Background Overlay (On Top) -->
                        <template x-if="selectedTemplate?.background_url">
                            <div 
                                class="canvas-background-overlay"
                                :style="`background-image: url('${selectedTemplate.background_url}');`"
                            ></div>
                        </template>
                    </div>
                </div>

                <!-- LUT Filter Info -->
                <div class="filter-badge" x-show="selectedLut">
                    <i class="fas fa-palette"></i>
                    <span x-text="selectedLut?.name"></span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/editor.css') }}">
<style>
    /* ========================================
   Editor Improvements - Tooltips, Processing, Notifications
   ======================================== */

/* ========================================
   HIGH QUALITY RENDERING OPTIMIZATION
   Untuk frame dan text yang tajam saat download/share
   ======================================== */
.canvas,
.canvas * {
    /* Force high quality rendering */
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

.canvas-background-overlay,
.canvas-background-overlay img,
.template-frame-preview,
.slot-photo img {
    /* Optimal image quality */
    image-rendering: -webkit-optimize-contrast;
    image-rendering: high-quality;
    backface-visibility: hidden;
    transform: translateZ(0);
    will-change: transform;
}

/* Text clarity optimization */
.canvas-background-overlay *,
.canvas * {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
    font-feature-settings: "kern" 1;
    font-kerning: normal;
}

/* ======================================== */

/* Button Wrapper dengan Tooltip */
.btn-wrapper {
    position: relative;
    display: inline-block;
}

.btn-tooltip {
    position: absolute;
    bottom: calc(100% + 12px);
    left: 50%;
    transform: translateX(-50%) translateY(10px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
    pointer-events: none;
}

.btn-wrapper:hover .btn-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

.tooltip-content {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
    min-width: 260px;
    position: relative;
}

.tooltip-content::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 8px solid transparent;
    border-top-color: #374151;
}

.tooltip-content strong {
    display: block;
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #f9fafb;
}

.tooltip-content p {
    font-size: 0.875rem;
    margin: 0 0 0.75rem 0;
    color: #e5e7eb;
    line-height: 1.5;
}

.tooltip-content ul {
    margin: 0;
    padding-left: 1.25rem;
    list-style: none;
}

.tooltip-content li {
    font-size: 0.8125rem;
    color: #cbd5e1;
    margin-bottom: 0.25rem;
    position: relative;
    padding-left: 0;
}

.tooltip-content li::before {
    content: '•';
    position: absolute;
    left: -1rem;
    color: var(--primary, #ccb36c);
    font-weight: bold;
}

/* Button Loading State */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: inherit;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.2; }
    50% { opacity: 0.4; }
}

/* Processing Overlay */
.processing-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.processing-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
    backdrop-filter: blur(20px);
    border-radius: 1.5rem;
    padding: 3rem 2.5rem;
    text-align: center;
    max-width: 420px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    animation: scaleIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.processing-icon {
    width: 5rem;
    height: 5rem;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, var(--primary, #ccb36c), var(--primary-dark, #c8ae68));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(204, 179, 108, 0.5);
}

.processing-icon i {
    font-size: 2.5rem;
    color: white;
}

.processing-card h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0 0 0.75rem 0;
}

.processing-card p {
    font-size: 0.9375rem;
    color: #64748b;
    margin: 0 0 1.5rem 0;
    line-height: 1.6;
}

.progress-bar-container {
    width: 100%;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary, #ccb36c), var(--primary-dark, #c8ae68));
    border-radius: 4px;
    transition: width 0.3s ease-out;
    box-shadow: 0 0 10px rgba(204, 179, 108, 0.6);
}

/* Notification Toast */
.notification {
    position: fixed;
    top: 80px;
    right: 20px;
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    font-size: 0.9375rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    z-index: 10001;
    min-width: 300px;
}

.notification i {
    font-size: 1.25rem;
}

.notification.success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.notification.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

/* Share Modal */
.share-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 1rem;
    animation: fadeIn 0.3s ease-out;
}

.share-modal {
    background: white;
    border-radius: 1.5rem;
    max-width: 520px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    animation: scaleIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.share-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: linear-gradient(135deg, #f9fafb, #f3f4f6);
}

.share-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
}

.close-btn {
    width: 2.5rem;
    height: 2.5rem;
    background: rgba(0, 0, 0, 0.05);
    border: none;
    border-radius: 50%;
    color: #64748b;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #1a202c;
    transform: rotate(90deg);
}

.share-body {
    padding: 1.5rem;
}

.share-info {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(59, 130, 246, 0.1);
    border-left: 4px solid #3b82f6;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.share-info i {
    color: #3b82f6;
    font-size: 1.125rem;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.share-info p {
    font-size: 0.875rem;
    color: #1e40af;
    line-height: 1.6;
    margin: 0;
}

.btn-block {
    width: 100%;
    justify-content: center;
}

.share-divider {
    display: flex;
    align-items: center;
    margin: 1.5rem 0;
}

.share-divider::before,
.share-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
}

.share-divider span {
    padding: 0 1rem;
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.share-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
}

.share-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: 0.75rem;
    text-decoration: none;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.share-btn i {
    font-size: 1.5rem;
}

.share-btn.facebook {
    background: linear-gradient(135deg, #1877f2, #0c63d4);
}

.share-btn.twitter {
    background: linear-gradient(135deg, #1da1f2, #0c8bd9);
}

.share-btn.whatsapp {
    background: linear-gradient(135deg, #25d366, #1da851);
}

.share-btn.telegram {
    background: linear-gradient(135deg, #0088cc, #006aa3);
}

/* Spinner Animation */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Loading Modal Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    /* Hide tooltips on mobile */
    .btn-tooltip {
        display: none;
    }
    
    .processing-card {
        padding: 2rem 1.5rem;
    }
    
    .processing-icon {
        width: 4rem;
        height: 4rem;
    }
    
    .processing-icon i {
        font-size: 2rem;
    }
    
    .processing-card h3 {
        font-size: 1.25rem;
    }
    
    .notification {
        right: 1rem;
        left: 1rem;
        top: 1rem;
        min-width: auto;
    }
    
    .share-buttons {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    /* Hide button text on very small screens */
    .btn-text {
        display: none;
    }
    
    .share-modal {
        margin: 0.5rem;
    }
    
    .share-header {
        padding: 1rem;
    }
    
    .share-body {
        padding: 1rem;
    }
}

/* Print Styles */
@media print {
    .btn-tooltip,
    .processing-overlay,
    .notification,
    .share-modal-overlay {
        display: none !important;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .processing-card {
        background: linear-gradient(135deg, rgba(31, 41, 55, 0.98) 0%, rgba(17, 24, 39, 0.95) 100%);
    }
    
    .processing-card h3 {
        color: #f9fafb;
    }
    
    .processing-card p {
        color: #d1d5db;
    }
    
    .share-modal {
        background: #1f2937;
    }
    
    .share-header {
        background: linear-gradient(135deg, #374151, #4b5563);
        border-bottom-color: #4b5563;
    }
    
    .share-header h3 {
        color: #f9fafb;
    }
    
    .close-btn {
        background: rgba(255, 255, 255, 0.1);
        color: #d1d5db;
    }
    
    .close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #f9fafb;
    }
    
    .share-info {
        background: rgba(59, 130, 246, 0.2);
        color: #93c5fd;
    }
}

/* Accessibility */
.btn:focus-visible,
.close-btn:focus-visible,
.share-btn:focus-visible {
    outline: 3px solid var(--primary, #ccb36c);
    outline-offset: 2px;
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    .btn-tooltip,
    .processing-overlay,
    .processing-card,
    .notification,
    .share-modal-overlay,
    .share-modal,
    .btn-loading::after {
        animation: none;
        transition: none;
    }
    
    .fa-spinner {
        animation: none;
    }
}
/* Template Frame Preview */
.template-frame-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    inset: 0;
}

/* LUT Filter Styles */
.lut-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.625rem;
}

.lut-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-base);
}

.lut-item:hover {
    border-color: var(--primary);
    background: var(--gray-50);
}

.lut-item.active {
    border-color: var(--primary);
    background: rgba(59, 130, 246, 0.1);
}

.lut-preview {
    width: 100%;
    aspect-ratio: 1;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
}

.lut-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lut-preview i {
    font-size: 1.5rem;
    color: var(--gray-400);
}

.lut-name {
    font-size: 0.75rem;
    font-weight: 500;
    text-align: center;
    color: var(--gray-700);
}

.lut-item.active .lut-name {
    color: var(--primary);
}

/* LUT Applied Effect - Using CSS Filters as approximation */
.slot-photo.lut-applied img {
    filter: contrast(1.1) saturate(1.2);
}

.filter-badge {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    background: var(--primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    z-index: 10;
}

@media (max-width: 768px) {
    .lut-list {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* ========================================
   SHARE MODAL STYLES - TAMBAHAN
   ======================================== */

.share-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.share-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
}

.share-modal-content {
    position: relative;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.98));
    border-radius: 24px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.share-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.share-modal-header h3 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 700;
    color: white;
    display: flex;
    align-items: center;
    gap: 12px;
}

.share-modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: white;
}

.share-modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.share-modal-body {
    padding: 24px;
}

.share-modal-message {
    margin: 0 0 24px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
    border-radius: 12px;
    color: #1a5f3f;
    font-weight: 500;
}

.share-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

.share-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 20px;
    border: none;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: white;
    position: relative;
    overflow: hidden;
}

.share-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.share-btn:hover::before {
    width: 300px;
    height: 300px;
}

.share-btn i {
    font-size: 1.1rem;
    z-index: 1;
}

.share-btn span {
    z-index: 1;
}

.share-btn-whatsapp {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.share-btn-whatsapp:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 211, 102, 0.4);
}

.share-btn-facebook {
    background: linear-gradient(135deg, #1877F2, #0C63D4);
}

.share-btn-facebook:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(24, 119, 242, 0.4);
}

.share-btn-twitter {
    background: linear-gradient(135deg, #1DA1F2, #0C85D0);
}

.share-btn-twitter:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(29, 161, 242, 0.4);
}

.share-btn-linkedin {
    background: linear-gradient(135deg, #0A66C2, #004182);
}

.share-btn-linkedin:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(10, 102, 194, 0.4);
}

.share-btn-telegram {
    background: linear-gradient(135deg, #0088CC, #006699);
}

.share-btn-telegram:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 136, 204, 0.4);
}

.share-btn-email {
    background: linear-gradient(135deg, #EA4335, #C5221F);
}

.share-btn-email:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(234, 67, 53, 0.4);
}

.share-btn-copy {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.share-btn-copy:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.share-btn-copy.success {
    background: linear-gradient(135deg, #56ab2f, #a8e063);
}

.share-modal-tip {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 10px;
    border-left: 3px solid #667eea;
}

.share-modal-tip i {
    color: #667eea;
    font-size: 1rem;
    margin-top: 2px;
}

.share-modal-tip small {
    color: #4a5568;
    line-height: 1.5;
    font-size: 0.85rem;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@media (max-width: 640px) {
    .share-buttons {
        grid-template-columns: 1fr;
    }
    .share-modal-content {
        width: 95%;
        margin: 20px;
    }
    .share-btn {
        padding: 16px 20px;
        font-size: 1rem;
    }
}

</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function editorApp() {
    return {
        photos: @json($photos),
        templates: @json($templates),
        luts: @json($luts),
        selectedTemplate: null,
        selectedLut: null,
        slotPhotos: {},
        draggedPhoto: null,
        zoomLevel: 100, // Percentage
        canvasScale: 1, // Actual scale multiplier
        canvasWidth: 0,
        canvasHeight: 0,
        
        init() {
            // Auto select first template if available
            if (this.templates.length > 0) {
                this.selectTemplate(this.templates[0]);
            }
            
            // Auto adjust canvas scale for device
            this.adjustCanvasScale();
            
            // Listen to window resize
            window.addEventListener('resize', () => {
                this.adjustCanvasScale();
            });
        },
        
        adjustCanvasScale() {
            if (!this.selectedTemplate) return;
            
            const wrapper = document.querySelector('.canvas-scroll-wrapper');
            if (!wrapper) return;
            
            const availableWidth = wrapper.clientWidth - 32; // padding
            const templateWidth = this.selectedTemplate.canvas_width;
            
            // Calculate scale to fit width on mobile
            const width = window.innerWidth;
            
            if (width <= 480) {
                // Mobile: fit to screen width
                this.canvasScale = Math.min(1, availableWidth / templateWidth);
                this.zoomLevel = Math.round(this.canvasScale * 100);
            } else if (width <= 768) {
                // Tablet: 80% fit
                this.canvasScale = Math.min(0.8, availableWidth / templateWidth);
                this.zoomLevel = Math.round(this.canvasScale * 100);
            } else {
                // Desktop: default zoom
                this.canvasScale = 0.5;
                this.zoomLevel = 50;
            }
            
            this.updateCanvasSize();
        },
        
        updateCanvasSize() {
            if (!this.selectedTemplate) return;
            
            this.canvasWidth = this.selectedTemplate.canvas_width * this.canvasScale;
            this.canvasHeight = this.selectedTemplate.canvas_height * this.canvasScale;
        },
        
        selectTemplate(template) {
            this.selectedTemplate = template;
            this.slotPhotos = {};
            
            // Auto fill slots with photos in order
            template.slots.forEach((slot, index) => {
                if (this.photos[index]) {
                    this.slotPhotos[slot.id] = this.photos[index];
                }
            });
            
            // Adjust canvas after template selected
            this.$nextTick(() => {
                this.adjustCanvasScale();
            });
        },

        selectLut(lut) {
            this.selectedLut = lut;
            
            // Update usage count if LUT is selected
            if (lut) {
                fetch(`/api/luts/${lut.id}/increment-usage`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                }).catch(err => console.log('LUT usage tracking failed:', err));
            }
        },
        
        handleDragStart(event, photo) {
            this.draggedPhoto = photo;
            event.dataTransfer.effectAllowed = 'copy';
            
            // Visual feedback
            event.target.style.opacity = '0.5';
        },
        
        handleDragEnd(event) {
            this.draggedPhoto = null;
            
            // Reset visual
            event.target.style.opacity = '1';
        },
        
        handleDrop(event, slot) {
            if (this.draggedPhoto) {
                this.slotPhotos[slot.id] = this.draggedPhoto;
            }
        },
        
        removePhotoFromSlot(slotId) {
            delete this.slotPhotos[slotId];
        },
        
        zoomIn() {
            this.zoomLevel = Math.min(150, this.zoomLevel + 10);
            this.canvasScale = this.zoomLevel / 100;
            this.updateCanvasSize();
        },
        
        zoomOut() {
            this.zoomLevel = Math.max(25, this.zoomLevel - 10);
            this.canvasScale = this.zoomLevel / 100;
            this.updateCanvasSize();
        },
        
        resetZoom() {
            this.adjustCanvasScale();
        },
        
        // Loading Modal Helper Functions
        showLoadingModal(message = 'Memproses...', progress = 0) {
            // Remove existing modal if any
            this.hideLoadingModal();
            
            const modal = document.createElement('div');
            modal.id = 'loading-modal';
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(10px);
                display: flex; align-items: center; justify-content: center;
                z-index: 10000; animation: fadeIn 0.3s ease-out;
            `;
            
            modal.innerHTML = `
                <div style="background: linear-gradient(135deg, rgba(255,255,255,0.98), rgba(255,255,255,0.95)); 
                            border-radius: 24px; padding: 40px 32px; text-align: center; 
                            max-width: 420px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.4);
                            animation: scaleIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                    
                    <!-- Spinner Icon -->
                    <div style="width: 80px; height: 80px; margin: 0 auto 24px;
                                background: linear-gradient(135deg, #667eea, #764ba2);
                                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                box-shadow: 0 8px 24px rgba(102, 126, 234, 0.5);
                                animation: spin 1.5s linear infinite;">
                        <i class="fas fa-magic" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    
                    <!-- Title -->
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #1a202c; margin: 0 0 12px 0;">
                        Memproses Foto
                    </h3>
                    
                    <!-- Message -->
                    <p id="loading-message" style="font-size: 1rem; color: #64748b; margin: 0 0 24px 0; min-height: 24px;">
                        ${message}
                    </p>
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; margin-bottom: 12px;">
                        <div id="progress-bar" style="height: 100%; background: linear-gradient(90deg, #667eea, #764ba2);
                                    border-radius: 4px; transition: width 0.3s ease-out; width: ${progress}%;
                                    box-shadow: 0 0 10px rgba(102, 126, 234, 0.6);"></div>
                    </div>
                    
                    <!-- Progress Percentage -->
                    <div id="progress-text" style="font-size: 0.875rem; color: #667eea; font-weight: 600;">
                        ${progress}%
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        },

        updateLoadingModal(message, progress) {
            const messageEl = document.getElementById('loading-message');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            
            if (messageEl) messageEl.textContent = message;
            if (progressBar) progressBar.style.width = progress + '%';
            if (progressText) progressText.textContent = progress + '%';
        },

        hideLoadingModal() {
            const modal = document.getElementById('loading-modal');
            if (modal) {
                modal.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => modal.remove(), 300);
            }
        },
        
        async downloadResult() {
            if (!this.selectedTemplate) {
                alert('Pilih template terlebih dahulu');
                return;
            }
            
            // Check empty slots
            const emptySlots = this.selectedTemplate.slots.filter(slot => !this.slotPhotos[slot.id]);
            if (emptySlots.length > 0) {
                if (!confirm('Beberapa slot masih kosong. Lanjutkan download?')) {
                    return;
                }
            }
            
            try {
                // Show loading modal
                this.showLoadingModal('Memulai proses...', 0);
                
                // Create canvas dari template ORIGINAL size
                const canvas = document.createElement('canvas');
                canvas.width = this.selectedTemplate.canvas_width;
                canvas.height = this.selectedTemplate.canvas_height;
                const ctx = canvas.getContext('2d', { alpha: false });
                
                // Enable high quality rendering
                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';
                
                // Set background putih
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                
                this.updateLoadingModal('Menyiapkan canvas...', 10);
                console.log('Starting render...', {
                    width: canvas.width,
                    height: canvas.height,
                    slots: this.selectedTemplate.slots.length
                });
                
                // LAYER 1: Render semua foto di slots
                const totalSlots = this.selectedTemplate.slots.filter(s => this.slotPhotos[s.id]).length;
                let processedSlots = 0;
                
                for (const slot of this.selectedTemplate.slots) {
                    if (!this.slotPhotos[slot.id]) {
                        console.log('Slot empty:', slot.id);
                        continue;
                    }
                    
                    const photo = this.slotPhotos[slot.id];
                    const progress = 10 + Math.round((processedSlots / totalSlots) * 40);
                    this.updateLoadingModal(`Memuat foto ${processedSlots + 1}/${totalSlots}...`, progress);
                    
                    console.log('Loading photo for slot:', slot.id, photo.full_url);
                    
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    
                    // Wait for image to load
                    await new Promise((resolve, reject) => {
                        img.onload = () => {
                            console.log('Photo loaded:', slot.id);
                            resolve();
                        };
                        img.onerror = (error) => {
                            console.error('Photo load error:', slot.id, error);
                            reject(error);
                        };
                        img.src = photo.full_url;
                    });
                    
                    // Save context state
                    ctx.save();
                    
                    // Apply rotation jika ada
                    if (slot.rotation && slot.rotation !== 0) {
                        const centerX = slot.x + slot.width / 2;
                        const centerY = slot.y + slot.height / 2;
                        ctx.translate(centerX, centerY);
                        ctx.rotate((slot.rotation * Math.PI) / 180);
                        ctx.translate(-centerX, -centerY);
                    }
                    
                    // Create clipping path untuk border radius
                    if (slot.border_radius && slot.border_radius > 0) {
                        const radius = slot.border_radius;
                        ctx.beginPath();
                        ctx.moveTo(slot.x + radius, slot.y);
                        ctx.lineTo(slot.x + slot.width - radius, slot.y);
                        ctx.quadraticCurveTo(slot.x + slot.width, slot.y, slot.x + slot.width, slot.y + radius);
                        ctx.lineTo(slot.x + slot.width, slot.y + slot.height - radius);
                        ctx.quadraticCurveTo(slot.x + slot.width, slot.y + slot.height, slot.x + slot.width - radius, slot.y + slot.height);
                        ctx.lineTo(slot.x + radius, slot.y + slot.height);
                        ctx.quadraticCurveTo(slot.x, slot.y + slot.height, slot.x, slot.y + slot.height - radius);
                        ctx.lineTo(slot.x, slot.y + radius);
                        ctx.quadraticCurveTo(slot.x, slot.y, slot.x + radius, slot.y);
                        ctx.closePath();
                        ctx.clip();
                    }
                    
                    // Calculate cover fit (object-fit: cover)
                    const imgRatio = img.width / img.height;
                    const slotRatio = slot.width / slot.height;
                    
                    let drawWidth, drawHeight, offsetX, offsetY;
                    
                    if (imgRatio > slotRatio) {
                        // Image lebih lebar, fit by height
                        drawHeight = slot.height;
                        drawWidth = img.width * (slot.height / img.height);
                        offsetX = (slot.width - drawWidth) / 2;
                        offsetY = 0;
                    } else {
                        // Image lebih tinggi, fit by width
                        drawWidth = slot.width;
                        drawHeight = img.height * (slot.width / img.width);
                        offsetX = 0;
                        offsetY = (slot.height - drawHeight) / 2;
                    }
                    
                    // Draw photo
                    ctx.drawImage(img, slot.x + offsetX, slot.y + offsetY, drawWidth, drawHeight);
                    console.log('Photo drawn:', slot.id);
                    
                    // Restore context
                    ctx.restore();
                    
                    processedSlots++;
                }
                
                this.updateLoadingModal('Semua foto berhasil dimuat!', 50);
                console.log('All photos rendered');
                
                // LAYER 2: Apply LUT Filter jika ada
                if (this.selectedLut) {
                    this.updateLoadingModal(`Menerapkan filter ${this.selectedLut.name}...`, 60);
                    console.log('Applying LUT filter:', this.selectedLut.name);
                    
                    // Get current canvas image data
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const data = imageData.data;
                    
                    // Apply LUT filter based on name
                    // Ini contoh sederhana - bisa disesuaikan dengan LUT file actual
                    this.applyLutFilter(data, this.selectedLut);
                    
                    // Put modified image data back
                    ctx.putImageData(imageData, 0, 0);
                    
                    this.updateLoadingModal('Filter berhasil diterapkan!', 75);
                    console.log('LUT filter applied');
                } else {
                    this.updateLoadingModal('Melewati filter...', 75);
                    console.log('LUT filter applied');
                }
                
                // LAYER 3: Render frame ORIGINAL dari database (on top)
                if (this.selectedTemplate.background_url) {
                    this.updateLoadingModal('Memuat frame...', 80);
                    console.log('Loading frame:', this.selectedTemplate.background_url);
                    
                    const frameImg = new Image();
                    frameImg.crossOrigin = 'anonymous';
                    
                    await new Promise((resolve, reject) => {
                        frameImg.onload = () => {
                            console.log('Frame loaded');
                            resolve();
                        };
                        frameImg.onerror = (error) => {
                            console.error('Frame load error:', error);
                            reject(error);
                        };
                        frameImg.src = this.selectedTemplate.background_url;
                    });
                    
                    // Draw frame dengan full resolution
                    ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
                    
                    this.updateLoadingModal('Frame berhasil dimuat!', 90);
                    console.log('Frame rendered');
                } else {
                    this.updateLoadingModal('Melewati frame...', 90);
                }
                
                this.updateLoadingModal('Menyiapkan download...', 95);
                console.log('Render complete, creating blob...');
                
                // Convert canvas to blob dan download
                canvas.toBlob((blob) => {
                    this.updateLoadingModal('Download dimulai!', 100);
                    
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    const fileName = this.selectedLut 
                        ? `foto-${this.selectedLut.name}-${Date.now()}.png`
                        : `foto-${Date.now()}.png`;
                    link.download = fileName;
                    link.href = url;
                    link.click();
                    URL.revokeObjectURL(url);
                    
                    console.log('Download started:', fileName);
                    
                    // Hide modal after short delay
                    setTimeout(() => {
                        this.hideLoadingModal();
                    }, 1000);
                }, 'image/png', 1.0);
                
            } catch (error) {
                console.error('Download error:', error);
                this.hideLoadingModal();
                alert('Gagal mendownload gambar: ' + error.message);
            }
        },


        async shareResult() {
            if (!this.selectedTemplate) {
                alert('Pilih template terlebih dahulu');
                return;
            }
            
            try {
                // Show loading modal
                this.showLoadingModal('Memulai proses...', 0);
                
                // Create canvas dari template ORIGINAL size
                const canvas = document.createElement('canvas');
                canvas.width = this.selectedTemplate.canvas_width;
                canvas.height = this.selectedTemplate.canvas_height;
                const ctx = canvas.getContext('2d', { alpha: false });
                
                // Enable high quality rendering
                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';
                
                // Set background putih
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                
                this.updateLoadingModal('Menyiapkan canvas...', 10);
                
                // LAYER 1: Render semua foto di slots
                const totalSlots = this.selectedTemplate.slots.filter(s => this.slotPhotos[s.id]).length;
                let processedSlots = 0;
                
                for (const slot of this.selectedTemplate.slots) {
                    if (!this.slotPhotos[slot.id]) continue;
                    
                    const photo = this.slotPhotos[slot.id];
                    const progress = 10 + Math.round((processedSlots / totalSlots) * 40);
                    this.updateLoadingModal(`Memuat foto ${processedSlots + 1}/${totalSlots}...`, progress);
                    
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    
                    await new Promise((resolve, reject) => {
                        img.onload = resolve;
                        img.onerror = reject;
                        img.src = photo.full_url;
                    });
                    
                    ctx.save();
                    
                    // Rotation
                    if (slot.rotation && slot.rotation !== 0) {
                        const centerX = slot.x + slot.width / 2;
                        const centerY = slot.y + slot.height / 2;
                        ctx.translate(centerX, centerY);
                        ctx.rotate((slot.rotation * Math.PI) / 180);
                        ctx.translate(-centerX, -centerY);
                    }
                    
                    // Border radius clipping
                    if (slot.border_radius && slot.border_radius > 0) {
                        const radius = slot.border_radius;
                        ctx.beginPath();
                        ctx.moveTo(slot.x + radius, slot.y);
                        ctx.lineTo(slot.x + slot.width - radius, slot.y);
                        ctx.quadraticCurveTo(slot.x + slot.width, slot.y, slot.x + slot.width, slot.y + radius);
                        ctx.lineTo(slot.x + slot.width, slot.y + slot.height - radius);
                        ctx.quadraticCurveTo(slot.x + slot.width, slot.y + slot.height, slot.x + slot.width - radius, slot.y + slot.height);
                        ctx.lineTo(slot.x + radius, slot.y + slot.height);
                        ctx.quadraticCurveTo(slot.x, slot.y + slot.height, slot.x, slot.y + slot.height - radius);
                        ctx.lineTo(slot.x, slot.y + radius);
                        ctx.quadraticCurveTo(slot.x, slot.y, slot.x + radius, slot.y);
                        ctx.closePath();
                        ctx.clip();
                    }
                    
                    // Cover fit calculation
                    const imgRatio = img.width / img.height;
                    const slotRatio = slot.width / slot.height;
                    
                    let drawWidth, drawHeight, offsetX, offsetY;
                    
                    if (imgRatio > slotRatio) {
                        drawHeight = slot.height;
                        drawWidth = img.width * (slot.height / img.height);
                        offsetX = (slot.width - drawWidth) / 2;
                        offsetY = 0;
                    } else {
                        drawWidth = slot.width;
                        drawHeight = img.height * (slot.width / img.width);
                        offsetX = 0;
                        offsetY = (slot.height - drawHeight) / 2;
                    }
                    
                    ctx.drawImage(img, slot.x + offsetX, slot.y + offsetY, drawWidth, drawHeight);
                    ctx.restore();
                    
                    processedSlots++;
                }
                
                this.updateLoadingModal('Semua foto berhasil dimuat!', 50);
                
                // LAYER 2: Apply LUT Filter jika ada
                if (this.selectedLut) {
                    this.updateLoadingModal(`Menerapkan filter ${this.selectedLut.name}...`, 60);
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const data = imageData.data;
                    this.applyLutFilter(data, this.selectedLut);
                    ctx.putImageData(imageData, 0, 0);
                    this.updateLoadingModal('Filter berhasil diterapkan!', 75);
                } else {
                    this.updateLoadingModal('Melewati filter...', 75);
                }
                
                // LAYER 3: Render frame original
                if (this.selectedTemplate.background_url) {
                    this.updateLoadingModal('Memuat frame...', 80);
                    const frameImg = new Image();
                    frameImg.crossOrigin = 'anonymous';
                    
                    await new Promise((resolve, reject) => {
                        frameImg.onload = resolve;
                        frameImg.onerror = reject;
                        frameImg.src = this.selectedTemplate.background_url;
                    });
                    
                    ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
                    this.updateLoadingModal('Frame berhasil dimuat!', 90);
                } else {
                    this.updateLoadingModal('Melewati frame...', 90);
                }
                
                this.updateLoadingModal('Menyiapkan untuk dibagikan...', 95);
                
                // Share dengan native API atau modal
                canvas.toBlob(async (blob) => {
                    const fileName = this.selectedLut 
                        ? `foto-${this.selectedLut.name}-${Date.now()}.png`
                        : `foto-${Date.now()}.png`;
                    
                    const file = new File([blob], fileName, { type: 'image/png' });
                    
                    // Cek apakah browser support native share
                    const canShareFiles = navigator.share && navigator.canShare && navigator.canShare({ files: [file] });
                    
                    if (canShareFiles) {
                        try {
                            // Hide loading modal
                            this.hideLoadingModal();
                            
                            console.log('Attempting native share...');
                            
                            // NATIVE SHARE - Popup akan muncul langsung!
                            await navigator.share({
                                files: [file],
                                title: 'Foto dari Memora',
                                text: 'Lihat foto saya!'
                            });
                            
                            console.log('✅ Share successful!');
                            
                        } catch (error) {
                            console.error('Share error:', error.name, error.message);
                            
                            // User cancelled - OK, no action needed
                            if (error.name === 'AbortError') {
                                console.log('User cancelled share');
                                return;
                            }
                            
                            // Error lain - tampilkan modal fallback
                            this.showShareModal(blob, fileName);
                        }
                    } else {
                        // Browser tidak support native share - tampilkan modal dengan opsi social media
                        this.hideLoadingModal();
                        this.showShareModal(blob, fileName);
                    }
                }, 'image/png', 1.0);
                
            } catch (error) {
                console.error('Share error:', error);
                this.hideLoadingModal();
                alert('Gagal membagikan gambar: ' + error.message);
            }
        },
        
        // Tampilkan modal share dengan social media options
        showShareModal(blob, fileName) {
            // Download file dulu
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.download = fileName;
            link.href = url;
            link.click();
            
            // Buat modal HTML
            const modal = document.createElement('div');
            modal.className = 'share-modal';
            modal.innerHTML = `
                <div class="share-modal-overlay"></div>
                <div class="share-modal-content">
                    <div class="share-modal-header">
                        <h3><i class="fas fa-share-nodes"></i> Bagikan Foto</h3>
                        <button class="share-modal-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="share-modal-body">
                        <p class="share-modal-message">
                            <i class="fas fa-check-circle"></i>
                            Foto berhasil didownload! Pilih platform untuk berbagi:
                        </p>
                        <div class="share-buttons">
                            <a href="whatsapp://send?text=${encodeURIComponent('Lihat foto saya dari Memora! ' + window.location.origin)}" 
                               class="share-btn share-btn-whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="share-btn share-btn-facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=${encodeURIComponent('Lihat foto saya dari Memora!')}&url=${encodeURIComponent(window.location.href)}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="share-btn share-btn-twitter">
                                <i class="fab fa-twitter"></i>
                                <span>Twitter</span>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(window.location.href)}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="share-btn share-btn-linkedin">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                            <a href="https://t.me/share/url?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent('Lihat foto saya dari Memora!')}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="share-btn share-btn-telegram">
                                <i class="fab fa-telegram-plane"></i>
                                <span>Telegram</span>
                            </a>
                            <a href="mailto:?subject=${encodeURIComponent('Foto dari Memora')}&body=${encodeURIComponent('Lihat foto saya! ' + window.location.href)}" 
                               class="share-btn share-btn-email">
                                <i class="fas fa-envelope"></i>
                                <span>Email</span>
                            </a>
                            <button class="share-btn share-btn-copy" id="copyLinkBtn">
                                <i class="fas fa-link"></i>
                                <span>Salin Link</span>
                            </button>
                        </div>
                        <div class="share-modal-tip">
                            <i class="fas fa-info-circle"></i>
                            <small>Foto sudah didownload ke perangkat Anda. Upload foto tersebut saat berbagi ke platform pilihan.</small>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Setup event listeners
            const overlay = modal.querySelector('.share-modal-overlay');
            const closeBtn = modal.querySelector('.share-modal-close');
            const copyBtn = modal.querySelector('#copyLinkBtn');
            
            const closeModal = () => {
                modal.remove();
                URL.revokeObjectURL(url);
            };
            
            overlay.addEventListener('click', closeModal);
            closeBtn.addEventListener('click', closeModal);
            
            copyBtn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    copyBtn.innerHTML = '<i class="fas fa-check"></i><span>Tersalin!</span>';
                    copyBtn.classList.add('success');
                    setTimeout(() => {
                        copyBtn.innerHTML = '<i class="fas fa-link"></i><span>Salin Link</span>';
                        copyBtn.classList.remove('success');
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                }
            });
            
            // Cleanup URL setelah 10 detik
            setTimeout(() => {
                URL.revokeObjectURL(url);
            }, 10000);
        },

        // Helper function untuk apply LUT filter
        applyLutFilter(data, lut) {
            // Ini adalah contoh sederhana LUT filter
            // Bisa disesuaikan berdasarkan LUT file yang sebenarnya
            
            // Untuk implementasi proper, perlu:
            // 1. Load LUT cube file dari lut.file_path
            // 2. Parse LUT data
            // 3. Apply color transformation
            
            // Sementara ini gunakan predefined filters berdasarkan nama
            const filterName = lut.name.toLowerCase();
            
            for (let i = 0; i < data.length; i += 4) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                
                // Example filters - customize based on your LUT names
                if (filterName.includes('vintage') || filterName.includes('warm')) {
                    // Warm vintage look
                    data[i] = Math.min(255, r * 1.1);
                    data[i + 1] = Math.min(255, g * 1.05);
                    data[i + 2] = Math.min(255, b * 0.9);
                } else if (filterName.includes('cool') || filterName.includes('blue')) {
                    // Cool blue look
                    data[i] = Math.min(255, r * 0.9);
                    data[i + 1] = Math.min(255, g * 1.0);
                    data[i + 2] = Math.min(255, b * 1.1);
                } else if (filterName.includes('bw') || filterName.includes('mono')) {
                    // Black and white
                    const gray = (r + g + b) / 3;
                    data[i] = gray;
                    data[i + 1] = gray;
                    data[i + 2] = gray;
                } else if (filterName.includes('sepia')) {
                    // Sepia tone
                    data[i] = Math.min(255, (r * 0.393) + (g * 0.769) + (b * 0.189));
                    data[i + 1] = Math.min(255, (r * 0.349) + (g * 0.686) + (b * 0.168));
                    data[i + 2] = Math.min(255, (r * 0.272) + (g * 0.534) + (b * 0.131));
                } else if (filterName.includes('bright')) {
                    // Brightness boost
                    data[i] = Math.min(255, r * 1.2);
                    data[i + 1] = Math.min(255, g * 1.2);
                    data[i + 2] = Math.min(255, b * 1.2);
                } else if (filterName.includes('contrast')) {
                    // Contrast boost
                    const factor = 1.3;
                    data[i] = Math.min(255, Math.max(0, ((r - 128) * factor) + 128));
                    data[i + 1] = Math.min(255, Math.max(0, ((g - 128) * factor) + 128));
                    data[i + 2] = Math.min(255, Math.max(0, ((b - 128) * factor) + 128));
                }
                // Add more filters as needed based on your LUT names
            }
        },


    }
}
</script>
@endpush