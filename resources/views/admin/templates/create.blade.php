@extends('admin.layout')

@section('title', 'Buat Template Baru')

@push('styles')
<style>
/* ========================================
   Template Builder - Unified Sidebar
   ======================================== */

.builder-container {
    min-height: calc(100vh - 150px);
}

.builder-header {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-2xl);
    padding: var(--spacing-2xl);
    margin-bottom: var(--spacing-2xl);
    box-shadow: var(--glass-shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--spacing-lg);
}

.builder-layout {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: var(--spacing-2xl);
    align-items: start;
}

/* ========================================
   UNIFIED SIDEBAR - Single Card Design
   ======================================== */
.builder-sidebar {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-2xl);
    box-shadow: var(--glass-shadow);
    position: sticky;
    top: 2rem;
    max-height: calc(100vh - 4rem);
    overflow-y: auto;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
}

/* Smooth scrollbar */
.builder-sidebar::-webkit-scrollbar {
    width: 8px;
}

.builder-sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 10px;
}

.builder-sidebar::-webkit-scrollbar-thumb {
    background: var(--gradient-blue);
    border-radius: 10px;
}

.builder-sidebar::-webkit-scrollbar-thumb:hover {
    background: var(--primary);
}

/* Sidebar Sections */
.sidebar-section {
    padding: var(--spacing-2xl);
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.sidebar-section:last-child {
    border-bottom: none;
    padding-bottom: var(--spacing-2xl);
}

.section-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-lg);
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
    flex: 1;
}

.section-title i {
    background: var(--gradient-blue);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 1.25rem;
}

.section-badge {
    background: var(--gradient-blue);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
}

/* ========================================
   Form Controls
   ======================================== */
.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: var(--spacing-sm);
    font-size: 0.875rem;
}

.form-group label i {
    color: var(--primary);
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-lg);
    font-size: 0.9375rem;
    transition: all var(--transition-base);
    color: var(--gray-700);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    background: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-group input[type="checkbox"] {
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
    accent-color: var(--primary);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    cursor: pointer;
}

/* Canvas Size Presets */
.preset-buttons {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
}

.preset-btn {
    padding: 0.75rem 0.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-lg);
    font-size: 0.8125rem;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-base);
    text-align: center;
    color: var(--gray-700);
}

.preset-btn:hover {
    background: white;
    border-color: var(--primary);
    transform: translateY(-2px);
    color: var(--primary);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

/* File Upload */
.file-upload-area {
    margin-top: var(--spacing-md);
}

.file-upload-label {
    display: block;
    padding: 1.5rem;
    border: 2px dashed rgba(255, 255, 255, 0.4);
    background: rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-lg);
    text-align: center;
    cursor: pointer;
    transition: all var(--transition-base);
}

.file-upload-label:hover {
    border-color: var(--primary);
    background: rgba(255, 255, 255, 0.5);
}

.file-upload-label i {
    display: block;
    font-size: 2rem;
    color: var(--primary);
    margin-bottom: var(--spacing-sm);
}

.file-upload-label span {
    display: block;
    color: var(--gray-600);
    font-size: 0.875rem;
}

.preview-image {
    position: relative;
    margin-top: var(--spacing-md);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.preview-image img {
    width: 100%;
    display: block;
}

.remove-preview {
    position: absolute;
    top: var(--spacing-sm);
    right: var(--spacing-sm);
    width: 2.5rem;
    height: 2.5rem;
    border: none;
    background: var(--gradient-sunset);
    color: white;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-base);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.remove-preview:hover {
    transform: scale(1.1) rotate(90deg);
}

/* Slots Management */
.slots-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.add-slot-btn {
    padding: 0.5rem 1rem;
    border: 2px solid var(--primary);
    background: var(--gradient-blue);
    color: white;
    border-radius: var(--radius-lg);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-base);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.add-slot-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

.slots-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
    max-height: 280px;
    overflow-y: auto;
    padding-right: var(--spacing-xs);
}

.slots-list::-webkit-scrollbar {
    width: 6px;
}

.slots-list::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

.slots-list::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.5);
    border-radius: 10px;
}

.slot-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-md);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all var(--transition-base);
    background: rgba(255, 255, 255, 0.3);
}

.slot-item:hover {
    background: rgba(255, 255, 255, 0.5);
    transform: translateX(5px);
}

.slot-item.active {
    border-color: var(--primary);
    background: rgba(59, 130, 246, 0.15);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

.slot-info strong {
    display: block;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
    font-size: 0.9375rem;
}

.slot-info small {
    color: var(--gray-600);
    font-size: 0.8125rem;
}

.slot-actions {
    display: flex;
    gap: var(--spacing-xs);
}

.slot-action-btn {
    width: 2rem;
    height: 2rem;
    border: none;
    background: transparent;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-base);
    display: flex;
    align-items: center;
    justify-content: center;
}

.slot-action-btn:hover {
    transform: scale(1.1);
}

.slot-action-btn.danger {
    color: var(--danger);
}

.slot-action-btn.danger:hover {
    background: rgba(239, 68, 68, 0.1);
}

.empty-slots {
    text-align: center;
    padding: var(--spacing-2xl);
    color: var(--gray-500);
}

.empty-slots i {
    font-size: 2.5rem;
    margin-bottom: var(--spacing-md);
    opacity: 0.5;
}

/* Slot Properties (when slot selected) */
.slot-properties {
    padding: var(--spacing-lg);
    background: rgba(59, 130, 246, 0.05);
    border-radius: var(--radius-lg);
    margin-top: var(--spacing-md);
}

.properties-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-md);
}

.properties-grid .form-group {
    margin-bottom: 0;
}

/* ========================================
   Canvas Area
   ======================================== */
.builder-canvas-wrapper {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-2xl);
    padding: var(--spacing-2xl);
    box-shadow: var(--glass-shadow);
    min-height: calc(100vh - 4rem);
    display: flex;
    flex-direction: column;
}

.canvas-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.zoom-controls {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    background: rgba(255, 255, 255, 0.5);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-lg);
}

.zoom-btn {
    width: 2.5rem;
    height: 2.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: white;
    border-radius: var(--radius-md);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-base);
    color: var(--gray-600);
}

.zoom-btn:hover:not(:disabled) {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.zoom-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.zoom-value {
    font-weight: 600;
    color: var(--gray-700);
    min-width: 4rem;
    text-align: center;
}

.canvas-info {
    display: flex;
    gap: var(--spacing-xl);
    color: var(--gray-600);
    font-weight: 500;
    font-size: 0.875rem;
}

.canvas-info span {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.canvas-scroll {
    flex: 1;
    overflow: auto;
    padding: var(--spacing-2xl);
    background: repeating-conic-gradient(#e5e7eb 0% 25%, #f3f4f6 0% 50%) 50% / 20px 20px;
    border-radius: var(--radius-lg);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 600px;
}

.canvas {
    position: relative;
    background: white;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    border: 2px solid var(--gray-200);
    background-size: cover;
    background-position: center;
}

/* Background Overlay - Layer paling atas */
.canvas-background-overlay {
    position: absolute;
    inset: 0;
    z-index: 30;
    pointer-events: none; /* Agar slots tetap bisa diklik */
    transition: opacity var(--transition-base);
}

.canvas-background-overlay img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.canvas-slot {
    position: absolute;
    background: rgba(59, 130, 246, 0.1);
    border: 2px solid var(--primary);
    cursor: move;
    transition: all 0.1s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.canvas-slot:hover {
    background: rgba(59, 130, 246, 0.15);
}

.canvas-slot.selected {
    border-color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    z-index: 50; /* Di atas overlay saat selected */
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
}

.slot-number {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background: var(--gradient-blue);
    color: white;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    pointer-events: none;
}

.canvas-slot.selected .slot-number {
    background: var(--gradient-sunset);
}

.resize-handle {
    position: absolute;
    width: 14px;
    height: 14px;
    background: white;
    border: 2px solid var(--primary);
    border-radius: 50%;
    opacity: 0;
    transition: all var(--transition-base);
}

.canvas-slot.selected .resize-handle {
    opacity: 1;
}

.resize-handle:hover {
    background: var(--primary);
    transform: scale(1.4);
}

.resize-handle.nw { top: -7px; left: -7px; cursor: nw-resize; }
.resize-handle.ne { top: -7px; right: -7px; cursor: ne-resize; }
.resize-handle.sw { bottom: -7px; left: -7px; cursor: sw-resize; }
.resize-handle.se { bottom: -7px; right: -7px; cursor: se-resize; }

/* ========================================
   Responsive
   ======================================== */
@media (max-width: 1200px) {
    .builder-layout {
        grid-template-columns: 1fr;
    }
    
    .builder-sidebar {
        position: relative;
        max-height: none;
    }
}

@media (max-width: 768px) {
    .preset-buttons {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .properties-grid {
        grid-template-columns: 1fr;
    }
    
    .canvas-toolbar {
        flex-direction: column;
        gap: var(--spacing-md);
    }
}
</style>
@endpush

@section('content')
<div class="builder-container" x-data="templateBuilder()">

    <!-- Header -->
    <div class="builder-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-magic"></i> Buat Template Baru
            </h1>
            <p class="page-subtitle">Desain template frame untuk memora Anda</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" @click="saveTemplate()" class="btn btn-primary" :disabled="saving">
                <i class="fas fa-save"></i> <span x-text="saving ? 'Menyimpan...' : 'Simpan Template'"></span>
            </button>
        </div>
    </div>

    <form id="templateForm" action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="builder-layout">

            <!-- Unified Sidebar -->
            <div class="builder-sidebar">

                <!-- Basic Info Section -->
                <div class="sidebar-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi Dasar
                        </h3>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-tag"></i>
                            Nama Template
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            x-model="template.name"
                            placeholder="Contoh: Template Wedding Classic"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-align-left"></i>
                            Deskripsi
                        </label>
                        <textarea 
                            name="description" 
                            x-model="template.description"
                            placeholder="Deskripsi singkat tentang template ini..."
                            rows="3"
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                x-model="template.is_active"
                                value="1"
                            >
                            <span>Aktifkan template setelah disimpan</span>
                        </label>
                    </div>
                </div>

                <!-- Canvas Settings Section -->
                <div class="sidebar-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-ruler-combined"></i>
                            Ukuran Canvas
                        </h3>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-arrows-alt-h"></i> Lebar (px)</label>
                        <input 
                            type="number" 
                            name="canvas_width" 
                            x-model.number="template.canvas_width"
                            min="600"
                            max="3000"
                        >
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-arrows-alt-v"></i> Tinggi (px)</label>
                        <input 
                            type="number" 
                            name="canvas_height" 
                            x-model.number="template.canvas_height"
                            min="800"
                            max="4000"
                        >
                    </div>

                    <div class="preset-buttons">
                        <button type="button" @click="setCanvasSize(1440, 2160)" class="preset-btn">
                            <div>4:6</div>
                            <small>1440x2160</small>
                        </button>
                        <button type="button" @click="setCanvasSize(1500, 2100)" class="preset-btn">
                            <div>5:7</div>
                            <small>1500x2100</small>
                        </button>
                        <button type="button" @click="setCanvasSize(1800, 1200)" class="preset-btn">
                            <div>3:2</div>
                            <small>1800x1200</small>
                        </button>
                    </div>
                </div>

                <!-- Background Overlay Section -->
                <div class="sidebar-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-layer-group"></i>
                            Background Overlay
                        </h3>
                    </div>

                    <div class="file-upload-area" x-show="!backgroundPreview">
                        <input 
                            type="file" 
                            name="background_image" 
                            id="backgroundInput"
                            accept="image/*"
                            @change="handleBackgroundUpload($event)"
                            style="display: none;"
                        >
                        <label for="backgroundInput" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Upload frame overlay</span>
                            <small style="display: block; margin-top: 0.5rem; color: var(--gray-500);">
                                Frame akan ditampilkan di atas foto
                            </small>
                        </label>
                    </div>

                    <div class="preview-image" x-show="backgroundPreview">
                        <img :src="backgroundPreview" alt="Preview">
                        <button type="button" @click="removeBackground()" class="remove-preview">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Opacity Control -->
                    <div class="form-group" x-show="backgroundPreview" style="margin-top: var(--spacing-lg);">
                        <label>
                            <i class="fas fa-adjust"></i>
                            Opacity Overlay
                        </label>
                        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
                            <input 
                                type="range" 
                                x-model.number="backgroundOpacity"
                                min="0"
                                max="100"
                                step="5"
                                style="flex: 1;"
                            >
                            <span style="min-width: 3rem; text-align: right; font-weight: 600;" x-text="`${backgroundOpacity}%`"></span>
                        </div>
                    </div>
                </div>

                <!-- Slots Management Section -->
                <div class="sidebar-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-th-large"></i>
                            Slot Foto
                        </h3>
                        <span class="section-badge" x-text="slots.length"></span>
                    </div>

                    <button type="button" @click="addSlot()" class="add-slot-btn">
                        <i class="fas fa-plus"></i>
                        Tambah Slot
                    </button>

                    <div class="slots-list" x-show="slots.length > 0">
                        <template x-for="(slot, index) in slots" :key="slot.id">
                            <div 
                                class="slot-item"
                                :class="selectedSlotIndex === index ? 'active' : ''"
                                @click="selectSlot(index)"
                            >
                                <div class="slot-info">
                                    <strong>Slot <span x-text="index + 1"></span></strong>
                                    <small x-text="`${Math.round(slot.width)}x${Math.round(slot.height)}px`"></small>
                                </div>
                                <div class="slot-actions">
                                    <button 
                                        type="button" 
                                        @click.stop="removeSlot(index)"
                                        class="slot-action-btn danger"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="empty-slots" x-show="slots.length === 0">
                        <i class="fas fa-image"></i>
                        <p>Belum ada slot foto.<br>Klik tombol di atas untuk menambah.</p>
                    </div>

                    <!-- Slot Properties (shown when slot is selected) -->
                    <div class="slot-properties" x-show="selectedSlot">
                        <h4 style="font-size: 0.9375rem; font-weight: 600; margin-bottom: var(--spacing-md);">
                            <i class="fas fa-cog"></i> Properti Slot <span x-text="selectedSlotIndex !== null ? (selectedSlotIndex + 1) : ''"></span>
                        </h4>
                        
                        <template x-if="selectedSlot">
                            <div class="properties-grid">
                                <div class="form-group">
                                    <label><i class="fas fa-border-style"></i> Border</label>
                                    <input 
                                        type="number" 
                                        x-model.number="selectedSlot.border_width"
                                        min="0"
                                        max="20"
                                    >
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-palette"></i> Warna</label>
                                    <input 
                                        type="color" 
                                        x-model="selectedSlot.border_color"
                                        style="height: 2.5rem; cursor: pointer;"
                                    >
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-circle"></i> Radius</label>
                                    <input 
                                        type="number" 
                                        x-model.number="selectedSlot.border_radius"
                                        min="0"
                                        max="100"
                                    >
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-redo"></i> Rotasi</label>
                                    <input 
                                        type="number" 
                                        x-model.number="selectedSlot.rotation"
                                        min="-180"
                                        max="180"
                                    >
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- Canvas Area -->
            <div class="builder-canvas-wrapper">
                <div class="canvas-toolbar">
                    <div class="zoom-controls">
                        <button type="button" @click="zoomOut()" class="zoom-btn" :disabled="zoom <= 0.2">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="zoom-value" x-text="`${Math.round(zoom * 100)}%`"></span>
                        <button type="button" @click="zoomIn()" class="zoom-btn" :disabled="zoom >= 1">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" @click="resetZoom()" class="zoom-btn">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                    <div class="canvas-info">
                        <span>
                            <i class="fas fa-ruler-combined"></i> 
                            <span x-text="`${template.canvas_width} x ${template.canvas_height}px`"></span>
                        </span>
                        <span>
                            <i class="fas fa-images"></i> 
                            <span x-text="`${slots.length} slot${slots.length !== 1 ? 's' : ''}`"></span>
                        </span>
                    </div>
                </div>

                <div class="canvas-scroll">
                    <div 
                        class="canvas" 
                        :style="`
                            width: ${template.canvas_width * zoom}px;
                            height: ${template.canvas_height * zoom}px;
                        `"
                        @click="selectedSlotIndex = null"
                    >
                        <!-- Slots Layer (Behind) -->
                        <template x-for="(slot, index) in slots" :key="slot.id">
                            <div 
                                class="canvas-slot"
                                :class="selectedSlotIndex === index ? 'selected' : ''"
                                :style="`
                                    left: ${slot.x * zoom}px;
                                    top: ${slot.y * zoom}px;
                                    width: ${slot.width * zoom}px;
                                    height: ${slot.height * zoom}px;
                                    transform: rotate(${slot.rotation}deg);
                                    border: ${slot.border_width}px solid ${slot.border_color};
                                    border-radius: ${slot.border_radius * zoom}px;
                                `"
                                @click.stop="selectSlot(index)"
                                @mousedown="startDrag($event, index)"
                            >
                                <div class="slot-number" x-text="index + 1"></div>
                                
                                <!-- Resize Handles -->
                                <div class="resize-handle nw" @mousedown.stop="startResize($event, index, 'nw')"></div>
                                <div class="resize-handle ne" @mousedown.stop="startResize($event, index, 'ne')"></div>
                                <div class="resize-handle sw" @mousedown.stop="startResize($event, index, 'sw')"></div>
                                <div class="resize-handle se" @mousedown.stop="startResize($event, index, 'se')"></div>
                            </div>
                        </template>

                        <!-- Background Overlay (On Top) -->
                        <div 
                            class="canvas-background-overlay" 
                            x-show="backgroundPreview"
                            :style="`opacity: ${backgroundOpacity / 100};`"
                        >
                            <img :src="backgroundPreview" alt="Background Overlay">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Hidden Inputs for Slots -->
        <template x-for="(slot, index) in slots" :key="slot.id">
            <div style="display: none;">
                <input type="hidden" :name="`slots[${index}][x]`" :value="Math.round(slot.x)">
                <input type="hidden" :name="`slots[${index}][y]`" :value="Math.round(slot.y)">
                <input type="hidden" :name="`slots[${index}][width]`" :value="Math.round(slot.width)">
                <input type="hidden" :name="`slots[${index}][height]`" :value="Math.round(slot.height)">
                <input type="hidden" :name="`slots[${index}][rotation]`" :value="slot.rotation">
                <input type="hidden" :name="`slots[${index}][border_style]`" :value="slot.border_style">
                <input type="hidden" :name="`slots[${index}][border_width]`" :value="slot.border_width">
                <input type="hidden" :name="`slots[${index}][border_color]`" :value="slot.border_color">
                <input type="hidden" :name="`slots[${index}][border_radius]`" :value="slot.border_radius">
            </div>
        </template>
    </form>

</div>
@endsection

@push('scripts')
<script>
function templateBuilder() {
    return {
        template: {
            name: '',
            description: '',
            canvas_width: 1200,
            canvas_height: 1800,
            is_active: true,
        },
        slots: [],
        selectedSlotIndex: null,
        backgroundPreview: null,
        backgroundOpacity: 100,
        zoom: 0.4,
        nextSlotId: 1,
        saving: false,
        
        // Drag/resize state
        isDragging: false,
        isResizing: false,
        resizeDirection: null,
        dragStart: { x: 0, y: 0 },
        slotStart: { x: 0, y: 0, width: 0, height: 0 },
        
        init() {
            document.addEventListener('mousemove', (e) => this.handleMouseMove(e));
            document.addEventListener('mouseup', (e) => this.handleMouseUp(e));
        },
        
        get selectedSlot() {
            return this.selectedSlotIndex !== null ? this.slots[this.selectedSlotIndex] : null;
        },
        
        setCanvasSize(width, height) {
            this.template.canvas_width = width;
            this.template.canvas_height = height;
        },
        
        handleBackgroundUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.backgroundPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        removeBackground() {
            this.backgroundPreview = null;
            document.getElementById('backgroundInput').value = '';
        },
        
        addSlot() {
            const centerX = (this.template.canvas_width - 300) / 2;
            const centerY = (this.template.canvas_height - 400) / 2;
            
            this.slots.push({
                id: this.nextSlotId++,
                x: Math.max(0, centerX),
                y: Math.max(0, centerY),
                width: 300,
                height: 400,
                rotation: 0,
                border_style: 'solid',
                border_width: 2,
                border_color: '#ffffff',
                border_radius: 0,
            });
            
            this.selectedSlotIndex = this.slots.length - 1;
        },
        
        removeSlot(index) {
            if (!confirm('Hapus slot ini?')) return;
            
            this.slots.splice(index, 1);
            if (this.selectedSlotIndex === index) {
                this.selectedSlotIndex = null;
            } else if (this.selectedSlotIndex > index) {
                this.selectedSlotIndex--;
            }
        },
        
        selectSlot(index) {
            this.selectedSlotIndex = index;
        },
        
        startDrag(event, index) {
            this.isDragging = true;
            this.selectedSlotIndex = index;
            this.dragStart = { x: event.clientX, y: event.clientY };
            this.slotStart = { ...this.slots[index] };
            event.preventDefault();
        },
        
        startResize(event, index, direction) {
            this.isResizing = true;
            this.resizeDirection = direction;
            this.selectedSlotIndex = index;
            this.dragStart = { x: event.clientX, y: event.clientY };
            this.slotStart = { ...this.slots[index] };
            event.preventDefault();
        },
        
        handleMouseMove(event) {
            if (this.isDragging && this.selectedSlotIndex !== null) {
                const dx = (event.clientX - this.dragStart.x) / this.zoom;
                const dy = (event.clientY - this.dragStart.y) / this.zoom;
                
                const slot = this.slots[this.selectedSlotIndex];
                slot.x = Math.max(0, Math.min(this.template.canvas_width - slot.width, this.slotStart.x + dx));
                slot.y = Math.max(0, Math.min(this.template.canvas_height - slot.height, this.slotStart.y + dy));
            } else if (this.isResizing && this.selectedSlotIndex !== null) {
                const dx = (event.clientX - this.dragStart.x) / this.zoom;
                const dy = (event.clientY - this.dragStart.y) / this.zoom;
                
                const slot = this.slots[this.selectedSlotIndex];
                const dir = this.resizeDirection;
                
                if (dir.includes('e')) {
                    slot.width = Math.max(50, this.slotStart.width + dx);
                }
                if (dir.includes('w')) {
                    const newWidth = Math.max(50, this.slotStart.width - dx);
                    const widthDiff = this.slotStart.width - newWidth;
                    slot.x = this.slotStart.x + widthDiff;
                    slot.width = newWidth;
                }
                if (dir.includes('s')) {
                    slot.height = Math.max(50, this.slotStart.height + dy);
                }
                if (dir.includes('n')) {
                    const newHeight = Math.max(50, this.slotStart.height - dy);
                    const heightDiff = this.slotStart.height - newHeight;
                    slot.y = this.slotStart.y + heightDiff;
                    slot.height = newHeight;
                }
            }
        },
        
        handleMouseUp(event) {
            this.isDragging = false;
            this.isResizing = false;
            this.resizeDirection = null;
        },
        
        zoomIn() {
            this.zoom = Math.min(1, this.zoom + 0.1);
        },
        
        zoomOut() {
            this.zoom = Math.max(0.2, this.zoom - 0.1);
        },
        
        resetZoom() {
            this.zoom = 0.4;
        },
        
        saveTemplate() {
            if (!this.template.name) {
                alert('Nama template harus diisi');
                return;
            }
            
            if (this.slots.length === 0) {
                alert('Tambahkan minimal 1 slot foto');
                return;
            }
            
            this.saving = true;
            document.getElementById('templateForm').submit();
        }
    }
}
</script>
@endpush