@extends('admin.layout')

@section('title', 'Kelola Token')

@section('content')
<div class="admin-container" x-data="tokensApp()">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-key"></i> Kelola Token
            </h1>
            <p class="page-subtitle">Manage token akses untuk memora</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.tokens.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Token Baru
            </a>
        </div>
    </div>

    <!-- Tokens Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Token</th>
                        <th>Penggunaan</th>
                        <th>Kadaluarsa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tokens as $token)
                    <tr>
                        <td>
                            <div class="cell-content">
                                <strong>{{ $token->name }}</strong>
                                @if($token->description)
                                <small class="text-muted">{{ Str::limit($token->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="token-display">
                                <code class="token-code">{{ Str::limit($token->token, 20) }}...</code>
                                <button 
                                    @click="copyToken('{{ $token->token }}')"
                                    class="btn-copy"
                                    title="Copy token"
                                >
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="usage-info">
                                <strong>{{ number_format($token->usage_count) }}</strong>
                                @if($token->max_usage)
                                / {{ number_format($token->max_usage) }}
                                @else
                                / Unlimited
                                @endif
                            </div>
                            @if($token->max_usage)
                            <div class="progress-bar">
                                <div 
                                    class="progress-fill"
                                    style="width: {{ min(100, ($token->usage_count / $token->max_usage) * 100) }}%"
                                ></div>
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($token->expires_at)
                                <span class="{{ $token->expires_at->isPast() ? 'text-danger' : 'text-muted' }}">
                                    {{ $token->expires_at->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ 
                                $token->is_active 
                                ? 'active' 
                                : ($token->isExpired() ? 'expired' : ($token->isDepleted() ? 'depleted' : 'inactive')) 
                            }}">
                                <i class="fas fa-circle"></i>
                                @switch($token->is_active)
                                    @case('1') 
                                        Aktif 
                                        @break
                                    @case('0') 
                                        Nonaktif 
                                        @break
                                    @case('expired') 
                                        Kadaluarsa 
                                        @break
                                    @case('depleted') 
                                        Habis 
                                        @break
                                @endswitch
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <button 
                                    @click="toggleToken({{ $token->id }}, {{ $token->is_active ? 'false' : 'true' }})"
                                    class="action-btn"
                                    title="{{ $token->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                >
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <a 
                                    href="{{ route('admin.tokens.edit', $token) }}"
                                    class="action-btn"
                                    title="Edit"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form 
                                    method="POST" 
                                    action="{{ route('admin.tokens.regenerate', $token) }}"
                                    style="display: inline;"
                                >
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="action-btn"
                                        onclick="return confirm('Regenerate token? Token lama akan tidak bisa digunakan.')"
                                        title="Regenerate"
                                    >
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                                <form 
                                    method="POST" 
                                    action="{{ route('admin.tokens.destroy', $token) }}"
                                    style="display: inline;"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="action-btn danger"
                                        onclick="return confirm('Yakin hapus token ini?')"
                                        title="Hapus"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <div class="empty-state">
                                <i class="fas fa-key"></i>
                                <h3>Belum Ada Token</h3>
                                <p>Buat token pertama untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $tokens->links() }}
    </div>

</div>
@endsection

@push('scripts')
<script>
function tokensApp() {
    return {
        async copyToken(token) {
            try {
                await navigator.clipboard.writeText(token);
                alert('Token berhasil dicopy!');
            } catch (error) {
                // Fallback untuk browser lama
                const textarea = document.createElement('textarea');
                textarea.value = token;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('Token berhasil dicopy!');
            }
        },
        
        async toggleToken(id, isActive) {
            try {
                const response = await fetch(`/admin/tokens/${id}/toggle`, {
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
                alert('Gagal mengubah status token');
            }
        }
    }
}
</script>
@endpush

@push('styles')
<style>
.token-display {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.token-code {
    background: var(--gray-100);
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-md);
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    color: var(--gray-700);
}

.btn-copy {
    padding: 0.375rem 0.5rem;
    background: none;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    color: var(--gray-600);
    cursor: pointer;
    transition: all var(--transition-base);
}

.btn-copy:hover {
    background: var(--gray-100);
    color: var(--gray-900);
}

.usage-info {
    margin-bottom: var(--spacing-xs);
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--gray-200);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--primary-dark));
    transition: width var(--transition-base);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge i {
    font-size: 0.5rem;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
}

.status-inactive {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.status-expired {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.status-depleted {
    background: rgba(249, 115, 22, 0.1);
    color: #ea580c;
}

.table-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: var(--gray-50);
}

.data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--gray-700);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--gray-200);
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--gray-100);
}

.data-table tbody tr:hover {
    background: var(--gray-50);
}

.cell-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}
</style>
@endpush
