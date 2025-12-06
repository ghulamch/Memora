@extends('admin.layout')

@section('title', 'Edit LUT')

@section('content')
<div class="admin-container">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-edit"></i> Edit LUT
            </h1>
            <p class="page-subtitle">Update informasi LUT</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.luts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('admin.luts.update', $lut) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Current Files Info -->
            <div class="info-box">
                <div class="info-row">
                    <div>
                        <div class="info-label">File LUT Saat Ini:</div>
                        <code>{{ basename($lut->file_path) }}</code>
                    </div>
                    <a href="{{ $lut->file_url }}" download class="btn btn-sm btn-secondary">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
                @if($lut->thumbnail_url)
                <div class="current-thumbnail">
                    <div class="info-label">Thumbnail Saat Ini:</div>
                    <img src="{{ $lut->thumbnail_url }}" alt="Current thumbnail">
                </div>
                @endif
            </div>

            <div class="form-group">
                <label for="name" class="form-label required">Nama LUT</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') error @enderror"
                    value="{{ old('name', $lut->name) }}"
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
                >{{ old('description', $lut->description) }}</textarea>
                @error('description')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="lut_file" class="form-label">Ganti File LUT (opsional)</label>
                <div class="file-input-wrapper">
                    <input 
                        type="file" 
                        id="lut_file" 
                        name="lut_file" 
                        class="file-input @error('lut_file') error @enderror"
                        accept=".cube,.3dl"
                        onchange="updateFileName(this, 'lut-file-name')"
                    >
                    <label for="lut_file" class="file-label">
                        <i class="fas fa-upload"></i>
                        <span id="lut-file-name">Pilih file LUT baru</span>
                    </label>
                </div>
                <small class="form-help">Kosongkan jika tidak ingin mengganti file</small>
                @error('lut_file')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="thumbnail" class="form-label">Ganti Thumbnail (opsional)</label>
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
                        <span id="thumbnail-file-name">Pilih gambar preview baru</span>
                    </label>
                </div>
                <small class="form-help">Kosongkan jika tidak ingin mengganti thumbnail</small>
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
                        {{ old('is_active', $lut->is_active) ? 'checked' : '' }}
                    >
                    <span>LUT aktif</span>
                </label>
            </div>

            <div class="usage-stats">
                <span><i class="fas fa-eye"></i> {{ number_format($lut->usage_count) }} kali digunakan</span>
                <span><i class="fas fa-calendar"></i> Dibuat {{ $lut->created_at->format('d M Y') }}</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update LUT
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
        target.textContent = 'Pilih file baru';
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
        fileName.textContent = 'Pilih gambar preview baru';
    }
}
</script>
@endpush

@push('styles')
<style>
.info-box {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.info-label {
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: var(--spacing-xs);
}

.current-thumbnail {
    margin-top: var(--spacing-md);
}

.current-thumbnail img {
    max-width: 300px;
    border-radius: var(--radius-md);
    margin-top: var(--spacing-sm);
}

.usage-stats {
    display: flex;
    gap: var(--spacing-xl);
    padding: var(--spacing-lg);
    background: var(--gray-50);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-xl);
    font-size: 0.875rem;
    color: var(--gray-600);
}

.usage-stats span {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
</style>
@endpush
