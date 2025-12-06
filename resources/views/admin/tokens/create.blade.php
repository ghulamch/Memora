@extends('admin.layout')

@section('title', 'Buat Token Baru')

@section('content')
<div class="admin-container">
    
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-key"></i> Buat Token Baru
            </h1>
            <p class="page-subtitle">Generate token akses baru</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.tokens.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form method="POST" action="{{ route('admin.tokens.store') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label required">Nama Token</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') error @enderror"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Token Event Pernikahan"
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
                        class="form-control @error('max_uses') error @enderror"
                        value="{{ old('max_uses') }}"
                        min="1"
                        placeholder="Kosongkan untuk unlimited"
                    >
                    <small class="form-help">Kosongkan jika tidak ada batasan</small>
                    @error('max_uses')
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
                        value="{{ old('expires_at') }}"
                    >
                    <small class="form-help">Kosongkan jika tidak ada batas waktu</small>
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
                        {{ old('is_active', true) ? 'checked' : '' }}
                    >
                    <span>Token aktif (dapat digunakan langsung)</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Token
                </button>
                <a href="{{ route('admin.tokens.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@push('styles')
<style>
.form-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: var(--spacing-2xl);
    max-width: 800px;
}

.form-group {
    margin-bottom: var(--spacing-xl);
}

.form-label {
    display: block;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: var(--spacing-sm);
}

.form-label.required::after {
    content: '*';
    color: var(--danger);
    margin-left: 0.25rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius-md);
    font-size: 1rem;
    transition: all var(--transition-base);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control.error {
    border-color: var(--danger);
}

.form-error {
    display: block;
    color: var(--danger);
    font-size: 0.875rem;
    margin-top: var(--spacing-xs);
}

.form-help {
    display: block;
    color: var(--gray-600);
    font-size: 0.875rem;
    margin-top: var(--spacing-xs);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 1.125rem;
    height: 1.125rem;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    margin-top: var(--spacing-2xl);
    padding-top: var(--spacing-xl);
    border-top: 1px solid var(--gray-200);
}
</style>
@endpush
