@extends('app')

@section('title', 'Gallery - Memora')

@section('content')
<div class="container" x-data="galleryApp()" x-init="init()">
    
    <!-- Hero Section -->
    <div class="hero">
        <h1 class="hero-title">
            ✨ Kenangan Spesial
        </h1>
        <p class="hero-subtitle">Simpan momen tak terlupakan Anda di hari istimewa ini</p>
    </div>

    <!-- Tutorial Section -->
    <div class="tutorial-section" x-data="{ open: false }">
        <div class="tutorial-header" @click="open = !open">
            <div class="tutorial-title">
                <div class="tutorial-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <span>Cara Menggunakan Memora</span>
            </div>
            <div class="tutorial-toggle" :class="open ? 'open' : ''">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <div x-show="open" 
             x-collapse
             class="tutorial-content">
            <div class="tutorial-steps">
                <div class="tutorial-step">
                    <div class="tutorial-step-number">1</div>
                    <div class="tutorial-step-content">
                        <h4>Filter Foto</h4>
                        <p>Gunakan filter tanggal dan jam untuk menemukan foto di waktu tertentu.</p>
                    </div>
                </div>
                
                <div class="tutorial-step">
                    <div class="tutorial-step-number">2</div>
                    <div class="tutorial-step-content">
                        <h4>Pilih Foto</h4>
                        <p>Klik pada foto untuk memilih (checkbox overlay akan muncul).</p>
                    </div>
                </div>
                
                <div class="tutorial-step">
                    <div class="tutorial-step-number">3</div>
                    <div class="tutorial-step-content">
                        <h4>Edit Foto</h4>
                        <p>Setelah memilih foto, klik tombol "Edit Foto" untuk membuat kolase.</p>
                    </div>
                </div>
                
                <div class="tutorial-step">
                    <div class="tutorial-step-number">4</div>
                    <div class="tutorial-step-content">
                        <h4>Preview Detail</h4>
                        <p>Tahan foto selama 1 detik untuk melihat preview besar dan opsi download/share.</p>
                    </div>
                </div>
            </div>
            
            <div class="tutorial-tips">
                <div class="tutorial-tips-title">
                    <i class="fas fa-star"></i>
                    <span>Tips Berguna</span>
                </div>
                <ul>
                    <li>Gunakan filter tanggal untuk melihat foto di hari tertentu</li>
                    <li>Filter jam membantu menemukan foto di rentang waktu spesifik</li>
                    <li>Kombinasikan filter untuk hasil yang lebih presisi</li>
                    <li>Pilih 2-4 foto untuk hasil kolase terbaik</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Section -->
    <div class="filter-panel">
        <div class="filter-header">
            <h3><i class="fas fa-sliders-h"></i> Filter Pencarian</h3>
            <button @click="showAdvancedFilters = !showAdvancedFilters" class="filter-toggle-btn">
                <span x-text="showAdvancedFilters ? 'Sembunyikan' : 'Tampilkan'"></span>
                <i class="fas fa-chevron-down" :class="showAdvancedFilters ? 'rotate-180' : ''"></i>
            </button>
        </div>

        <div x-show="showAdvancedFilters" x-collapse class="filter-content">
            <form method="GET" action="{{ route('gallery') }}" class="filter-form">

                <!-- Time Range Filter -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-clock"></i> 
                        Rentang Waktu
                    </label>
                    <div class="time-range-inputs">
                        <div class="time-input-group">
                            <label class="time-input-label">Dari Jam</label>
                            <select name="start_hour" x-model="filterStartHour" class="filter-select">
                                <option value="">--</option>
                                @for($i = 0; $i < 24; $i++)
                                    <option value="{{ $i }}" {{ request('start_hour') == $i ? 'selected' : '' }}>
                                        {{ sprintf('%02d:00', $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="time-range-separator">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="time-input-group">
                            <label class="time-input-label">Sampai Jam</label>
                            <select name="end_hour" x-model="filterEndHour" class="filter-select">
                                <option value="">--</option>
                                @for($i = 0; $i < 24; $i++)
                                    <option value="{{ $i }}" {{ request('end_hour') == $i ? 'selected' : '' }}>
                                        {{ sprintf('%02d:59', $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Session Code Filter -->
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-tag"></i> 
                        Kode Sesi
                    </label>
                    <select name="session_code" x-model="filterSessionCode" class="filter-select">
                        <option value="">Semua Sesi</option>
                        @foreach($sessionCodes as $code)
                            <option value="{{ $code }}" {{ request('session_code') == $code ? 'selected' : '' }}>
                                {{ $code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('gallery') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset Filter
                    </a>
                </div>

                <!-- Active Filters Display -->
                <div class="active-filters" x-show="hasActiveFilters()">
                    <span class="active-filters-label">Filter Aktif:</span>
                    <div class="active-filters-list">
                        <span x-show="filterDate" class="filter-tag">
                            <i class="fas fa-calendar"></i> 
                            <span x-text="getDateLabel(filterDate)"></span>
                            <button type="button" @click="clearFilterField('date')" class="filter-tag-remove">×</button>
                        </span>
                        <span x-show="filterStartHour !== ''" class="filter-tag">
                            <i class="fas fa-clock"></i> 
                            Dari: <span x-text="sprintf('%02d:00', filterStartHour)"></span>
                            <button type="button" @click="clearFilterField('start_hour')" class="filter-tag-remove">×</button>
                        </span>
                        <span x-show="filterEndHour !== ''" class="filter-tag">
                            <i class="fas fa-clock"></i> 
                            Sampai: <span x-text="sprintf('%02d:59', filterEndHour)"></span>
                            <button type="button" @click="clearFilterField('end_hour')" class="filter-tag-remove">×</button>
                        </span>
                        <span x-show="filterSessionCode" class="filter-tag">
                            <i class="fas fa-tag"></i> 
                            <span x-text="filterSessionCode"></span>
                            <button type="button" @click="clearFilterField('session_code')" class="filter-tag-remove">×</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Search -->
    <div class="quick-search-container">
        <div class="search-wrapper">
            <label class="filter-label">
                <i class="fas fa-search"></i> 
                Pencarian Cepat
            </label>
            <div style="position: relative;">
                <input 
                    type="text" 
                    x-model="searchCode"
                    @input="filterPhotos()"
                    placeholder="Cari berdasarkan kode sesi..."
                    class="search-input"
                >
                <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Selected Photos Info -->
    <div x-show="selectedPhotos.length > 0" x-cloak class="selection-card">
        <div class="selection-info">
            <div class="selection-count">
                <i class="fas fa-check-circle"></i>
                <span x-text="selectedPhotos.length"></span> foto dipilih
            </div>
            <div class="selection-actions">
                <button @click="clearSelection()" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batalkan
                </button>
                <button @click="goToEditor()" class="btn btn-primary">
                    <i class="fas fa-wand-magic-sparkles"></i> Edit Foto
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="filteredPhotos.length === 0" x-cloak class="empty-state">
        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="empty-title">Tidak Ada Foto Ditemukan</h3>
        <p class="empty-text">
            <span x-show="hasActiveFilters()">Coba ubah filter atau reset untuk melihat semua foto</span>
            <span x-show="!hasActiveFilters()">Belum ada foto yang tersedia</span>
        </p>
    </div>

    <!-- Photo Grid -->
    <div x-show="filteredPhotos.length > 0" class="photo-grid">
        <template x-for="photo in filteredPhotos" :key="photo.id">
            <div 
                class="photo-card-modern"
                :class="{ 'selected': selectedPhotos.includes(photo.id) }"
                @click="handlePhotoClick(photo, $event)"
                @touchstart="handlePressStart(photo, $event)"
                @touchend="handlePressEnd()"
                @touchcancel="handlePressEnd()"
                @mousedown="handlePressStart(photo, $event)"
                @mouseup="handlePressEnd()"
                @mouseleave="handlePressEnd()"
            >
                <!-- Photo Image Container -->
                <div class="photo-image-container">
                    <img 
                        :src="`/storage/${photo.file_path}`"
                        :alt="`Photo ${photo.id}`"
                        class="photo-img"
                        loading="lazy"
                    >
                    
                    <!-- Full Overlay Checkbox -->
                    <div class="photo-overlay-checkbox" 
                         :class="{ 'visible': selectedPhotos.includes(photo.id) }"
                         @click.stop="togglePhoto(photo.id)">
                        <div class="overlay-bg"></div>
                        <div class="checkbox-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="checkbox-label" x-show="!selectedPhotos.includes(photo.id)">
                            Pilih Foto
                        </span>
                        <span class="checkbox-label selected-label" x-show="selectedPhotos.includes(photo.id)">
                            Dipilih
                        </span>
                    </div>
                </div>

                <!-- Photo Info -->
                <div class="photo-info-modern">
                    <div class="photo-meta">
                        <span class="photo-session">
                            <i class="fas fa-tag"></i>
                            <span x-text="photo.session_code"></span>
                        </span>
                        <span class="photo-time">
                            <i class="fas fa-clock"></i>
                            <span x-text="formatTime(photo.created_at)"></span>
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Preview Modal - Modern Design -->
    <div x-show="showPreview" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="closePreview()"
         class="preview-modal-modern">
        
        <div class="preview-backdrop" @click="closePreview()"></div>
        
        <div class="preview-container" 
             x-transition:enter="transition ease-out duration-300 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.stop>
            
            <button @click="closePreview()" class="preview-close-btn">
                <i class="fas fa-times"></i>
            </button>

            <template x-if="previewPhoto">
                <div class="preview-content-wrapper">
                    <!-- Image Section -->
                    <div class="preview-image-section">
                        <img :src="`/storage/${previewPhoto.file_path}`" 
                             :alt="`Photo ${previewPhoto.id}`" 
                             class="preview-img">
                    </div>
                    
                    <!-- Info Section -->
                    <div class="preview-info-section">
                        <h3 class="preview-title">Detail Foto</h3>
                        
                        <div class="preview-meta-grid">
                            <div class="preview-meta-card">
                                <div class="meta-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="meta-content">
                                    <span class="meta-label">Kode Sesi</span>
                                    <span class="meta-value" x-text="previewPhoto.session_code"></span>
                                </div>
                            </div>
                            
                            <div class="preview-meta-card">
                                <div class="meta-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="meta-content">
                                    <span class="meta-label">Tanggal</span>
                                    <span class="meta-value" x-text="formatDate(previewPhoto.created_at)"></span>
                                </div>
                            </div>
                            
                            <div class="preview-meta-card">
                                <div class="meta-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="meta-content">
                                    <span class="meta-label">Waktu</span>
                                    <span class="meta-value" x-text="formatTime(previewPhoto.created_at)"></span>
                                </div>
                            </div>
                            
                            <div class="preview-meta-card">
                                <div class="meta-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="meta-content">
                                    <span class="meta-label">ID Foto</span>
                                    <span class="meta-value" x-text="`#${previewPhoto.id}`"></span>
                                </div>
                            </div>
                        </div>

                        <div class="preview-actions-grid">
                            <button @click="downloadPreviewPhoto()" class="preview-action-btn primary">
                                <i class="fas fa-download"></i>
                                <span>Download</span>
                            </button>
                            <button @click="sharePreviewPhoto()" class="preview-action-btn secondary">
                                <i class="fas fa-share-alt"></i>
                                <span>Share</span>
                            </button>
                            <button @click="selectPreviewPhoto()" class="preview-action-btn success">
                                <i class="fas fa-check"></i>
                                <span>Pilih Foto</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function galleryApp() {
    return {
        allPhotos: @json($photos),
        filteredPhotos: [],
        selectedPhotos: [],
        searchCode: '',
        showPreview: false,
        previewPhoto: null,
        longPressTimer: null,
        longPressDuration: 1000,
        isLongPress: false,
        showAdvancedFilters: {{ request()->hasAny(['date', 'start_hour', 'end_hour', 'session_code']) ? 'true' : 'false' }},
        filterDate: '{{ request("date") }}',
        filterStartHour: '{{ request("start_hour") }}',
        filterEndHour: '{{ request("end_hour") }}',
        filterSessionCode: '{{ request("session_code") }}',
        
        init() {
            this.filteredPhotos = this.allPhotos;
            
            // Load selected photos from localStorage
            const saved = localStorage.getItem('selectedPhotos');
            if (saved) {
                this.selectedPhotos = JSON.parse(saved);
            }

            // Show advanced filters if any filter is active
            if (this.hasActiveFilters()) {
                this.showAdvancedFilters = true;
            }
        },
        
        filterPhotos() {
            if (this.searchCode === '') {
                this.filteredPhotos = this.allPhotos;
            } else {
                this.filteredPhotos = this.allPhotos.filter(photo => 
                    photo.session_code.toLowerCase().includes(this.searchCode.toLowerCase())
                );
            }
        },
        
        togglePhoto(photoId) {
            const index = this.selectedPhotos.indexOf(photoId);
            if (index > -1) {
                this.selectedPhotos.splice(index, 1);
            } else {
                this.selectedPhotos.push(photoId);
            }
            
            // Save to localStorage
            localStorage.setItem('selectedPhotos', JSON.stringify(this.selectedPhotos));
            
            // Haptic feedback if available
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        },
        
        clearSelection() {
            this.selectedPhotos = [];
            localStorage.removeItem('selectedPhotos');
        },
        
        goToEditor() {
            if (this.selectedPhotos.length === 0) {
                alert('Pilih minimal 1 foto untuk diedit');
                return;
            }
            
            window.location.href = `/editor?photos=${this.selectedPhotos.join(',')}`;
        },
        
        handlePhotoClick(photo, event) {
            // If it was a long press, don't toggle
            if (this.isLongPress) {
                this.isLongPress = false;
                return;
            }
            
            // Regular click toggles selection
            this.togglePhoto(photo.id);
        },
        
        handlePressStart(photo, event) {
            this.isLongPress = false;
            
            this.longPressTimer = setTimeout(() => {
                this.isLongPress = true;
                this.openPreview(photo);
                
                if (navigator.vibrate) {
                    navigator.vibrate([50, 50, 50]); // Pattern vibration
                }
            }, this.longPressDuration);
        },
        
        handlePressEnd() {
            if (this.longPressTimer) {
                clearTimeout(this.longPressTimer);
                this.longPressTimer = null;
            }
        },
        
        openPreview(photo) {
            this.previewPhoto = photo;
            this.showPreview = true;
            document.body.style.overflow = 'hidden';
        },
        
        closePreview() {
            this.showPreview = false;
            this.previewPhoto = null;
            this.isLongPress = false;
            document.body.style.overflow = '';
        },
        
        downloadPreviewPhoto() {
            if (!this.previewPhoto) return;
            
            fetch(`/storage/${this.previewPhoto.file_path}`)
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = `photo-${this.previewPhoto.session_code}-${this.previewPhoto.id}.jpg`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error('Download error:', error);
                    // Fallback
                    const link = document.createElement('a');
                    link.href = `/storage/${this.previewPhoto.file_path}`;
                    link.download = `photo-${this.previewPhoto.session_code}-${this.previewPhoto.id}.jpg`;
                    link.target = '_blank';
                    link.click();
                });
        },
        
        async sharePreviewPhoto() {
            if (!this.previewPhoto) return;
            
            try {
                const response = await fetch(`/storage/${this.previewPhoto.file_path}`);
                const blob = await response.blob();
                const file = new File([blob], `photo-${this.previewPhoto.session_code}.jpg`, { type: 'image/jpeg' });
                
                if (navigator.share && navigator.canShare({ files: [file] })) {
                    await navigator.share({
                        files: [file],
                        title: 'Photo Booth',
                        text: `Foto dari sesi ${this.previewPhoto.session_code}`
                    });
                } else {
                    alert('Share feature tidak didukung di browser ini. Silakan download foto dan bagikan manual.');
                }
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Share error:', error);
                }
            }
        },
        
        selectPreviewPhoto() {
            if (!this.previewPhoto) return;
            this.togglePhoto(this.previewPhoto.id);
            this.closePreview();
        },
        
        // Filter helpers
        hasActiveFilters() {
            return this.filterDate !== '' || 
                   this.filterStartHour !== '' || 
                   this.filterEndHour !== '' || 
                   this.filterSessionCode !== '';
        },
        
        clearFilterField(field) {
            if (field === 'date') this.filterDate = '';
            if (field === 'start_hour') this.filterStartHour = '';
            if (field === 'end_hour') this.filterEndHour = '';
            if (field === 'session_code') this.filterSessionCode = '';
        },
        
        getDateLabel(date) {
            if (date === 'today') return 'Hari Ini';
            if (date === 'yesterday') return 'Kemarin';
            return date;
        },
        
        sprintf(format, ...args) {
            return format.replace(/%(\d+\$)?([sd])/g, (match, index, type) => {
                const argIndex = index ? parseInt(index) - 1 : 0;
                const value = args[argIndex];
                return type === 'd' ? String(value).padStart(2, '0') : value;
            });
        },
        
        // Format helpers
        formatTime(datetime) {
            return new Date(datetime).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        formatDate(datetime) {
            return new Date(datetime).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
}
</script>
@endpush

@push('styles')
<style>
/* Modern Photo Card dengan Landscape 4:3 */
.photo-card-modern {
    position: relative;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.photo-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(31, 38, 135, 0.25);
    border-color: var(--primary);
}

.photo-card-modern.selected {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(204, 179, 108, 0.2);
}

/* Photo Image Container - 4:3 Aspect Ratio */
.photo-image-container {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3; /* Landscape 4:3 */
    overflow: hidden;
    background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
}

.photo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.photo-card-modern:hover .photo-img {
    transform: scale(1.08);
}

/* Full Overlay Checkbox */
.photo-overlay-checkbox {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    opacity: 0;
    transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 10;
    cursor: pointer;
}

.photo-card-modern:hover .photo-overlay-checkbox,
.photo-overlay-checkbox.visible {
    opacity: 1;
}

.overlay-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, 
        rgba(204, 179, 108, 0.9) 0%, 
        rgba(200, 174, 104, 0.85) 100%
    );
    backdrop-filter: blur(4px);
}

.checkbox-circle {
    position: relative;
    width: 4rem;
    height: 4rem;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 2;
}

.photo-card-modern:hover .checkbox-circle {
    transform: scale(1.1);
}

.checkbox-circle i {
    font-size: 2rem;
    color: var(--primary);
    transition: all 0.3s;
}

.photo-overlay-checkbox.visible .checkbox-circle {
    background: var(--primary);
    transform: scale(1.2) rotate(360deg);
}

.photo-overlay-checkbox.visible .checkbox-circle i {
    color: white;
}

.checkbox-label {
    position: relative;
    color: white;
    font-weight: 600;
    font-size: 1.125rem;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    z-index: 2;
    transition: all 0.3s;
}

.selected-label {
    font-size: 1.25rem;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

/* Photo Info Modern */
.photo-info-modern {
    padding: 1rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
}

.photo-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.photo-session,
.photo-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--gray-600);
    font-weight: 500;
}

.photo-session i,
.photo-time i {
    color: var(--primary);
}

/* Selection Card */
.selection-card {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(204, 179, 108, 0.3);
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.selection-card .selection-count {
    color: white;
}

.selection-card .selection-count i {
    color: white;
}

/* Modern Preview Modal */
.preview-modal-modern {
    position: fixed;
    inset: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.preview-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
}

.preview-container {
    position: relative;
    width: 100%;
    max-width: 1200px;
    max-height: 90vh;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.preview-close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 3rem;
    height: 3rem;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    color: white;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    z-index: 100;
}

.preview-close-btn:hover {
    background: rgba(239, 68, 68, 0.9);
    transform: rotate(90deg);
}

.preview-content-wrapper {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    max-height: 90vh;
}

.preview-image-section {
    position: relative;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.preview-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    max-height: 90vh;
}

.preview-info-section {
    padding: 2rem;
    overflow-y: auto;
    background: rgba(255, 255, 255, 0.98);
}

.preview-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.preview-meta-grid {
    display: grid;
    gap: 1rem;
    margin-bottom: 2rem;
}

.preview-meta-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(204, 179, 108, 0.1);
    border-radius: 0.75rem;
    border: 1px solid rgba(204, 179, 108, 0.2);
    transition: all 0.2s;
}

.preview-meta-card:hover {
    background: rgba(204, 179, 108, 0.15);
    transform: translateX(4px);
}

.meta-icon {
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.meta-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.meta-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.meta-value {
    font-size: 1rem;
    color: var(--gray-900);
    font-weight: 600;
}

.preview-actions-grid {
    display: grid;
    gap: 0.75rem;
}

.preview-action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.preview-action-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.preview-action-btn.secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.preview-action-btn.success {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
}

.preview-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

/* Responsive */
@media (max-width: 1024px) {
    .preview-content-wrapper {
        grid-template-columns: 1fr;
    }
    
    .preview-image-section {
        max-height: 50vh;
    }
}

@media (max-width: 768px) {
    .photo-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    
    .checkbox-circle {
        width: 3rem;
        height: 3rem;
    }
    
    .checkbox-circle i {
        font-size: 1.5rem;
    }
    
    .checkbox-label {
        font-size: 1rem;
    }
    
    .preview-info-section {
        padding: 1.5rem;
    }
    
    .preview-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .photo-grid {
        grid-template-columns: 1fr;
    }
}

/* ========================================
   Gallery Filter Components CSS
   ======================================== */


/* Filter Panel */
.filter-panel {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 1rem;
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    background: rgba(255, 255, 255, 0.5);
}

.filter-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a202c;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.filter-header h3 i {
    color: var(--primary, #3b82f6);
}

.filter-toggle-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(204, 179, 108, 0.1);
    border: 1px solid rgba(204, 179, 108, 0.2);
    border-radius: 0.5rem;
    color: var(--primary, #ccb36c);
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-toggle-btn:hover {
    background: rgba(204, 179, 108, 0.15);
    border-color: rgba(204, 179, 108, 0.3);
}

.filter-toggle-btn i {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.filter-toggle-btn i.rotate-180 {
    transform: rotate(180deg);
}

.filter-content {
    padding: 1.5rem;
}

.filter-form {
    display: grid;
    gap: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-label i {
    color: var(--primary, #3b82f6);
}

.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    font-size: 0.9375rem;
    color: #1a202c;
    font-family: 'Poppins', sans-serif;
    transition: all 0.2s;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary, #3b82f6);
    box-shadow: 0 0 0 3px rgba(204, 179, 108, 0.1);
}

.filter-select:hover {
    border-color: #d1d5db;
}

/* Time Range Inputs */
.time-range-inputs {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 1rem;
    align-items: end;
}

.time-input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.time-input-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
}

.time-range-separator {
    display: flex;
    align-items: center;
    padding-bottom: 0.75rem;
    color: #94a3b8;
    font-size: 1.25rem;
}

/* Filter Actions */
.filter-actions {
    display: flex;
    gap: 1rem;
    padding-top: 0.5rem;
}

.filter-actions .btn {
    flex: 1;
}

/* Active Filters */
.active-filters {
    padding-top: 1rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    margin-top: 0.5rem;
}

.active-filters-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.75rem;
    display: block;
}

.active-filters-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: rgba(204, 179, 108, 0.1);
    border: 1px solid rgba(204, 179, 108, 0.2);
    border-radius: 0.5rem;
    color: var(--primary, #ccb36c);
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.filter-tag:hover {
    background: rgba(204, 179, 108, 0.15);
}

.filter-tag i {
    font-size: 0.75rem;
}

.filter-tag-remove {
    width: 1.25rem;
    height: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(204, 179, 108, 0.2);
    border: none;
    border-radius: 50%;
    color: var(--primary, #ccb36c);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    margin-left: 0.25rem;
    line-height: 1;
}

.filter-tag-remove:hover {
    background: rgba(204, 179, 108, 0.3);
    transform: scale(1.1);
}

/* Quick Search */
.quick-search-container {
    margin-bottom: 2rem;
}

.search-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.search-input {
    width: 100%;
    padding: 0.875rem 1rem;
    padding-left: 3rem;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(12px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 0.75rem;
    font-size: 1rem;
    font-family: 'Poppins', sans-serif;
    color: #1a202c;
    transition: all 0.2s;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
}

.search-input:focus {
    outline: none;
    border-color: var(--primary, #ccb36c);
    box-shadow: 0 0 0 4px rgba(204, 179, 108, 0.1);
    background: white;
}

.search-input::placeholder {
    color: #94a3b8;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-size: 0.9375rem;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    text-decoration: none;
    white-space: nowrap;
    -webkit-tap-highlight-color: transparent;
    min-height: 44px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary, #ccb36c), var(--primary-dark, #c8ae68));
    color: white;
    box-shadow: 0 4px 12px rgba(204, 179, 108, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(204, 179, 108, 0.4);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.9);
    color: #374151;
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    background: white;
    border-color: var(--primary, #ccb36c);
    color: var(--primary, #ccb36c);
    transform: translateY(-2px);
}

/* Selection Card */
.selection-card {
    background: linear-gradient(135deg, var(--primary, #ccb36c) 0%, var(--primary-dark, #c8ae68) 100%);
    backdrop-filter: blur(12px);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(204, 179, 108, 0.3);
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.selection-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.selection-count {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    font-size: 1rem;
    color: white;
}

.selection-count i {
    color: white;
    font-size: 1.25rem;
}

.selection-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.selection-card .btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
}

.selection-card .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: white;
}

.selection-card .btn-primary {
    background: white;
    color: var(--primary, #ccb36c);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.selection-card .btn-primary:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

/* Tutorial Section */
.tutorial-section {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 1rem;
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
}

.tutorial-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    cursor: pointer;
    transition: background 0.2s;
}

.tutorial-header:hover {
    background: rgba(255, 255, 255, 0.5);
}

.tutorial-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    color: #1a202c;
}

.tutorial-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.tutorial-toggle {
    transition: transform 0.3s;
}

.tutorial-toggle.open {
    transform: rotate(180deg);
}

.tutorial-content {
    padding: 1.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.tutorial-steps {
    display: grid;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.tutorial-step {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.tutorial-step-number {
    width: 2rem;
    height: 2rem;
    background: linear-gradient(135deg, var(--primary, #ccb36c), var(--primary-dark, #c8ae68));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    flex-shrink: 0;
}

.tutorial-step-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #1a202c;
    margin: 0 0 0.25rem 0;
}

.tutorial-step-content p {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
    line-height: 1.5;
}

.tutorial-tips {
    padding: 1rem;
    background: rgba(204, 179, 108, 0.05);
    border-radius: 0.75rem;
    border: 1px solid rgba(204, 179, 108, 0.1);
}

.tutorial-tips-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--primary, #ccb36c);
    margin-bottom: 0.75rem;
}

.tutorial-tips ul {
    margin: 0;
    padding-left: 1.5rem;
}

.tutorial-tips li {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.tutorial-tips li:last-child {
    margin-bottom: 0;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
}

.empty-icon {
    width: 6rem;
    height: 6rem;
    color: #cbd5e1;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #374151;
    margin: 0 0 0.5rem 0;
}

.empty-text {
    font-size: 1rem;
    color: #64748b;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .time-range-inputs {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .time-range-separator {
        display: none;
    }
}

@media (max-width: 768px) {
    
    .filter-header {
        padding: 1rem;
    }
    
    .filter-content {
        padding: 1rem;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .selection-info {
        flex-direction: column;
        align-items: stretch;
    }
    
    .selection-actions {
        width: 100%;
    }
    
    .selection-actions .btn {
        flex: 1;
    }
    
    .tutorial-header {
        padding: 1rem;
    }
    
    .tutorial-content {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    
    .filter-toggle-btn span {
        display: none;
    }
}

/* Dark mode support (optional) */
@media (prefers-color-scheme: dark) {
    /* Add dark mode styles here if needed */
}

/* Print styles */
@media print {
    .filter-panel,
    .tutorial-section,
    .selection-card {
        display: none;
    }
}
</style>
@endpush