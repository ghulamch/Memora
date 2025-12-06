@extends('admin.layout')

@section('title', 'Edit Token')

@section('content')
<div class="admin-container">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-edit"></i> Edit Token
            </h1>
            <p class="page-subtitle">Update informasi token</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.tokens.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('admin.tokens.update', $token) }}">
            @csrf
            @method('PUT')

            <!-- Token Display -->
            <div class="info-box">
                <div class="info-label">Token String:</div>
                <div class="token-display">
                    <code>{{ $token->token }}</code>
                    <button type="button" onclick="copyToken('{{ $token->token }}')" class="btn-copy">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                <small class="text-muted">ID: {{ $token->id }} | Dibuat: {{ $token->created_at->format('d M Y H:i') }}</small>
            </div>

            <div class="form-group">
                <label for="name" class="form-label required">Nama Token</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') error @enderror"
                    value="{{ old('name', $token->name) }}"
                    required
                >
                @error('name')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="max_uses" class="form-label">Maksimal Penggunaan</label>
                    <input 
                        type="number" 
                        id="max_uses" 
                        name="max_uses" 
                        class="form-control @error('max_usage') error @enderror"
                        value="{{ old('max_usage', $token->max_usage) }}"
                        min="1"
                    >
                    <small class="form-help">
                        Sudah digunakan: {{ $token->usage_count }} kali
                    </small>
                    @error('max_usage')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="expires_at" class="form-label">Tanggal Kadaluarsa</label>
                    <input 
                        type="datetime-local" 
                        id="expires_at" 
                        name="expires_at" 
                        class="form-control @error('expires_at') error @enderror"
                        value="{{ old('expires_at', $token->expires_at ? $token->expires_at->format('Y-m-d\TH:i') : '') }}"
                    >
                    @error('expires_at')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        {{ old('is_active', $token->is_active) ? 'checked' : '' }}
                    >
                    <span>Token aktif</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Token
                </button>
                <a href="{{ route('admin.tokens.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function copyToken(token) {
    navigator.clipboard.writeText(token).then(() => {
        alert('Token berhasil dicopy!');
    });
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

.info-label {
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: var(--spacing-sm);
}

.token-display {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-sm);
}

.token-display code {
    flex: 1;
    background: white;
    padding: 0.75rem;
    border-radius: var(--radius-md);
    font-family: 'Courier New', monospace;
    word-break: break-all;
}

.btn-copy {
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-base);
}

.btn-copy:hover {
    background: var(--primary-dark);
}
</style>
@endpush
