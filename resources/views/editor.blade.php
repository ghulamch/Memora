@extends('app')

@section('title', 'Editor - Photo Booth')

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
                    <span class="btn-label-mobile">Galeri</span>
                </a>
                <button @click="shareResult()" class="btn btn-success" :disabled="!selectedTemplate">
                    <i class="fas fa-share-nodes"></i> 
                    <span class="btn-text">Bagikan</span>
                    <span class="btn-label-mobile">Share</span>
                </button>
                <button @click="downloadResult()" class="btn btn-primary" :disabled="!selectedTemplate">
                    <i class="fas fa-download"></i> 
                    <span class="btn-text">Download</span>
                    <span class="btn-label-mobile">Simpan</span>
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
                <div 
                    class="canvas" 
                    x-ref="canvas"
                    :style="`
                        width: ${canvasScale * (selectedTemplate?.canvas_width || 1200)}px;
                        height: ${canvasScale * (selectedTemplate?.canvas_height || 1800)}px;
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

                <!-- Canvas Controls -->
                <div class="canvas-controls">
                    <button @click="zoomOut()" class="control-btn" :disabled="canvasScale <= 0.3" title="Zoom out">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="zoom-level" x-text="`${Math.round(canvasScale * 100)}%`"></span>
                    <button @click="zoomIn()" class="control-btn" :disabled="canvasScale >= 1.5" title="Zoom in">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button @click="resetZoom()" class="control-btn" title="Reset zoom">
                        <i class="fas fa-expand"></i>
                    </button>
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
/* Button Mobile Labels */
.btn .btn-text {
    display: inline;
}

.btn .btn-label-mobile {
    display: none;
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
    gap: var(--spacing-sm);
}

.lut-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm);
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
    /* This is a simplified version - real LUT would need canvas manipulation */
    filter: contrast(1.1) saturate(1.2);
}

.filter-badge {
    position: absolute;
    bottom: 80px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 0.875rem;
    font-weight: 500;
    z-index: 10;
}

@media (max-width: 768px) {
    .lut-list {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 480px) {
    /* Mobile Button Labels */
    .btn .btn-text {
        display: none;
    }
    
    .btn .btn-label-mobile {
        display: inline;
        font-size: 0.75rem;
    }
    
    .btn {
        flex-direction: column;
        gap: 0.25rem;
        padding: 0.625rem 0.875rem;
    }
    
    .btn i {
        font-size: 1.125rem;
    }
    
    .editor-actions {
        gap: 0.5rem;
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
        canvasScale: 0.5,
        
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
            const width = window.innerWidth;
            
            if (width <= 480) {
                this.canvasScale = 0.3; // Mobile
            } else if (width <= 768) {
                this.canvasScale = 0.4; // Tablet
            } else {
                this.canvasScale = 0.5; // Desktop
            }
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
        },

        selectLut(lut) {
            this.selectedLut = lut;
            
            // Update usage count if LUT is selected
            if (lut) {
                fetch(`/api/luts/${lut.id}/increment-usage`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
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
            this.canvasScale = Math.min(1.5, this.canvasScale + 0.1);
        },
        
        zoomOut() {
            this.canvasScale = Math.max(0.3, this.canvasScale - 0.1);
        },
        
        resetZoom() {
            this.adjustCanvasScale();
        },
        
        async downloadResult() {
            if (!this.selectedTemplate) {
                alert('Pilih template terlebih dahulu');
                return;
            }
            
            // Check if all slots are filled
            const allFilled = this.selectedTemplate.slots.every(slot => this.slotPhotos[slot.id]);
            if (!allFilled) {
                if (!confirm('Beberapa slot masih kosong. Lanjutkan download?')) {
                    return;
                }
            }
            
            try {
                // Hide remove buttons and placeholders temporarily
                const removeButtons = document.querySelectorAll('.remove-photo-btn');
                const placeholders = document.querySelectorAll('.slot-placeholder');
                const filterBadge = document.querySelector('.filter-badge');
                
                removeButtons.forEach(btn => btn.style.display = 'none');
                placeholders.forEach(ph => ph.style.display = 'none');
                if (filterBadge) filterBadge.style.display = 'none';
                
                const canvas = this.$refs.canvas;
                const scale = 3; // Higher quality for download
                
                const result = await html2canvas(canvas, {
                    scale: scale / this.canvasScale,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    imageTimeout: 0,
                    removeContainer: true,
                });
                
                // Restore UI elements
                removeButtons.forEach(btn => btn.style.display = '');
                placeholders.forEach(ph => ph.style.display = '');
                if (filterBadge) filterBadge.style.display = '';
                
                result.toBlob((blob) => {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    const fileName = this.selectedLut 
                        ? `photo-booth-${this.selectedLut.name}-${Date.now()}.png`
                        : `photo-booth-${Date.now()}.png`;
                    link.download = fileName;
                    link.href = url;
                    link.click();
                    URL.revokeObjectURL(url);
                }, 'image/png', 1.0);
            } catch (error) {
                console.error('Download error:', error);
                alert('Gagal mendownload gambar. Silakan coba lagi.');
                
                // Restore UI elements on error
                const removeButtons = document.querySelectorAll('.remove-photo-btn');
                const placeholders = document.querySelectorAll('.slot-placeholder');
                const filterBadge = document.querySelector('.filter-badge');
                removeButtons.forEach(btn => btn.style.display = '');
                placeholders.forEach(ph => ph.style.display = '');
                if (filterBadge) filterBadge.style.display = '';
            }
        },
        
        async shareResult() {
            if (!this.selectedTemplate) {
                alert('Pilih template terlebih dahulu');
                return;
            }
            
            try {
                const removeButtons = document.querySelectorAll('.remove-photo-btn');
                const placeholders = document.querySelectorAll('.slot-placeholder');
                const filterBadge = document.querySelector('.filter-badge');
                const canvasControls = document.querySelector('.canvas-controls');
                
                removeButtons.forEach(btn => btn.style.display = 'none');
                placeholders.forEach(ph => ph.style.display = 'none');
                if (filterBadge) filterBadge.style.display = 'none';
                if (canvasControls) canvasControls.style.display = 'none';
                
                const canvas = this.$refs.canvas;
                const scale = 3 / this.canvasScale;
                
                const result = await html2canvas(canvas, {
                    scale: scale,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    imageTimeout: 0,
                });
                
                removeButtons.forEach(btn => btn.style.display = '');
                placeholders.forEach(ph => ph.style.display = '');
                if (filterBadge) filterBadge.style.display = '';
                if (canvasControls) canvasControls.style.display = '';
                
                result.toBlob(async (blob) => {
                    const file = new File([blob], 'photo-booth.png', { type: 'image/png' });
                    
                    if (navigator.share && navigator.canShare({ files: [file] })) {
                        try {
                            await navigator.share({
                                files: [file],
                                title: 'Photo Booth',
                                text: 'Lihat hasil foto saya!'
                            });
                        } catch (error) {
                            if (error.name !== 'AbortError') {
                                this.fallbackShare(result);
                            }
                        }
                    } else {
                        this.fallbackShare(result);
                    }
                }, 'image/png', 1.0);
            } catch (error) {
                console.error('Share error:', error);
                alert('Gagal membagikan gambar.');
            }
        },
        
        fallbackShare(canvasElement) {
            const shareModal = document.createElement('div');
            shareModal.style.cssText = `
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.9); display: flex;
                align-items: center; justify-content: center;
                z-index: 10000; padding: 20px;
            `;
            shareModal.innerHTML = `
                <div style="background: white; border-radius: 16px; padding: 24px; max-width: 500px; width: 100%;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Bagikan Foto</h3>
                        <button onclick="this.closest('div[style*=fixed]').remove()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">×</button>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 12px;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}" target="_blank" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: #1877F2; color: white; border-radius: 12px; text-decoration: none;">
                            <i class="fab fa-facebook-f" style="font-size: 1.5rem;"></i>
                            <span style="font-size: 0.75rem;">Facebook</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}" target="_blank" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: #1DA1F2; color: white; border-radius: 12px; text-decoration: none;">
                            <i class="fab fa-twitter" style="font-size: 1.5rem;"></i>
                            <span style="font-size: 0.75rem;">Twitter</span>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=${encodeURIComponent('Lihat hasil foto saya! ' + window.location.href)}" target="_blank" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: #25D366; color: white; border-radius: 12px; text-decoration: none;">
                            <i class="fab fa-whatsapp" style="font-size: 1.5rem;"></i>
                            <span style="font-size: 0.75rem;">WhatsApp</span>
                        </a>
                        <a href="https://t.me/share/url?url=${encodeURIComponent(window.location.href)}" target="_blank" style="display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 16px; background: #0088cc; color: white; border-radius: 12px; text-decoration: none;">
                            <i class="fab fa-telegram-plane" style="font-size: 1.5rem;"></i>
                            <span style="font-size: 0.75rem;">Telegram</span>
                        </a>
                    </div>
                </div>
            `;
            document.body.appendChild(shareModal);
        }
    }
}
</script>
@endpush