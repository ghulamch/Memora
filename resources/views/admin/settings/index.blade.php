@extends('admin.layout')

@section('title', 'Application Settings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin-settings.css') }}">
@endpush

@section('content')
<div class="settings-page">
    <div class="settings-container">
        
        <!-- Header -->
        <div class="settings-header">
            <h1>
                <i class="fas fa-cog"></i>
                Application Settings
            </h1>
            <p>Manage application features and configurations</p>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
        <div class="settings-alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                
                <!-- Editor Features Card -->
                @php
                    $editorSettings = $settings['editor'] ?? collect();
                @endphp

                @if($editorSettings->isNotEmpty())
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h5>
                            <i class="fas fa-edit"></i>
                            Editor Features
                        </h5>
                    </div>
                    <div class="settings-card-body">
                        @foreach($editorSettings as $setting)
                        <div class="settings-item">
                            <div class="settings-item-content">
                                <div class="settings-item-title">
                                    <i class="fas fa-palette"></i>
                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                </div>
                                <p class="settings-item-description">
                                    {{ $setting->description }}
                                </p>
                            </div>
                            
                            <div class="settings-item-control">
                                @if($setting->type === 'boolean')
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="status-badge {{ $setting->value === '1' ? 'enabled' : 'disabled' }}" 
                                              id="badge_{{ $setting->id }}">
                                            {{ $setting->value === '1' ? 'Enabled' : 'Disabled' }}
                                        </span>
                                        
                                        <label class="toggle-switch" for="setting_{{ $setting->id }}">
                                            <input 
                                                type="checkbox" 
                                                id="setting_{{ $setting->id }}"
                                                class="setting-toggle"
                                                data-key="{{ $setting->key }}"
                                                data-badge="badge_{{ $setting->id }}"
                                                {{ $setting->value === '1' ? 'checked' : '' }}
                                            >
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                @elseif($setting->type === 'string')
                                    <input 
                                        type="text" 
                                        class="settings-input setting-input" 
                                        data-key="{{ $setting->key }}"
                                        value="{{ $setting->value }}"
                                        placeholder="Enter value..."
                                    >
                                @elseif($setting->type === 'integer')
                                    <input 
                                        type="number" 
                                        class="settings-input setting-input" 
                                        data-key="{{ $setting->key }}"
                                        value="{{ $setting->value }}"
                                        placeholder="0"
                                    >
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h5>
                            <i class="fas fa-edit"></i>
                            Editor Features
                        </h5>
                    </div>
                    <div class="settings-card-body">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h6>No Settings Found</h6>
                            <p>No editor settings have been configured yet.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- General Settings Card -->
                @php
                    $generalSettings = $settings['general'] ?? collect();
                @endphp

                @if($generalSettings->isNotEmpty())
                <div class="settings-card">
                    <div class="settings-card-header" style="background: linear-gradient(135deg, #6b7280, #9ca3af);">
                        <h5>
                            <i class="fas fa-sliders-h"></i>
                            General Settings
                        </h5>
                    </div>
                    <div class="settings-card-body">
                        @foreach($generalSettings as $setting)
                        <div class="settings-item">
                            <div class="settings-item-content">
                                <div class="settings-item-title">
                                    <i class="fas fa-cog"></i>
                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                </div>
                                <p class="settings-item-description">
                                    {{ $setting->description }}
                                </p>
                            </div>
                            
                            <div class="settings-item-control">
                                @if($setting->type === 'boolean')
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="status-badge {{ $setting->value === '1' ? 'enabled' : 'disabled' }}" 
                                              id="badge_{{ $setting->id }}">
                                            {{ $setting->value === '1' ? 'Enabled' : 'Disabled' }}
                                        </span>
                                        
                                        <label class="toggle-switch" for="setting_{{ $setting->id }}">
                                            <input 
                                                type="checkbox" 
                                                id="setting_{{ $setting->id }}"
                                                class="setting-toggle"
                                                data-key="{{ $setting->key }}"
                                                data-badge="badge_{{ $setting->id }}"
                                                {{ $setting->value === '1' ? 'checked' : '' }}
                                            >
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                
                <!-- Info Card -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h5>
                            <i class="fas fa-info-circle"></i>
                            Information
                        </h5>
                    </div>
                    <div class="info-card-body">
                        
                        <div class="info-section">
                            <h6>About Settings</h6>
                            <p>
                                Settings are cached for 1 hour for better performance. 
                                Changes take effect immediately across the application.
                            </p>
                        </div>

                        <div class="info-section">
                            <h6>LUT Filter Feature</h6>
                            <p>
                                When disabled, the LUT color filter section will be 
                                completely hidden from the photo editor interface. 
                                Users won't see the filter options at all.
                            </p>
                        </div>

                        <div class="info-section">
                            <h6>Cache Management</h6>
                            <p>
                                Settings cache is automatically cleared when you make changes. 
                                You can also manually clear all application cache below.
                            </p>
                        </div>

                        <div class="info-section mb-0">
                            <button class="btn-settings btn-primary w-100" onclick="clearAllCache()">
                                <i class="fas fa-sync-alt"></i>
                                Clear All Cache
                            </button>
                        </div>
                        
                    </div>
                </div>

                <!-- Stats Card (Optional) -->
                <div class="info-card" style="margin-top: 1.5rem;">
                    <div class="info-card-header" style="background: linear-gradient(135deg, #10b981, #34d399);">
                        <h5>
                            <i class="fas fa-chart-line"></i>
                            Quick Stats
                        </h5>
                    </div>
                    <div class="info-card-body">
                        <div class="info-section">
                            <h6>Total Settings</h6>
                            <p style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-top: 0.5rem;">
                                {{ $settings->flatten()->count() }}
                            </p>
                        </div>

                        <div class="info-section mb-0">
                            <h6>Enabled Features</h6>
                            <p style="font-size: 1.5rem; font-weight: 700; color: var(--success); margin-top: 0.5rem;">
                                {{ $settings->flatten()->where('value', '1')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Toast Container (for notifications) -->
<div class="toast-container" id="toastContainer"></div>

@endsection

@push('scripts')
<script>
// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// Toggle boolean settings
document.querySelectorAll('.setting-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const key = this.dataset.key;
        const enabled = this.checked;
        const badgeId = this.dataset.badge;
        const badge = document.getElementById(badgeId);
        
        // Show loading spinner
        const spinner = createSpinner();
        this.parentElement.appendChild(spinner);
        this.disabled = true;
        
        // Update badge immediately (optimistic UI)
        if (badge) {
            badge.textContent = enabled ? 'Enabled' : 'Disabled';
            badge.className = `status-badge ${enabled ? 'enabled' : 'disabled'}`;
        }
        
        // Send update to server
        fetch('/admin/settings/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ key })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Success', data.message);
            } else {
                throw new Error(data.message || 'Failed to update setting');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Error', error.message || 'Failed to update setting');
            
            // Revert on error
            this.checked = !enabled;
            if (badge) {
                badge.textContent = !enabled ? 'Enabled' : 'Disabled';
                badge.className = `status-badge ${!enabled ? 'enabled' : 'disabled'}`;
            }
        })
        .finally(() => {
            spinner.remove();
            this.disabled = false;
        });
    });
});

// Update text/number settings with debounce
let updateTimeout;
document.querySelectorAll('.setting-input').forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(updateTimeout);
        
        // Add loading indicator
        this.style.borderColor = '#f59e0b';
        
        updateTimeout = setTimeout(() => {
            const key = this.dataset.key;
            const value = this.value;
            
            fetch('/admin/settings/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ key, value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.style.borderColor = '#10b981';
                    showToast('success', 'Success', data.message);
                    
                    // Reset border after 1s
                    setTimeout(() => {
                        this.style.borderColor = '';
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to update setting');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.style.borderColor = '#ef4444';
                showToast('error', 'Error', error.message || 'Failed to update setting');
            });
        }, 1000);
    });
});

// Clear all cache
function clearAllCache() {
    if (!confirm('Are you sure you want to clear all cache? This will temporarily slow down the application.')) {
        return;
    }
    
    showToast('info', 'Processing', 'Clearing cache...');
    
    fetch('/admin/cache/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        }
    })
    .then(response => response.json())
    .then(data => {
        showToast('success', 'Success', 'All cache cleared successfully');
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error', 'Failed to clear cache');
    });
}

// Create spinner element
function createSpinner() {
    const spinner = document.createElement('div');
    spinner.className = 'spinner';
    spinner.style.marginLeft = '0.5rem';
    return spinner;
}

// Show toast notification
function showToast(type, title, message) {
    const container = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icon = type === 'success' ? 'fa-check-circle' : 
                 type === 'error' ? 'fa-exclamation-circle' : 
                 'fa-info-circle';
    
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}
</script>
@endpush