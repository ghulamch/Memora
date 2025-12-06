<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Memora')</title>
    <link rel="icon" href="{{ asset('memora_logo.png') }}" type="image/x-icon">
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('app.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('gallery') }}" class="navbar-brand">
                <img src="{{ asset('memora_logo.png') }}" style="height: 50px; width: auto;" alt="Logo">
                <span class="navbar-title">MEMORA</span>
            </a>
            
            <div>
                @yield('header-actions')
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

   <footer class="footer">
    <div class="footer-content">
        <p>&copy; {{ date('Y') }} Memora. All Rights Reserved.</p>
        <p>Thank you for being a part of Memora's journey!</p>
    </div>
</footer>


    @stack('scripts')
</body>
</html>