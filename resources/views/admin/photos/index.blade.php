@extends('admin.layout')

@section('title', 'Kelola Foto')

@section('content')
<div class="admin-container" x-data="photosApp()">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-images"></i> Kelola Foto
            </h1>
            <p class="page-subtitle">Manage semua foto yang diupload</p>
        </div>
        <div class="header-actions">
            <button @click="bulkDelete()" x-show="selectedPhotos.length > 0" class="btn btn-danger">
                <i class="fas fa-trash"></i> Hapus (<span x-text="selectedPhotos.length"></span>)
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-bar">
        <form method="GET" class="filters-form">
            <div class="filter-group">
                <label><i class="fas fa-tag"></i> Kode Sesi</label>
                <select name="session_code" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Sesi</option>
                    @foreach($sessionCodes as $code)
                    <option value="{{ $code }}" {{ request('session_code') == $code ? 'selected' : '' }}>
                        {{ $code }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
            </div>

            @if(request('session_code') || request('date'))
            <a href="{{ route('admin.photos.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Photos Grid -->
    <div class="photos-grid">
        @forelse($photos as $photo)
        <div class="photo-card" :class="selectedPhotos.includes({{ $photo->id }}) ? 'selected' : ''">
            <div class="photo-checkbox">
                <input 
                    type="checkbox" 
                    :checked="selectedPhotos.includes({{ $photo->id }})"
                    @change="togglePhoto({{ $photo->id }})"
                >
            </div>

            <div class="photo-preview">
                <img src="{{ $photo->full_url }}" alt="Photo {{ $photo->id }}" loading="lazy">
            </div>

            <div class="photo-details">
                <div class="photo-session">
                    <i class="fas fa-tag"></i> {{ $photo->session_code }}
                </div>
                <div class="photo-date">
                    <i class="fas fa-clock"></i> {{ $photo->created_at->format('d M Y, H:i') }}
                </div>
                @if($photo->file_size)
                <div class="photo-size">
                    <i class="fas fa-file"></i> {{ number_format($photo->file_size / 1024, 2) }} KB
                </div>
                @endif
            </div>

            <div class="photo-actions">
                <a href="{{ $photo->full_url }}" download class="action-btn" title="Download">
                    <i class="fas fa-download"></i>
                </a>
                <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn danger" onclick="return confirm('Yakin hapus foto ini?')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <i class="fas fa-images"></i>
            <h3>Belum Ada Foto</h3>
            <p>Foto yang diupload akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $photos->appends(request()->query())->links() }}
    </div>

</div>
@endsection

@push('scripts')
<script>
function photosApp() {
    return {
        selectedPhotos: [],
        
        togglePhoto(photoId) {
            const index = this.selectedPhotos.indexOf(photoId);
            if (index > -1) {
                this.selectedPhotos.splice(index, 1);
            } else {
                this.selectedPhotos.push(photoId);
            }
        },
        
        async bulkDelete() {
            if (!confirm(`Yakin hapus ${this.selectedPhotos.length} foto?`)) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("admin.photos.bulk-delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        photo_ids: this.selectedPhotos,
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Gagal menghapus foto');
            }
        }
    }
}
</script>
@endpush
