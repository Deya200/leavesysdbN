@extends('layouts.auth')

@section('title', 'Login')

@section('styles')
    <style>
        /* Using system fonts and local Font Awesome */
        
        body {
            background-image: url('{{ asset("images/login.png") }}');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            color: #fff;
            position: relative;
            margin: 0;
            padding: 0;
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

        /* ===== ADVANCED GRAPHICAL EFFECTS ===== */
        
        /* Enhanced particle system */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 3;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.8), rgba(79, 70, 229, 0.4));
            animation: floatParticle 25s infinite linear;
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.6);
            backdrop-filter: blur(1px);
        }

        .particle:nth-child(odd) {
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.6), rgba(236, 72, 153, 0.3));
            box-shadow: 0 0 15px rgba(236, 72, 153, 0.5);
        }

        .particle:nth-child(3n) {
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.7), rgba(16, 185, 129, 0.4));
            box-shadow: 0 0 18px rgba(16, 185, 129, 0.6);
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg) scale(1);
                opacity: 0.1;
            }
            25% {
                transform: translateY(-30vh) translateX(10vw) rotate(90deg) scale(1.2);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-60vh) translateX(-5vw) rotate(180deg) scale(0.8);
                opacity: 0.2;
            }
            75% {
                transform: translateY(-90vh) translateX(15vw) rotate(270deg) scale(1.1);
                opacity: 0.4;
            }
            100% {
                transform: translateY(-120vh) translateX(0) rotate(360deg) scale(1);
                opacity: 0.1;
            }
        }

        /* Geometric shapes overlay */
        .geometric-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            opacity: 0.05;
            animation: shapeFloat 30s infinite ease-in-out;
        }

        .shape.circle {
            border-radius: 50%;
            background: linear-gradient(45deg, #4f46e5, #ec4899);
        }

        .shape.square {
            background: linear-gradient(45deg, #06b6d4, #10b981);
            transform: rotate(45deg);
        }

        .shape.triangle {
            width: 0;
            height: 0;
            border-left: 50px solid transparent;
            border-right: 50px solid transparent;
            border-bottom: 87px solid #8b5cf6;
            background: none;
        }

        @keyframes shapeFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            33% {
                transform: translateY(-20px) rotate(120deg) scale(1.1);
            }
            66% {
                transform: translateY(10px) rotate(240deg) scale(0.9);
            }
        }

        /* Enhanced glow effects */
        .glow-effect {
            display: none !important;
        }

        .medical-icons,
        .grid-pattern,
        .particles,
        .geometric-shapes,
        .bg-abstract-shape,
        .shape1,
        .shape2,
        .shape3 {
            display: none !important;
        }

        @keyframes glowPulse {
            0%, 100% {
                opacity: 0.3;
                transform: scale(1);
            }
            50% {
                opacity: 0.6;
                transform: scale(1.2);
            }
        }

        /* Interactive hover effects */
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 40px 80px -20px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset,
                0 0 40px rgba(79, 70, 229, 0.3);
        }

        .input-group:hover .input-group-text {
            background: none;
            transform: scale(1.05);
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.8);
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
            background: rgba(79, 70, 229, 0.12);
            border: 1px solid rgba(15, 23, 42, 0.08);
            color: #4f46e5;
            border-radius: 1rem 0 0 1rem;
            padding: 0 1.5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: none;
        }

        .input-group-text::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: none;
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
            border: 1px solid rgba(15, 23, 42, 0.1);
            background-color: rgba(255, 255, 255, 0.95);
            font-size: 1rem;
            transition: all 0.3s;
            color: #111827;
            box-shadow: inset 0 1px 3px rgba(15, 23, 42, 0.08);
        }

        .form-control:focus {
            border-color: #4f46e5;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12), 0 4px 15px rgba(79, 70, 229, 0.12);
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

        .input-group.invalid .input-group-text {
            background: none;
        }

        .input-group.invalid .form-control {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* ===== RESPONSIVE FULL-SCREEN DESIGN ===== */
        
        .login-wrapper {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
            overflow: auto;
        }

        .login-container {
            width: 100%;
            max-width: 1400px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 3rem;
        }

        /* ===== ENHANCED BRAND SECTION ===== */
        
        .brand-section {
            flex: 1;
            padding: 3rem 2rem;
            text-align: left;
            position: relative;
            z-index: 20;
            animation: slideInLeft 1s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            max-width: 600px;
        }

        @media (max-width: 1000px) {
            .brand-section {
                display: none;
            }
            
            .login-container {
                max-width: 100%;
                justify-content: center;
            }
            
            .login-section {
                max-width: 450px;
            }
        }
        
        @media (max-height: 600px) {
            .login-wrapper {
                overflow-y: auto;
                min-height: 100vh;
            }
        }

        .brand-icon {
            width: 250px;
            height: auto;
            margin-bottom: 2rem;
            filter: drop-shadow(0 20px 25px -5px rgba(0, 0, 0, 0.3));
            animation: floatIcon 6s ease infinite;
        }

        @keyframes floatIcon {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }

        .brand-title {
            font-weight: 800;
            color: #28a745;
            font-size: 2.8rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.025em;
            text-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            align-items: flex-start;
        }

        .feature-item {
            display: flex;
            align-items: center;
            color: #111827;
            font-weight: 500;
            font-size: 1rem;
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
            animation-delay: calc(var(--item-index) * 0.2s);
            background: rgba(255, 255, 255, 0.72);
            padding: 1rem;
            border-radius: 1rem;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(15, 23, 42, 0.08);
            transition: all 0.3s ease;
            text-shadow: none;
            text-align: left;
            justify-content: flex-start;
            width: 100%;
        }

        .feature-item:nth-child(1) { --item-index: 1; }
        .feature-item:nth-child(2) { --item-index: 2; }
        .feature-item:nth-child(3) { --item-index: 3; }

        .feature-item:hover {
            transform: translateX(10px);
            background: none;
            box-shadow: none;
        }

        .feature-item:hover .feature-icon {
            background: none;
        }

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
            background: none;
            padding: 0.5rem;
            border-radius: 50%;
            backdrop-filter: none;
            transition: transform 0.3s ease;
            font-size: 1.2rem;
        }

        .feature-item:hover .feature-icon {
            transform: scale(1.2) rotate(10deg);
            background: none;
        }

        /* ===== ENHANCED LOGIN CARD ===== */
        
        .login-section {
            flex: 0 0 auto;
            width: 100%;
            max-width: 480px;
            padding: 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.5rem;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.18);
            padding: 2.5rem;
            width: 100%;
            position: relative;
            overflow: hidden;
            z-index: 20;
            animation: slideInRight 1s ease;
            backdrop-filter: blur(12px);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
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
            background: #28a745;
            border: 1px solid #28a745;
            color: #fff;
            padding: 1.2rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 12px 24px rgba(18, 99, 44, 0.2);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .btn-primary::before {
            display: none;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 30px rgba(18, 99, 44, 0.3);
        }

        .btn-primary:hover::before {
            width: 0;
            height: 0;
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
                display: none; /* Hide brand section on mobile for better focus */
            }

            .login-section {
                max-width: 100%;
                padding: 1rem;
            }

            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                border-radius: 1.5rem;
            }

            .brand-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
            }

            .brand-title {
                font-size: 1.8rem;
            }

            .feature-item {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Left Side: Enhanced Branding -->
            <div class="brand-section">
                <img src="{{ asset('logo3.png') }}" alt="Logo" class="brand-icon">
                <h1 class="brand-title">ABC Mission Hospital<br>Leave Management System</h1>
                
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

            <!-- Right Side: Enhanced Login Form -->
            <div class="login-section">
                <div class="login-card">
                    <div class="mb-4 text-center">
                        <h3 class="fw-bold text-dark mb-2" style="font-size: 1.5rem;">
                            <i class="fas fa-user-circle text-primary me-2"></i>Welcome Back!
                        </h3>
                        <p class="text-muted" style="font-size: 0.9rem;">Sign in to access your dashboard</p>
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

    <!-- JavaScript for Enhanced Particles and Form Validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create enhanced particles
            const particlesContainer = document.getElementById('particles');
            const particleCount = 60;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                // Random size with more variety
                const size = Math.random() * 12 + 3;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                
                // Random position
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // Random animation delay and duration
                particle.style.animationDelay = Math.random() * 25 + 's';
                particle.style.animationDuration = (Math.random() * 15 + 20) + 's';
                
                // Random opacity
                particle.style.opacity = Math.random() * 0.4 + 0.1;
                
                particlesContainer.appendChild(particle);
            }

            // Enhanced form validation with visual feedback
            const employeeInput = document.getElementById('EmployeeNumber');
            const passwordInput = document.getElementById('password');
            const loginForm = document.getElementById('loginForm');
            
            function validateInput(input, minLength) {
                const inputGroup = input.closest('.input-group');
                if (input.value.length >= minLength) {
                    inputGroup.classList.add('valid');
                    inputGroup.classList.remove('invalid');
                } else {
                    inputGroup.classList.remove('valid');
                    if (input.value.length > 0) {
                        inputGroup.classList.add('invalid');
                    } else {
                        inputGroup.classList.remove('invalid');
                    }
                }
            }

            employeeInput.addEventListener('input', function() {
                validateInput(this, 3);
            });

            passwordInput.addEventListener('input', function() {
                validateInput(this, 6);
            });

            // Add loading state to button
            loginForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Signing In...';
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds (in case of error)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });

            // Add smooth scrolling and focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.input-group').style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.closest('.input-group').style.transform = 'translateY(0)';
                });
            });
        });
    </script>
@endsection