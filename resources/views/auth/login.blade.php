@extends('layouts.auth')

@section('title', 'Login')

@section('styles')
    <style>
        body {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 70%, #4f46e5 100%);
            background-size: 200% 200%;
            animation: gradientAnimation 15s ease infinite;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            color: #fff;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        /* Abstract Background Shapes */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
        }

        .shape1 {
            top: -10%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: #06b6d4;
        }

        .shape2 {
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: #ec4899;
        }

        /* Left Side Content */
        .brand-section {
            padding-right: 3rem;
            position: relative;
            z-index: 20;
        }

        .brand-icon {
            width: 200px;
            height: auto;
            margin-bottom: 2rem;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.3));
        }

        .brand-title {
            font-weight: 800;
            color: #ffffff;
            font-size: 3rem;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -0.025em;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .brand-text {
            color: #e0e7ff;
            font-size: 1.15rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            max-width: 500px;
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
            font-size: 1.05rem;
        }

        .feature-icon {
            color: #10b981;
            margin-right: 1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem;
            border-radius: 50%;
            backdrop-filter: blur(4px);
        }

        /* Right Side Card - Glassmorphism */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            padding: 3.5rem;
            width: 100%;
            position: relative;
            overflow: hidden;
            z-index: 20;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #ec4899, #4f46e5, #06b6d4);
        }

        .form-control {
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            font-size: 1rem;
            transition: all 0.3s;
            color: #1f2937;
        }

        .form-control:focus {
            border-color: #4f46e5;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            outline: none;
        }

        .input-group-text {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            color: #6b7280;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            padding: 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3), 0 2px 4px -1px rgba(79, 70, 229, 0.06);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4), 0 4px 6px -2px rgba(79, 70, 229, 0.05);
            background: linear-gradient(135deg, #4338ca, #4f46e5);
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
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>
    <div class="login-wrapper">
        <div class="container">
            <div class="row align-items-center justify-content-center">

                <!-- Left Side: Branding & Info -->
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="brand-section">
                        <img src="{{ asset('logo3.png') }}" alt="Logo" class="brand-icon">
                        <h1 class="brand-title">ABC Mission Hospital Leave Management System</h1>
                        <p class="brand-text">
                            Welcome to your centralized employee portal. A simpler, faster, and more transparent way to
                            manage time off.
                        </p>

                        <ul class="feature-list">
                            <li class="feature-item">
                                <i class="fas fa-check feature-icon"></i>
                                <span>Real-time leave balance tracking</span>
                            </li>
                            <li class="feature-item">
                                <i class="fas fa-check feature-icon"></i>
                                <span>Seamless approval workflows</span>
                            </li>
                            <li class="feature-item">
                                <i class="fas fa-check feature-icon"></i>
                                <span>Detailed leave history & reports</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Side: Login Form -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="login-card">
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark">Sign In</h3>
                            <p class="text-muted small">Available for all employees and supervisors</p>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show small border-0 bg-success bg-opacity-10 text-success"
                                role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show small border-0 bg-danger bg-opacity-10 text-danger"
                                role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Employee Number -->
                            <div class="mb-4">
                                <label for="EmployeeNumber" class="form-label">Employee ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fas fa-id-badge"></i></span>
                                    <input id="EmployeeNumber" type="text" name="EmployeeNumber"
                                        class="form-control border-start-0 ps-0 @error('EmployeeNumber') is-invalid @enderror"
                                        placeholder="EMP001" value="{{ old('EmployeeNumber') }}" required autofocus>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fas fa-lock"></i></span>
                                    <input id="password" type="password" name="password"
                                        class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                        placeholder="••••••••" required>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted fw-semibold" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}"
                                    class="text-decoration-none small text-primary fw-bold">
                                    Forgot password?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary text-white">
                                    Sign In
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection