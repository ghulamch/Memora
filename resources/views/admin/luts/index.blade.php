@extends('admin.layout')

@section('title', 'Kelola LUT')

@section('content')
<div class="admin-container" x-data="lutsApp()">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-palette"></i> Kelola LUT
            </h1>
            <p class="page-subtitle">Manage Look-Up Tables untuk color grading</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.luts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Upload LUT Baru
            </a>
        </div>
    </div>

    <!-- LUTs Grid -->
    <div class="luts-grid">
        @forelse($luts as $lut)
        <div class="lut-card">
            <!-- Preview -->
            <div class="lut-preview">
                @if($lut->thumbnail_url)
                    <img src="{{ $lut->thumbnail_url }}" alt="{{ $lut->name }}">
                @else
                    <div class="preview-placeholder">
                        <i class="fas fa-palette"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="status-badge {{ $lut->is_active ? 'active' : 'inactive' }}">
                    <i class="fas fa-circle"></i>
                    {{ $lut->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
            </div>

            <!-- Info -->
            <div class="lut-info">
                <h3 class="lut-name">{{ $lut->name }}</h3>
                @if($lut->description)
                <p class="lut-desc">{{ Str::limit($lut->description, 80) }}</p>
                @endif
                <div class="lut-meta">
                    <span>
                        <i class="fas fa-eye"></i> 
                        {{ number_format($lut->usage_count) }} digunakan
                    </span>
                    <span>
                        <i class="fas fa-file"></i>
                        {{ pathinfo($lut->file_path, PATHINFO_EXTENSION) }}
                    </span>
                </div>
            </div>

            <!-- Actions -->
            <div class="lut-actions">
                <button 
                    @click="toggleLut({{ $lut->id }}, {{ $lut->is_active ? 'false' : 'true' }})"
                    class="action-btn"
                    title="{{ $lut->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                >
                    <i class="fas fa-power-off"></i>
                </button>
                <a 
                    href="{{ $lut->file_url }}"
                    download
                    class="action-btn"
                    title="Download LUT"
                >
                    <i class="fas fa-download"></i>
                </a>
                <a 
                    href="{{ route('admin.luts.edit', $lut) }}"
                    class="action-btn"
                    title="Edit"
                >
                    <i class="fas fa-edit"></i>
                </a>
                <button 
                    @click="deleteLut({{ $lut->id }})"
                    class="action-btn danger"
                    title="Hapus"
                >
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <i class="fas fa-palette"></i>
            <h3>Belum Ada LUT</h3>
            <p>Upload file LUT pertama Anda untuk mulai menggunakan filter</p>
            <a href="{{ route('admin.luts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Upload LUT
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $luts->links() }}
    </div>

</div>
@endsection

@push('scripts')
<script>
function lutsApp() {
    return {
        async toggleLut(id, isActive) {
            try {
                const response = await fetch(`/admin/luts/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                alert('Gagal mengubah status LUT');
            }
        },
        
        async deleteLut(id) {
            if (!confirm('Yakin ingin menghapus LUT ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/luts/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                alert('Gagal menghapus LUT');
            }
        }
    }
}
</script>
@endpush

@push('styles')
<style>
.luts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--spacing-xl);
}

.lut-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: all var(--transition-base);
}

.lut-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.lut-preview {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: var(--gray-100);
}

.lut-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
}

.preview-placeholder i {
    font-size: 3rem;
    color: var(--gray-400);
}

.lut-info {
    padding: var(--spacing-lg);
}

.lut-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: var(--spacing-sm);
}

.lut-desc {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin-bottom: var(--spacing-md);
    line-height: 1.5;
}

.lut-meta {
    display: flex;
    gap: var(--spacing-md);
    font-size: 0.875rem;
    color: var(--gray-600);
}

.lut-meta span {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.lut-actions {
    display: flex;
    gap: 0.5rem;
    padding: var(--spacing-md) var(--spacing-lg);
    border-top: 1px solid var(--gray-100);
}
</style>
@endpush
