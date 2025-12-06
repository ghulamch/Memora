<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Photo Booth</title>
    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('memora_logo.png') }}" type="image/x-icon">
    
    <!-- Admin Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/photos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/builder.css') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body>
    
    <!-- Admin Navigation -->
    <nav class="admin-nav">
        <div class="admin-nav-container">
            <div class="admin-nav-brand">
                <img src="{{ asset('memora_logo.png') }}" style="height: 50px; width: auto;" alt="Logo">
                <span class="navbar-title">Memora Admin</span>
            </div>
            
            <div class="admin-nav-menu">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.photos.index') }}" class="nav-link {{ request()->routeIs('admin.photos.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Foto</span>
                </a>
                <a href="{{ route('admin.templates.index') }}" class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span>Template</span>
                </a>
                <a href="{{ route('admin.luts.index') }}" class="nav-link {{ request()->routeIs('admin.luts.*') ? 'active' : '' }}">
                    <i class="fas fa-palette"></i>
                    <span>LUT Filter</span>
                </a>
                <a href="{{ route('admin.tokens.index') }}" class="nav-link {{ request()->routeIs('admin.tokens.*') ? 'active' : '' }}">
                    <i class="fas fa-key"></i>
                    <span>Token</span>
                </a>
            </div>
            
            <div class="admin-nav-actions">
                <a href="{{ route('gallery') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Gallery</span>
                </a>
                <button class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="admin-main">
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
    
    <script>
        function toggleMobileMenu() {
            document.querySelector('.admin-nav-menu').classList.toggle('active');
            document.querySelector('.admin-nav-actions').classList.toggle('active');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>

    <style>
        /* Admin Navigation Styles */
        .admin-nav {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .admin-nav-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 var(--spacing-xl);
            display: flex;
            align-items: center;
            gap: var(--spacing-xl);
        }

        .admin-nav-brand {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--gray-900);
            padding: var(--spacing-lg) 0;
        }

        .admin-nav-brand i {
            color: var(--primary);
            font-size: 1.5rem;
        }

        .admin-nav-menu {
            display: flex;
            gap: var(--spacing-sm);
            flex: 1;
        }

        .admin-nav-actions {
            display: flex;
            gap: var(--spacing-sm);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: 0.625rem 1rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all var(--transition-base);
            background: none;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .nav-link:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .nav-link i {
            font-size: 1rem;
        }

        .mobile-menu-toggle {
            display: none;
            padding: 0.625rem;
            background: none;
            border: none;
            color: var(--gray-700);
            font-size: 1.25rem;
            cursor: pointer;
        }

        .admin-main {
            min-height: calc(100vh - 70px);
            background: var(--gray-50);
            padding-top: var(--spacing-xl);
        }

        /* Alert Styles */
        .alert {
            position: fixed;
            top: 90px;
            right: var(--spacing-xl);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            font-weight: 500;
            z-index: 9999;
            transition: opacity 0.3s;
            max-width: 400px;
        }

        .alert-success {
            background: white;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        .alert-danger {
            background: white;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        @media (max-width: 992px) {
            .admin-nav-container {
                flex-wrap: wrap;
                padding: 0 var(--spacing-md);
            }

            .mobile-menu-toggle {
                display: block;
                margin-left: auto;
            }

            .admin-nav-menu,
            .admin-nav-actions {
                display: none;
                width: 100%;
                flex-direction: column;
                padding-bottom: var(--spacing-md);
            }

            .admin-nav-menu.active,
            .admin-nav-actions.active {
                display: flex;
            }

            .nav-link {
                width: 100%;
                justify-content: flex-start;
            }

            .alert {
                right: var(--spacing-md);
                left: var(--spacing-md);
                max-width: none;
            }
        }
    </style>
</body>
</html>
