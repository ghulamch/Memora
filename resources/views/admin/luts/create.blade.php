{{-- Create LUT Form --}}
@extends('admin.layout')

@section('title', 'Upload LUT Baru')

@section('content')
<div class="admin-container">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-palette"></i> Upload LUT Baru
            </h1>
            <p class="page-subtitle">Upload file Look-Up Table baru</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.luts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('admin.luts.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label required">Nama LUT</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') error @enderror"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Cinematic Warm Tone"
                    required
                >
                @error('name')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control @error('description') error @enderror"
                    rows="3"
                    placeholder="Deskripsi efek filter ini..."
                >{{ old('description') }}</textarea>
                @error('description')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="lut_file" class="form-label required">File LUT (.cube atau .3dl)</label>
                <div class="file-input-wrapper">
                    <input 
                        type="file" 
                        id="lut_file" 
                        name="lut_file" 
                        class="file-input @error('lut_file') error @enderror"
                        accept=".cube,.3dl"
                        required
                        onchange="updateFileName(this, 'lut-file-name')"
                    >
                    <label for="lut_file" class="file-label">
                        <i class="fas fa-upload"></i>
                        <span id="lut-file-name">Pilih file LUT</span>
                    </label>
                </div>
                <small class="form-help">Format: .cube atau .3dl, Max: 2MB</small>
                @error('lut_file')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="thumbnail" class="form-label">Thumbnail Preview</label>
                <div class="file-input-wrapper">
                    <input 
                        type="file" 
                        id="thumbnail" 
                        name="thumbnail" 
                        class="file-input @error('thumbnail') error @enderror"
                        accept="image/*"
                        onchange="previewImage(this)"
                    >
                    <label for="thumbnail" class="file-label">
                        <i class="fas fa-image"></i>
                        <span id="thumbnail-file-name">Pilih gambar preview</span>
                    </label>
                </div>
                <small class="form-help">Gambar yang menunjukkan hasil filter (opsional)</small>
                @error('thumbnail')
                <span class="form-error">{{ $message }}</span>
                @enderror
                
                <div id="thumbnail-preview" class="thumbnail-preview" style="display: none;">
                    <img id="preview-image" alt="Preview">
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        {{ old('is_active', true) ? 'checked' : '' }}
                    >
                    <span>LUT aktif (dapat digunakan langsung)</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Upload LUT
                </button>
                <a href="{{ route('admin.luts.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function updateFileName(input, targetId) {
    const target = document.getElementById(targetId);
    if (input.files && input.files.length > 0) {
        target.textContent = input.files[0].name;
    } else {
        target.textContent = 'Pilih file';
    }
}

function previewImage(input) {
    const preview = document.getElementById('thumbnail-preview');
    const previewImg = document.getElementById('preview-image');
    const fileName = document.getElementById('thumbnail-file-name');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
        fileName.textContent = input.files[0].name;
    } else {
        preview.style.display = 'none';
        fileName.textContent = 'Pilih gambar preview';
    }
}
</script>
@endpush

@push('styles')
<style>
.file-input-wrapper {
    position: relative;
}

.file-input {
    position: absolute;
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    z-index: -1;
}

.file-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: 0.75rem 1rem;
    background: var(--gray-50);
    border: 2px dashed var(--gray-300);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-base);
}

.file-label:hover {
    background: var(--gray-100);
    border-color: var(--primary);
}

.file-label i {
    color: var(--gray-500);
}

.file-input.error + .file-label {
    border-color: var(--danger);
}

.thumbnail-preview {
    margin-top: var(--spacing-md);
    border-radius: var(--radius-md);
    overflow: hidden;
    max-width: 400px;
}

.thumbnail-preview img {
    width: 100%;
    height: auto;
    display: block;
}
</style>
@endpush
