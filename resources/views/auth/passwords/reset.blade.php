@extends('layouts.auth')

@section('title', 'Reset Password')

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

            .brand-icon,
            .brand-text {
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
                            <img src="{{ asset('logo3.png') }}" alt="Logo" class="img-fluid"
                                style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <h1 class="brand-title">Set New Password</h1>
                        <p class="brand-text">
                            Create a strong password to secure your account. Choose something unique that you'll remember.
                        </p>

                        <ul class="feature-list">
                            <li class="feature-item">
                                <i class="fas fa-check feature-icon"></i>
                                <span>At least 8 characters long</span>
                            </li>
                            <li class="feature-item">
                                <i class="fas fa-check feature-icon"></i>
                                <span>Include letters and numbers</span>
                            </li>
                            <li class="feature-item">
                                <i class="fas fa-check feature-icon"></i>
                                <span>Avoid common passwords</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Side: Reset Password Form -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="login-card">
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark">Set New Password</h3>
                            <p class="text-muted small">Enter your email and choose a secure new password</p>
                        </div>

                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show small border-0 bg-success bg-opacity-10 text-success"
                                role="alert">
                                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Error Messages -->
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

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fas fa-envelope"></i></span>
                                    <input id="email" type="email"
                                        class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ $email ?? old('email') }}" required autofocus
                                        placeholder="name@example.com">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fas fa-key"></i></span>
                                    <input id="password" type="password"
                                        class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                        name="password" required placeholder="At least 8 characters">
                                </div>
                                <div id="password-strength" class="mt-1 small"></div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fas fa-lock"></i></span>
                                    <input id="password-confirm" type="password" class="form-control border-start-0 ps-0"
                                        name="password_confirmation" required placeholder="Repeat new password">
                                </div>
                                <div id="password-match" class="mt-1 small fw-bold"></div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary text-white">
                                    <i class="fas fa-save me-2"></i> Reset Password
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-decoration-none small text-primary fw-bold">
                                    ← Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password-confirm');
            const strengthIndicator = document.getElementById('password-strength');
            const matchIndicator = document.getElementById('password-match');
            const submitBtn = document.querySelector('button[type="submit"]');

            function validatePassword() {
                const val = password.value;
                if (!val) {
                    strengthIndicator.innerHTML = '';
                    password.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                let strength = 0;
                let checks = {
                    length: val.length >= 8,
                    uppercase: /[A-Z]/.test(val),
                    lowercase: /[a-z]/.test(val),
                    number: /[0-9]/.test(val),
                    special: /[^A-Za-z0-9]/.test(val)
                };

                if (checks.length) strength++;
                if (checks.uppercase) strength++;
                if (checks.lowercase) strength++;
                if (checks.number) strength++;
                if (checks.special) strength++;

                let color = 'text-danger';
                let feedbackText = 'Weak';
                let width = '25%';
                let barColor = 'bg-danger';

                if (strength >= 4) {
                    color = 'text-success';
                    feedbackText = 'Strong';
                    width = '100%';
                    barColor = 'bg-success';
                } else if (strength >= 3) {
                    color = 'text-warning';
                    feedbackText = 'Medium';
                    width = '60%';
                    barColor = 'bg-warning';
                }

                strengthIndicator.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="${color} fw-bold">Strength: ${feedbackText}</span>
                    <span class="text-muted" style="font-size: 0.75rem;">(Length, Upper, Lower, Num, Special)</span>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar ${barColor}" role="progressbar" style="width: ${width}"></div>
                </div>
            `;

                if (strength >= 3) {
                    password.classList.add('is-valid');
                    password.classList.remove('is-invalid');
                } else {
                    password.classList.add('is-invalid');
                    password.classList.remove('is-valid');
                }

                checkMatch();
            }

            function checkMatch() {
                if (!confirmPassword.value) {
                    matchIndicator.innerText = '';
                    confirmPassword.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                if (password.value === confirmPassword.value) {
                    matchIndicator.innerText = '✓ Passwords match';
                    matchIndicator.className = 'mt-1 small fw-bold text-success';
                    confirmPassword.classList.add('is-valid');
                    confirmPassword.classList.remove('is-invalid');
                    submitBtn.disabled = !password.classList.contains('is-valid');
                } else {
                    matchIndicator.innerText = '✗ Passwords do not match';
                    matchIndicator.className = 'mt-1 small fw-bold text-danger';
                    confirmPassword.classList.add('is-invalid');
                    confirmPassword.classList.remove('is-valid');
                    submitBtn.disabled = true;
                }
            }

            password.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', checkMatch);
        });
    </script>
@endsection