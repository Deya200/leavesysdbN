@extends('layouts.auth')

@section('title', 'Login')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        overflow-x: hidden;
    }
    
    .login-wrapper {
        min-height: 100vh; 
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    /* Left Side Content */
    .brand-section {
        padding-right: 3rem;
    }
    
    .brand-icon {
        width: 60px; 
        height: 60px;
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .brand-title {
        font-weight: 800;
        color: #1e1b4b;
        font-size: 2.5rem;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        letter-spacing: -0.025em;
    }

    .brand-text {
        color: #4b5563;
        font-size: 1.125rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        max-width: 480px;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        color: #374151;
        font-weight: 500;
    }

    .feature-icon {
        color: #10b981;
        margin-right: 0.75rem;
        background: #ecfdf5;
        padding: 0.25rem;
        border-radius: 50%;
    }

    /* Right Side Card */
    .login-card {
        background: white;
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        padding: 3rem;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #4f46e5, #06b6d4);
    }

    .form-control {
        padding: 0.875rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background-color: #f9fafb;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #4f46e5;
        background-color: white;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        border: none;
        padding: 0.875rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    @media (max-width: 768px) {
        .brand-section {
            padding-right: 0;
            margin-bottom: 3rem;
            text-align: center;
        }
        
        .brand-icon, .brand-text {
            margin-left: auto;
            margin-right: auto;
        }

        .feature-item {
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            
            <!-- Left Side: Branding & Info -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="brand-section">
                    <div class="brand-icon" style="background: white; padding: 10px;">
                        <img src="{{ asset('logo3.png') }}" alt="Logo" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <h1 class="brand-title">ABC Leave Management System</h1>
                    <p class="brand-text">
                        Welcome to your centralized employee portal. A simpler, faster, and more transparent way to manage time off.
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
                        <div class="alert alert-success alert-dismissible fade show small border-0 bg-success bg-opacity-10 text-success" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show small border-0 bg-danger bg-opacity-10 text-danger" role="alert">
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
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-id-badge"></i></span>
                                <input id="EmployeeNumber" type="text" name="EmployeeNumber" class="form-control border-start-0 ps-0 @error('EmployeeNumber') is-invalid @enderror" placeholder="EMP001" value="{{ old('EmployeeNumber') }}" required autofocus>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary fw-bold">
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
