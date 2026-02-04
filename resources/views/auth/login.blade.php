@extends('layouts.auth')

@section('title', 'Login')

@section('styles')
<style>
/* === MATCHING DEPARTMENTS PAGE THEME === */
.login-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.login-card {
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    overflow: hidden;
    border: 1px solid #e9ecef;
    width: 100%;
    max-width: 500px; /* Increased from 420px to 500px */
    position: relative;
}

/* Creamy White Header - Matching Departments */
.login-header {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    color: #2E3A87;
    padding: 35px 30px; /* Increased padding */
    border-bottom: 2px solid #e9ecef;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.login-header:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

.login-header h2 {
    font-size: 32px; /* Increased font size */
    font-weight: 700;
    margin-bottom: 10px; /* Increased margin */
    color: #2E3A87;
}

.login-header p {
    color: #6c757d;
    font-size: 16px; /* Increased font size */
    margin-bottom: 0;
}

/* Login Body */
.login-body {
    padding: 35px 40px; /* Increased padding */
}

/* Form Styling - Matching Departments */
.form-group {
    margin-bottom: 25px; /* Increased margin */
}

.form-label {
    font-weight: 600;
    color: #2E3A87;
    margin-bottom: 10px; /* Increased margin */
    font-size: 15px; /* Increased font size */
    display: flex;
    align-items: center;
    gap: 10px; /* Increased gap */
}

.form-label i {
    font-size: 18px; /* Increased icon size */
    color: #4A5BD9;
}

.form-control {
    border-radius: 12px; /* Increased border radius */
    border: 1px solid #e9ecef;
    padding: 14px 18px; /* Increased padding */
    font-size: 16px; /* Increased font size */
    transition: all 0.3s ease;
    background-color: #ffffff;
    height: 52px; /* Fixed height for consistency */
}

.form-control:focus {
    border-color: #2E3A87;
    box-shadow: 0 0 0 4px rgba(46, 58, 135, 0.1); /* Increased shadow */
    outline: none;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1); /* Increased shadow */
}

/* Input Group with Icons */
.input-group {
    position: relative;
}

.input-group-icon {
    position: absolute;
    left: 18px; /* Adjusted position */
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 4;
    font-size: 18px; /* Increased icon size */
}

.input-group .form-control {
    padding-left: 55px; /* Increased padding for larger icons */
}

.input-group-text {
    background: white;
    border-left: none;
    padding: 0 18px; /* Increased padding */
    cursor: pointer;
    transition: all 0.3s ease;
    height: 52px; /* Match input height */
    display: flex;
    align-items: center;
    justify-content: center;
}

.input-group-text:hover {
    background: #f8f9fa;
}

.input-group-text i {
    font-size: 18px; /* Increased icon size */
    color: #6c757d;
}

/* Alert Messages - Matching Departments */
.alert {
    border-radius: 12px; /* Increased border radius */
    border: none;
    padding: 20px 25px; /* Increased padding */
    margin-bottom: 30px; /* Increased margin */
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08); /* Increased shadow */
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border-left: 5px solid #28a745; /* Increased border */
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-left: 5px solid #dc3545; /* Increased border */
}

.alert i {
    margin-right: 12px; /* Increased margin */
    font-size: 20px; /* Increased icon size */
}

.alert ul {
    margin-bottom: 0;
    padding-left: 25px; /* Increased padding */
}

.alert ul li {
    margin-bottom: 6px; /* Increased margin */
    font-size: 15px; /* Increased font size */
}

.alert ul li:last-child {
    margin-bottom: 0;
}

/* Buttons - Matching Departments */
.btn {
    border-radius: 12px; /* Increased border radius */
    font-weight: 600;
    padding: 16px 28px; /* Increased padding */
    font-size: 17px; /* Increased font size */
    transition: all 0.3s ease;
    border: none;
    height: 56px; /* Fixed height */
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    box-shadow: 0 5px 20px rgba(46, 58, 135, 0.25); /* Increased shadow */
}

.btn-primary:hover {
    transform: translateY(-3px); /* Increased transform */
    box-shadow: 0 8px 25px rgba(46, 58, 135, 0.35); /* Increased shadow */
    background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
}

.btn-primary:active {
    transform: translateY(-1px);
}

/* Remember Me & Forgot Password */
.login-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px; /* Increased margin */
    font-size: 15px; /* Increased font size */
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 10px; /* Increased gap */
}

.remember-me input[type="checkbox"] {
    width: 20px; /* Increased size */
    height: 20px; /* Increased size */
    border-radius: 6px; /* Increased border radius */
    border: 2px solid #dee2e6; /* Increased border */
    cursor: pointer;
    transition: all 0.3s ease;
}

.remember-me input[type="checkbox"]:checked {
    background-color: #2E3A87;
    border-color: #2E3A87;
}

.remember-me label {
    color: #6c757d;
    cursor: pointer;
    font-weight: 500;
    font-size: 15px; /* Increased font size */
}

.forgot-password a {
    color: #2E3A87;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px; /* Increased font size */
    transition: color 0.2s ease;
}

.forgot-password a:hover {
    color: #4A5BD9;
    text-decoration: underline;
}

/* Registration Link */
.registration-link {
    text-align: center;
    margin-top: 30px; /* Increased margin */
    padding-top: 25px; /* Increased padding */
    border-top: 2px solid #e9ecef; /* Increased border */
    color: #6c757d;
    font-size: 16px; /* Increased font size */
}

.registration-link p {
    margin-bottom: 0;
}

.registration-link a {
    color: #2E3A87;
    text-decoration: none;
    font-weight: 700; /* Increased weight */
    font-size: 16px; /* Increased font size */
    transition: color 0.2s ease;
    margin-left: 5px;
}

.registration-link a:hover {
    color: #4A5BD9;
    text-decoration: underline;
}

/* Invalid Feedback */
.invalid-feedback {
    font-size: 14px; /* Increased font size */
    color: #dc3545;
    margin-top: 8px; /* Increased margin */
    display: flex;
    align-items: center;
    gap: 8px; /* Increased gap */
}

.invalid-feedback i {
    font-size: 16px; /* Increased icon size */
}

/* Loading Animation */
.btn-loading .spinner-border {
    width: 1.2rem; /* Increased size */
    height: 1.2rem; /* Increased size */
    border-width: 0.25em; /* Increased border */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .login-container {
        padding: 15px;
    }
    
    .login-card {
        max-width: 100%;
    }
    
    .login-header {
        padding: 30px 25px;
    }
    
    .login-body {
        padding: 30px 25px;
    }
    
    .login-header h2 {
        font-size: 28px;
    }
    
    .login-header p {
        font-size: 15px;
    }
    
    .login-options {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .btn {
        padding: 14px 24px;
        font-size: 16px;
        height: 52px;
    }
    
    .form-control {
        padding: 12px 16px;
        font-size: 15px;
        height: 48px;
    }
}

@media (max-width: 576px) {
    .login-header {
        padding: 25px 20px;
    }
    
    .login-body {
        padding: 25px 20px;
    }
    
    .login-header h2 {
        font-size: 24px;
    }
    
    .btn {
        padding: 13px 22px;
        font-size: 15px;
        height: 50px;
    }
    
    .form-control {
        padding: 11px 15px;
        font-size: 14px;
        height: 46px;
    }
    
    .alert {
        padding: 18px 22px;
    }
}

/* Animation for form */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px); /* Increased transform */
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Logo/Symbol */
.login-symbol {
    width: 90px; /* Increased size */
    height: 90px; /* Increased size */
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px; /* Increased font size */
    margin: 0 auto 20px; /* Increased margin */
    box-shadow: 0 6px 20px rgba(46, 58, 135, 0.4); /* Increased shadow */
}

/* Spacing adjustments for wider form */
.login-body .d-grid {
    margin-top: 10px; /* Added margin */
}

/* Custom width for better proportion */
.login-card {
    min-height: 580px; /* Minimum height for better proportion */
    display: flex;
    flex-direction: column;
}

.login-header {
    flex-shrink: 0;
}

.login-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="login-symbol">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2>Welcome Back</h2>
            <p>Sign in to access your account</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong style="color: #dc3545;">Login Failed</strong>
                    <ul class="mt-2 mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Employee Number -->
                <div class="form-group">
                    <label for="EmployeeNumber" class="form-label">
                        <i class="fas fa-id-badge"></i>
                        Employee Number
                    </label>
                    <div class="input-group">
                        <div class="input-group-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <input id="EmployeeNumber" type="text" name="EmployeeNumber" 
                               class="form-control @error('EmployeeNumber') is-invalid @enderror" 
                               value="{{ old('EmployeeNumber') }}" 
                               placeholder="Enter your employee number" 
                               required autofocus>
                    </div>
                    @error('EmployeeNumber')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="input-group">
                        <div class="input-group-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <input id="password" type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Enter your password" 
                               required>
                        <span class="input-group-text" onclick="togglePassword()">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Login Options -->
                <div class="login-options">
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="loginButton">
                        <span class="submit-text">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>

            <!-- Registration Link -->
            <div class="registration-link">
                <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    window.togglePassword = function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    };

    // Form submission loader
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    
    if (loginForm && loginButton) {
        loginForm.addEventListener('submit', function() {
            const submitText = loginButton.querySelector('.submit-text');
            const spinner = loginButton.querySelector('.spinner-border');
            
            if (submitText && spinner) {
                submitText.classList.add('d-none');
                spinner.classList.remove('d-none');
                loginButton.disabled = true;
            }
        });
    }

    // Auto-focus on Employee Number field
    const employeeNumberField = document.getElementById('EmployeeNumber');
    if (employeeNumberField) {
        employeeNumberField.focus();
    }

    // Add input validation feedback
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Add enter key submission
    loginForm.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !loginButton.disabled) {
            loginButton.click();
        }
    });

    // Check for saved credentials
    const savedEmployeeNumber = localStorage.getItem('rememberedEmployeeNumber');
    if (savedEmployeeNumber) {
        employeeNumberField.value = savedEmployeeNumber;
        document.getElementById('remember').checked = true;
    }

    // Save credentials if remember is checked
    const rememberCheckbox = document.getElementById('remember');
    if (rememberCheckbox) {
        rememberCheckbox.addEventListener('change', function() {
            if (this.checked && employeeNumberField.value) {
                localStorage.setItem('rememberedEmployeeNumber', employeeNumberField.value);
            } else {
                localStorage.removeItem('rememberedEmployeeNumber');
            }
        });
    }

    // Form validation on submit
    loginForm.addEventListener('submit', function(e) {
        const employeeNumber = employeeNumberField.value.trim();
        const password = document.getElementById('password').value;
        
        if (!employeeNumber) {
            e.preventDefault();
            employeeNumberField.classList.add('is-invalid');
            employeeNumberField.focus();
            return false;
        }
        
        if (!password) {
            e.preventDefault();
            document.getElementById('password').classList.add('is-invalid');
            document.getElementById('password').focus();
            return false;
        }
    });
});
</script>
@endsection