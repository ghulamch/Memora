@extends('admin.layout')

@section('title', 'Template Management')

@section('content')
<div class="admin-container" x-data="templatesApp()">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-layer-group"></i> Template Management
            </h1>
            <p class="page-subtitle">Kelola template frame untuk memora</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Template Baru
            </a>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="templates-grid">
        @forelse($templates as $template)
        <div class="template-card">
            <!-- Preview -->
            <div class="template-preview">
                @if($template->background_url)
                    <img src="{{ $template->background_url }}" alt="{{ $template->name }}">
                @else
                    <div class="preview-placeholder">
                        <div class="slots-preview">
                            @foreach($template->slots as $slot)
                            <div class="slot-box" style="
                                left: {{ ($slot->x / $template->canvas_width) * 100 }}%;
                                top: {{ ($slot->y / $template->canvas_height) * 100 }}%;
                                width: {{ ($slot->width / $template->canvas_width) * 100 }}%;
                                height: {{ ($slot->height / $template->canvas_height) * 100 }}%;
                            "></div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="status-badge {{ $template->is_active ? 'active' : 'inactive' }}">
                    <i class="fas fa-circle"></i>
                    {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
            </div>

            <!-- Info -->
            <div class="template-info">
                <h3 class="template-name">{{ $template->name }}</h3>
                @if($template->description)
                <p class="template-desc">{{ Str::limit($template->description, 60) }}</p>
                @endif
                <div class="template-meta">
                    <span><i class="fas fa-image"></i> {{ $template->slots_count }} slot</span>
                    <span><i class="fas fa-ruler-combined"></i> {{ $template->canvas_width }}x{{ $template->canvas_height }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="template-actions">
                <button 
                    @click="toggleTemplate({{ $template->id }}, {{ $template->is_active ? 'false' : 'true' }})"
                    class="action-btn"
                    title="{{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                >
                    <i class="fas fa-power-off"></i>
                </button>
                <button 
                    @click="deleteTemplate({{ $template->id }})"
                    class="action-btn danger"
                    title="Hapus"
                >
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <i class="fas fa-layer-group"></i>
            <h3>Belum Ada Template</h3>
            <p>Buat template pertama Anda untuk memulai</p>
            <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Template
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $templates->links() }}
    </div>

</div>
@endsection

@push('scripts')
<script>
function templatesApp() {
    return {
        async toggleTemplate(id, isActive) {
            try {
                const response = await fetch(`/admin/templates/${id}/toggle`, {
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
                alert('Gagal mengubah status template');
            }
        },
        
        async deleteTemplate(id) {
            if (!confirm('Yakin ingin menghapus template ini?')) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/templates/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                
                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                alert('Gagal menghapus template');
            }
        }
    }
}
</script>
@endpush
