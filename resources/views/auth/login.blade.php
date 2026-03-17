@extends('layouts.auth')

@section('title', 'Login')

@section('styles')
    <style>
        /* Import modern font and Font Awesome for additional icons */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
        
        body {
            background: linear-gradient(135deg, #0a0f1e 0%, #1a1f35 30%, #2a2f52 70%, #1e1b4b 100%);
            background-size: 300% 300%;
            animation: gradientShift 12s ease infinite;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            color: #fff;
            position: relative;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }

        /* ===== ENHANCED BACKGROUND GRAPHICS ===== */
        
        /* Medical/Healthcare themed floating icons */
        .medical-icons {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .medical-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.03);
            font-size: 4rem;
            animation: floatIcon 15s infinite linear;
            z-index: 1;
        }

        @keyframes floatIcon {
            0% {
                transform: translateY(0) rotate(0deg) scale(1);
                opacity: 0.03;
            }
            50% {
                transform: translateY(-20px) rotate(10deg) scale(1.1);
                opacity: 0.06;
            }
            100% {
                transform: translateY(0) rotate(0deg) scale(1);
                opacity: 0.03;
            }
        }

        .medical-icon:nth-child(1) { top: 10%; left: 5%; animation-delay: 0s; }
        .medical-icon:nth-child(2) { top: 60%; left: 15%; animation-delay: 2s; font-size: 5rem; }
        .medical-icon:nth-child(3) { top: 20%; right: 10%; animation-delay: 4s; font-size: 3.5rem; }
        .medical-icon:nth-child(4) { bottom: 15%; right: 20%; animation-delay: 6s; font-size: 4.5rem; }
        .medical-icon:nth-child(5) { top: 40%; left: 40%; animation-delay: 8s; font-size: 6rem; }
        .medical-icon:nth-child(6) { bottom: 30%; left: 30%; animation-delay: 10s; font-size: 3rem; }

        /* Animated grid pattern */
        .grid-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(79, 70, 229, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79, 70, 229, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 1;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Animated floating particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 3;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            pointer-events: none;
            animation: float 20s infinite linear;
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.3);
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
            }
        }

        /* Abstract shapes with medical cross patterns */
        .bg-abstract-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            opacity: 0.5;
        }

        .shape1 {
            top: -20%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: linear-gradient(45deg, #4f46e5, #06b6d4);
            animation: pulse 8s ease infinite;
        }

        .shape2 {
            bottom: -20%;
            right: -10%;
            width: 700px;
            height: 700px;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            animation: pulse 8s ease infinite reverse;
        }

        .shape3 {
            top: 30%;
            right: 30%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #10b981, #059669);
            animation: pulse 6s ease infinite;
            opacity: 0.3;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        /* ===== ENHANCED ICON STYLES ===== */
        
        .input-group {
            position: relative;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .input-group-text {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            color: white;
            border-radius: 1rem 0 0 1rem;
            padding: 0 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }

        .input-group-text::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .input-group:hover .input-group-text::before {
            width: 100px;
            height: 100px;
        }

        .input-group-text i {
            font-size: 1.3rem;
            position: relative;
            z-index: 2;
            animation: iconPulse 2s ease infinite;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .form-control {
            padding: 1.2rem 1rem;
            border-radius: 0 1rem 1rem 0;
            border: 2px solid transparent;
            background-color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            transition: all 0.3s;
            color: #1f2937;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            border-color: #4f46e5;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15), 0 4px 15px rgba(79, 70, 229, 0.2);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 300;
        }

        /* Animated icons for validation states */
        .input-group .fa-check-circle {
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            color: #10b981;
            font-size: 1.2rem;
            opacity: 0;
            transition: all 0.3s;
        }

        .input-group.valid .fa-check-circle {
            right: 15px;
            opacity: 1;
        }

        /* Rest of your existing styles remain the same */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .brand-section {
            padding-right: 3rem;
            position: relative;
            z-index: 20;
            animation: slideInLeft 1s ease;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .brand-icon {
            width: 220px;
            height: auto;
            margin-bottom: 2rem;
            filter: drop-shadow(0 20px 25px -5px rgba(0, 0, 0, 0.3));
            animation: floatIcon 6s ease infinite;
        }

        @keyframes floatIcon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .brand-title {
            font-weight: 800;
            color: #ffffff;
            font-size: 3.2rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.025em;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .brand-text {
            color: #e0e7ff;
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
            max-width: 500px;
            opacity: 0.9;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
            color: #ffffff;
            font-weight: 500;
            font-size: 1.1rem;
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
            animation-delay: calc(var(--item-index) * 0.2s);
        }

        .feature-item:nth-child(1) { --item-index: 1; }
        .feature-item:nth-child(2) { --item-index: 2; }
        .feature-item:nth-child(3) { --item-index: 3; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-icon {
            color: #10b981;
            margin-right: 1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem;
            border-radius: 50%;
            backdrop-filter: blur(4px);
            transition: transform 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            transform: scale(1.2);
            background: rgba(16, 185, 129, 0.2);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 2rem;
            box-shadow: 
                0 30px 60px -15px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset,
                0 0 30px rgba(79, 70, 229, 0.2);
            padding: 3.5rem;
            width: 100%;
            position: relative;
            overflow: hidden;
            z-index: 20;
            animation: slideInRight 1s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #ec4899, #4f46e5, #06b6d4, #10b981);
            background-size: 300% 300%;
            animation: gradientFlow 6s ease infinite;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: #4f46e5;
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1, #8b5cf6);
            background-size: 200% 200%;
            border: none;
            padding: 1.2rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 15px -3px rgba(79, 70, 229, 0.4);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(79, 70, 229, 0.6);
            background-position: 100% 100%;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            border-radius: 0.3em;
            border: 2px solid #4f46e5;
            transition: all 0.2s;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .form-check-label {
            cursor: pointer;
            transition: color 0.2s;
        }

        .form-check-label:hover {
            color: #4f46e5;
        }

        .text-primary.fw-bold {
            position: relative;
            display: inline-block;
            transition: all 0.3s;
        }

        .text-primary.fw-bold::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #4f46e5, #8b5cf6);
            transition: width 0.3s;
        }

        .text-primary.fw-bold:hover::after {
            width: 100%;
        }

        .alert {
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991px) {
            .brand-section {
                padding-right: 0;
                margin-bottom: 3rem;
                text-align: center;
            }

            .brand-icon {
                margin-left: auto;
                margin-right: auto;
            }

            .feature-item {
                justify-content: center;
            }

            .login-card {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- ENHANCED BACKGROUND GRAPHICS -->
    
    <!-- Medical/Healthcare themed floating icons -->
    <div class="medical-icons">
        <i class="fas fa-hospital medical-icon"></i>
        <i class="fas fa-stethoscope medical-icon"></i>
        <i class="fas fa-heartbeat medical-icon"></i>
        <i class="fas fa-ambulance medical-icon"></i>
        <i class="fas fa-user-md medical-icon"></i>
        <i class="fas fa-clinic-medical medical-icon"></i>
        <i class="fas fa-syringe medical-icon"></i>
        <i class="fas fa-capsules medical-icon"></i>
        <i class="fas fa-notes-medical medical-icon"></i>
        <i class="fas fa-bone medical-icon"></i>
        <i class="fas fa-brain medical-icon"></i>
        <i class="fas fa-lungs medical-icon"></i>
        <i class="fas fa-heart medical-icon"></i>
        <i class="fas fa-tooth medical-icon"></i>
        <i class="fas fa-eye medical-icon"></i>
    </div>

    <!-- Animated grid pattern -->
    <div class="grid-pattern"></div>
    
    <!-- Animated Particles -->
    <div class="particles" id="particles"></div>
    
    <!-- Abstract Background Shapes -->
    <div class="bg-abstract-shape shape1"></div>
    <div class="bg-abstract-shape shape2"></div>
    <div class="bg-abstract-shape shape3"></div>
    
    <div class="login-wrapper">
        <div class="container">
            <div class="row align-items-center justify-content-center">

                <!-- Left Side: Enhanced Branding -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="brand-section">
                        <img src="{{ asset('logo3.png') }}" alt="Logo" class="brand-icon">
                        <h1 class="brand-title">ABC Mission Hospital<br>Leave Management System</h1>
                        <p class="brand-text">
                            Welcome to your centralized employee portal. Experience a simpler, faster, and more transparent way to
                            manage your time off with our intelligent leave management platform.
                        </p>

                        <ul class="feature-list">
                            <li class="feature-item">
                                <i class="fas fa-chart-line feature-icon"></i>
                                <span>Real-time leave balance tracking with analytics</span>
                            </li>
                            <li class="feature-item">
                                <i class="fas fa-bell feature-icon"></i>
                                <span>Seamless approval workflows with instant notifications</span>
                            </li>
                            <li class="feature-item">
                                <i class="fas fa-file-alt feature-icon"></i>
                                <span>Detailed leave history & interactive reports</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Side: Enhanced Login Form with Better Icons -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="login-card">
                        <div class="mb-5 text-center text-lg-start">
                            <h3 class="fw-bold text-dark mb-2" style="font-size: 2rem;">
                                <i class="fas fa-user-circle text-primary me-2"></i>Welcome Back!
                            </h3>
                            <p class="text-muted small">Sign in to access your employee dashboard</p>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-10 text-success"
                                role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-10 text-danger"
                                role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Login Failed:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-times-circle me-2" style="font-size: 0.8rem;"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf

                            <!-- Employee Number with Enhanced Icon -->
                            <div class="mb-4">
                                <label for="EmployeeNumber" class="form-label">
                                    <i class="fas fa-id-card"></i> Employee ID
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-id-badge"></i>
                                    </span>
                                    <input id="EmployeeNumber" type="text" name="EmployeeNumber"
                                        class="form-control @error('EmployeeNumber') is-invalid @enderror"
                                        placeholder="EMP001" value="{{ old('EmployeeNumber') }}" required autofocus>
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <small class="text-muted ms-2">
                                    <i class="fas fa-info-circle me-1"></i>Enter your 6-digit employee ID
                                </small>
                            </div>

                            <!-- Password with Enhanced Icon -->
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key"></i> Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter your password" required>
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>Password is encrypted
                                    </small>
                                </div>
                            </div>

                            <!-- Actions with Enhanced Design -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted fw-semibold" for="remember">
                                        <i class="fas fa-save me-1"></i>Keep me signed in
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}"
                                    class="text-decoration-none small text-primary fw-bold">
                                    <i class="fas fa-question-circle me-1"></i>Forgot password?
                                </a>
                            </div>

                            <!-- Enhanced Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary text-white">
                                    <i class="fas fa-sign-in-alt me-2"></i> Sign In to Dashboard
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                            
                            <!-- Additional Info -->
                            <div class="text-center mt-4">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i> Secure login • SSL encrypted
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-1"></i> Session timeout: 30 min
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Particles and Form Validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create particles
            const particlesContainer = document.getElementById('particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random size
                const size = Math.random() * 8 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                
                // Random position
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // Random animation delay
                particle.style.animationDelay = Math.random() * 20 + 's';
                
                // Random animation duration
                particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
                
                // Random opacity
                particle.style.opacity = Math.random() * 0.3 + 0.1;
                
                particlesContainer.appendChild(particle);
            }

            // Simple validation for icons (optional)
            const employeeInput = document.getElementById('EmployeeNumber');
            const passwordInput = document.getElementById('password');
            
            employeeInput.addEventListener('input', function() {
                const inputGroup = this.closest('.input-group');
                if (this.value.length >= 3) {
                    inputGroup.classList.add('valid');
                } else {
                    inputGroup.classList.remove('valid');
                }
            });

            passwordInput.addEventListener('input', function() {
                const inputGroup = this.closest('.input-group');
                if (this.value.length >= 6) {
                    inputGroup.classList.add('valid');
                } else {
                    inputGroup.classList.remove('valid');
                }
            });
        });
    </script>
@endsection