<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Memora Admin</title>
    <link rel="icon" href="{{ asset('memora_logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #6366f1;
            --accent: #ec4899;
            --success: #10b981;
            --danger: #ef4444;
            --dark: #0f172a;
            --gray: #64748b;
            --light: #f1f5f9;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: #0f172a;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Geometric Background Pattern */
        .background-pattern {
            position: fixed;
            inset: 0;
            z-index: 0;
            opacity: 0.03;
            background-image: 
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255, 255, 255, 0.1) 2px, rgba(255, 255, 255, 0.1) 4px),
                repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(255, 255, 255, 0.1) 2px, rgba(255, 255, 255, 0.1) 4px);
            background-size: 60px 60px;
        }

        /* Animated Dots Grid */
        .dots-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .dot {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(99, 102, 241, 0.4);
            border-radius: 50%;
            animation: twinkle 4s ease-in-out infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.5); }
        }

        /* Floating Geometric Shapes */
        .geometric-shape {
            position: absolute;
            border: 2px solid rgba(99, 102, 241, 0.15);
            background: rgba(99, 102, 241, 0.03);
            backdrop-filter: blur(2px);
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            border-radius: 50%;
            top: -200px;
            left: -200px;
            animation: rotate-slow 30s linear infinite;
        }

        .shape-2 {
            width: 300px;
            height: 300px;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            bottom: -150px;
            right: -150px;
            animation: morph 20s ease-in-out infinite;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            border-radius: 40% 60% 60% 40% / 60% 40% 60% 40%;
            top: 20%;
            right: 10%;
            animation: float-diagonal 25s ease-in-out infinite;
        }

        .shape-4 {
            width: 250px;
            height: 250px;
            border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
            bottom: 30%;
            left: 5%;
            animation: morph-reverse 22s ease-in-out infinite;
        }

        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes morph {
            0%, 100% { 
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
                transform: rotate(0deg);
            }
            50% { 
                border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
                transform: rotate(180deg);
            }
        }

        @keyframes morph-reverse {
            0%, 100% { 
                border-radius: 63% 37% 54% 46% / 55% 48% 52% 45%;
                transform: scale(1) rotate(0deg);
            }
            50% { 
                border-radius: 37% 63% 46% 54% / 48% 55% 45% 52%;
                transform: scale(1.1) rotate(-180deg);
            }
        }

        @keyframes float-diagonal {
            0%, 100% { transform: translate(0, 0); }
            25% { transform: translate(30px, -30px); }
            50% { transform: translate(60px, 0); }
            75% { transform: translate(30px, 30px); }
        }

        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 480px;
            padding: 2rem;
            position: relative;
            z-index: 10;
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Premium Card */
        .login-card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
            padding: 3.5rem 3rem;
            box-shadow: 
                0 0 0 1px rgba(99, 102, 241, 0.1),
                0 20px 60px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.8), transparent);
            animation: shimmer-line 4s ease-in-out infinite;
        }

        @keyframes shimmer-line {
            0%, 100% { opacity: 0; transform: translateX(-100%); }
            50% { opacity: 1; transform: translateX(100%); }
        }

        /* Header with Logo */
        .login-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 10px 40px rgba(79, 70, 229, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            animation: logo-float 3s ease-in-out infinite;
        }

        .logo-container::before {
            content: '';
            position: absolute;
            inset: -4px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 2rem;
            opacity: 0.3;
            filter: blur(20px);
            z-index: -1;
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes logo-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        .logo-container img {
            filter: brightness(0) invert(1);
        }

        .login-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.75rem;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
            font-weight: 400;
            letter-spacing: 0.01em;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.875rem;
            font-size: 0.9375rem;
            letter-spacing: 0.01em;
        }

        .form-label i {
            color: var(--primary-light);
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 1.125rem;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2;
        }

        .form-control {
            width: 100%;
            padding: 1.125rem 1.25rem 1.125rem 3.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            font-size: 1rem;
            color: white;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary);
            box-shadow: 
                0 0 0 4px rgba(79, 70, 229, 0.1),
                0 8px 24px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        .form-control:focus ~ .input-icon {
            color: var(--primary-light);
            transform: translateY(-50%) scale(1.1);
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            font-size: 1.125rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary-light);
            background: rgba(255, 255, 255, 0.05);
        }

        /* Error Messages */
        .form-error {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.75rem;
            padding: 0.875rem 1rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.75rem;
            color: #fca5a5;
            font-size: 0.875rem;
            font-weight: 500;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .form-error i {
            font-size: 1rem;
            color: var(--danger);
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 1rem;
            color: white;
            font-size: 1.0625rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 8px 24px rgba(79, 70, 229, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            margin-top: 0.5rem;
            letter-spacing: 0.02em;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 12px 32px rgba(79, 70, 229, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-login span {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        /* Loading State */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading span {
            opacity: 0;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .login-footer a {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-footer a:hover {
            color: white;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .login-container {
                padding: 1.5rem;
            }

            .login-card {
                padding: 2.5rem 2rem;
                border-radius: 1.75rem;
            }

            .logo-container {
                width: 85px;
                height: 85px;
                margin-bottom: 1.5rem;
            }

            .login-title {
                font-size: 1.875rem;
            }

            .login-subtitle {
                font-size: 0.9375rem;
            }

            .form-control {
                padding: 1rem 1rem 1rem 3rem;
            }

            .input-icon {
                left: 1rem;
                font-size: 1rem;
            }

            .btn-login {
                padding: 1.125rem;
                font-size: 1rem;
            }

            .geometric-shape {
                opacity: 0.5;
            }
        }

        /* Accessibility */
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            margin: -1px;
            padding: 0;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* High Contrast Mode */
        @media (prefers-contrast: high) {
            .login-card {
                border: 2px solid rgba(255, 255, 255, 0.3);
            }
            
            .form-control {
                border-width: 3px;
            }
        }
    </style>
</head>
<body>
    <!-- Background Pattern -->
    <div class="background-pattern"></div>
    
    <!-- Dots Grid -->
    <div class="dots-grid" id="dotsGrid"></div>
    
    <!-- Geometric Shapes -->
    <div class="geometric-shape shape-1"></div>
    <div class="geometric-shape shape-2"></div>
    <div class="geometric-shape shape-3"></div>
    <div class="geometric-shape shape-4"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('memora_logo.png') }}" style="height: 55px; width: auto;" alt="Memora Logo">
                </div>
                <h1 class="login-title">Memora Admin</h1>
                <p class="login-subtitle">Masuk ke dashboard admin</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>Email Address
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            placeholder="admin@memora.com"
                            autocomplete="email"
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    @error('email')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>Password
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            required
                            placeholder="Enter your password"
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="form-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="submitBtn">
                    <span>
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In to Dashboard
                    </span>
                </button>
            </form>

            <div class="login-footer">
                <p>&copy; {{ date('Y') }} Memora. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Generate Animated Dots
        function generateDots() {
            const dotsGrid = document.getElementById('dotsGrid');
            const numberOfDots = 100;
            
            for (let i = 0; i < numberOfDots; i++) {
                const dot = document.createElement('div');
                dot.className = 'dot';
                dot.style.left = Math.random() * 100 + '%';
                dot.style.top = Math.random() * 100 + '%';
                dot.style.animationDelay = Math.random() * 4 + 's';
                dotsGrid.appendChild(dot);
            }
        }

        // Password Toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form Submit Loading
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
        });

        // Input Focus Animation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
                this.parentElement.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Initialize
        window.addEventListener('DOMContentLoaded', () => {
            generateDots();
            
            // Prevent form resubmission on refresh
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });

        // Keyboard Navigation Enhancement
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                const form = e.target.closest('form');
                if (form) {
                    const inputs = Array.from(form.querySelectorAll('input'));
                    const currentIndex = inputs.indexOf(e.target);
                    if (currentIndex < inputs.length - 1) {
                        e.preventDefault();
                        inputs[currentIndex + 1].focus();
                    }
                }
            }
        });
    </script>
</body>
</html>