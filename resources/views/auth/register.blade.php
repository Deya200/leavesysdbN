@extends('layouts.auth')

@section('title', 'Employee Registration')

@section('styles')
<style>
/* === MATCHING DEPARTMENTS PAGE THEME === */
.registration-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px; /* Increased padding */
    width: 100%;
}

.registration-card {
    background-color: #ffffff;
    min-height: 80vh;
    border-radius: 16px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15); /* Stronger shadow for depth */
    overflow: hidden;
    border: 1px solid #e9ecef;
    width: 100%;
    max-width: 100; /* WIDER: Increased from 600px to 900px */
    position: relative;
    margin: 0 auto;
}

.registration-body {
    flex: 1;
}

/* Creamy White Header - Matching Departments */
.registration-header {
    background: linear-gradient(135deg, #f8f5f0 0%, #fefefe 100%);
    color: #2E3A87;
    padding: 45px 50px; /* Increased padding */
    border-bottom: 2px solid #e9ecef;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.registration-header:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px; /* Thicker header line */
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
}

.registration-header h2 {
    font-size: 36px; /* Larger heading */
    font-weight: 700;
    margin-bottom: 12px;
    color: #2E3A87;
}

.registration-header p {
    color: #6c757d;
    font-size: 18px; /* Larger description */
    margin-bottom: 0;
    line-height: 1.6;
}

/* Registration Body */
.registration-body {
    padding: 45px 50px; /* Increased padding */
}

/* Form Styling - Matching Departments */
.form-group {
    margin-bottom: 28px; /* More spacing between form groups */
}

.form-label {
    font-weight: 600;
    color: #2E3A87;
    margin-bottom: 12px; /* More spacing */
    font-size: 16px; /* Slightly larger */
    display: flex;
    align-items: center;
    gap: 12px; /* More space between icon and text */
}

.form-label i {
    font-size: 20px; /* Larger icons */
    color: #4A5BD9;
}

.form-control, .form-select {
    border-radius: 12px;
    border: 1px solid #e9ecef;
    padding: 16px 20px; /* More padding for larger appearance */
    font-size: 17px; /* Larger text */
    transition: all 0.3s ease;
    background-color: #ffffff;
    height: 56px; /* Taller inputs */
}

.form-control:focus, .form-select:focus {
    border-color: #2E3A87;
    box-shadow: 0 0 0 5px rgba(46, 58, 135, 0.12); /* Larger focus shadow */
    outline: none;
}

.form-control.is-invalid, .form-select.is-invalid {
    border-color: #dc3545;
}

.form-control.is-invalid:focus, .form-select.is-invalid:focus {
    box-shadow: 0 0 0 5px rgba(220, 53, 69, 0.12); /* Larger invalid focus shadow */
}

/* File Upload Styling */
.form-control[type="file"] {
    padding: 13px 18px; /* More padding */
    height: auto;
}

.form-control[type="file"]::file-selector-button {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    border: none;
    padding: 12px 20px; /* Larger button */
    border-radius: 10px; /* More rounded */
    margin-right: 15px; /* More spacing */
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px; /* Larger text */
}

.form-control[type="file"]::file-selector-button:hover {
    background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
    transform: translateY(-2px); /* Lift effect on hover */
    box-shadow: 0 4px 12px rgba(46, 58, 135, 0.3);
}

/* Alert Messages - Matching Departments */
.alert {
    border-radius: 12px;
    border: none;
    padding: 22px 28px; /* More padding */
    margin-bottom: 35px; /* More spacing */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Stronger shadow */
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.12); /* More visible */
    color: #28a745;
    border-left: 6px solid #28a745; /* Thicker border */
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.12); /* More visible */
    color: #dc3545;
    border-left: 6px solid #dc3545; /* Thicker border */
}

.alert i {
    margin-right: 14px; /* More spacing */
    font-size: 22px; /* Larger icon */
}

.alert ul {
    margin-bottom: 0;
    padding-left: 30px; /* More indent */
}

.alert ul li {
    margin-bottom: 8px; /* More spacing between items */
    font-size: 16px; /* Larger text */
}

.alert ul li:last-child {
    margin-bottom: 0;
}

/* Buttons - Matching Departments */
.btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 18px 32px; /* Larger buttons */
    font-size: 18px; /* Larger text */
    transition: all 0.3s ease;
    border: none;
    height: 60px; /* Taller buttons */
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    box-shadow: 0 6px 25px rgba(46, 58, 135, 0.3); /* Stronger shadow */
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(46, 58, 135, 0.4); /* More pronounced hover */
    background: linear-gradient(135deg, #26327A 0%, #3D4DC7 100%);
}

.btn-primary:active {
    transform: translateY(-1px);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    box-shadow: 0 6px 25px rgba(108, 117, 125, 0.3); /* Stronger shadow */
}

.btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(108, 117, 125, 0.4); /* More pronounced hover */
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
}

/* Grid Layout for Form - Adjusted for wider layout */
.row {
    margin-left: -15px; /* More spacing between columns */
    margin-right: -15px;
}

.row > .form-group {
    padding-left: 15px;
    padding-right: 15px;
}

/* Invalid Feedback */
.invalid-feedback {
    font-size: 15px; /* Slightly larger */
    color: #dc3545;
    margin-top: 10px; /* More spacing */
    display: flex;
    align-items: center;
    gap: 10px; /* More spacing */
}

.invalid-feedback i {
    font-size: 18px; /* Larger icon */
}

/* Loading Animation */
.btn-loading .spinner-border {
    width: 1.4rem; /* Larger spinner */
    height: 1.4rem;
    border-width: 0.3em; /* Thicker border */
}

/* Registration Link */
.login-link {
    text-align: center;
    margin-top: 35px; /* More spacing */
    padding-top: 30px; /* More padding */
    border-top: 2px solid #e9ecef;
    color: #6c757d;
    font-size: 17px; /* Larger text */
}

.login-link p {
    margin-bottom: 0;
}

.login-link a {
    color: #2E3A87;
    text-decoration: none;
    font-weight: 700;
    font-size: 17px; /* Larger text */
    transition: color 0.2s ease;
    margin-left: 8px; /* More spacing */
}

.login-link a:hover {
    color: #4A5BD9;
    text-decoration: underline;
    text-decoration-thickness: 2px; /* Thicker underline */
}

/* Animation for form */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.registration-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Logo/Symbol - Larger */
.registration-symbol {
    width: 110px; /* Larger symbol */
    height: 110px;
    background: linear-gradient(135deg, #2E3A87 0%, #4A5BD9 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 44px; /* Larger icon */
    margin: 0 auto 25px; /* More spacing below */
    box-shadow: 0 8px 25px rgba(46, 58, 135, 0.5); /* Stronger shadow */
}

/* Disabled Input Styling */
.form-control:disabled {
    background-color: #f8f9fa;
    color: #6c757d;
    border-color: #dee2e6;
    cursor: not-allowed;
}

/* Password Strength Indicator */
.password-strength {
    margin-top: 12px; /* More spacing */
    font-size: 14px; /* Larger text */
    display: flex;
    align-items: center;
    gap: 8px; /* More spacing */
}

.strength-bar {
    flex: 1;
    height: 6px; /* Thicker bar */
    background: #e9ecef;
    border-radius: 3px; /* More rounded */
    overflow: hidden;
}

.strength-bar-fill {
    height: 100%;
    transition: all 0.3s ease;
}

.strength-weak .strength-bar-fill {
    background: #dc3545;
    width: 25%;
}

.strength-medium .strength-bar-fill {
    background: #ffc107;
    width: 50%;
}

.strength-strong .strength-bar-fill {
    background: #28a745;
    width: 75%;
}

.strength-very-strong .strength-bar-fill {
    background: #20c997;
    width: 100%;
}

/* Password Requirements */
.password-requirements {
    background: #f8f9fa;
    border-radius: 10px; /* More rounded */
    padding: 20px; /* More padding */
    margin-top: 12px; /* More spacing */
    font-size: 14px; /* Larger text */
    color: #6c757d;
    border: 1px solid #e9ecef;
}

.password-requirements ul {
    margin-bottom: 0;
    padding-left: 25px; /* More indent */
}

.password-requirements li {
    margin-bottom: 6px; /* More spacing */
    font-size: 14px; /* Larger text */
}

.password-requirements li.requirement-met {
    color: #28a745;
}

.password-requirements li.requirement-not-met {
    color: #dc3545;
}

/* Gender Select Custom Styling */
.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%232E3A87' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 22px center; /* Adjusted for wider padding */
    background-size: 18px 14px; /* Larger arrow */
    padding-right: 50px; /* More space for arrow */
}

/* Make select dropdown placeholder more visible */
select:invalid {
    color: #95a5a6;
    font-size: 17px; /* Larger placeholder */
}

select option {
    color: #333;
    font-size: 16px; /* Larger options */
}

select option[value=""][disabled] {
    color: #95a5a6;
}

/* Ensure all placeholders are clearly visible */
::placeholder {
    opacity: 0.9 !important;
    font-size: 16px; /* Larger placeholder text */
    color: #6c757d; /* More visible color */
}

/* Wider spacing for better readability */
.form-section:last-of-type {
    margin-bottom: 0;
}

/* File upload info text */
.text-muted {
    font-size: 15px; /* Larger info text */
    margin-top: 8px !important;
}

/* Responsive Adjustments - Adjusted for wider base */
@media (max-width: 992px) {
    .registration-card {
        max-width: 95%; /* Still wide on medium screens */
    }
}

@media (max-width: 768px) {
    .registration-container {
        padding: 20px 15px;
    }
    
    .registration-card {
        max-width: 100%;
    }
    
    .registration-header {
        padding: 35px 30px; /* Adjusted for mobile but still generous */
    }
    
    .registration-body {
        padding: 35px 30px; /* Adjusted for mobile but still generous */
    }
    
    .registration-header h2 {
        font-size: 30px; /* Still readable on mobile */
    }
    
    .registration-header p {
        font-size: 16px;
    }
    
    .btn {
        padding: 16px 28px;
        font-size: 17px;
        height: 56px;
    }
    
    .form-control, .form-select {
        padding: 14px 18px;
        font-size: 16px;
        height: 54px;
    }
}

@media (max-width: 576px) {
    .registration-header {
        padding: 30px 25px;
    }
    
    .registration-body {
        padding: 30px 25px;
    }
    
    .registration-header h2 {
        font-size: 26px;
    }
    
    .btn {
        padding: 15px 24px;
        font-size: 16px;
        height: 54px;
    }
    
    .form-control, .form-select {
        padding: 13px 16px;
        font-size: 15px;
        height: 52px;
    }
    
    .alert {
        padding: 20px 24px;
    }
    
    .row {
        margin-left: -10px;
        margin-right: -10px;
    }
    
    .row > .form-group {
        padding-left: 10px;
        padding-right: 10px;
    }
}

/* Form Section Headers for better grouping */
.form-section-header {
    font-size: 20px;
    color: #2E3A87;
    margin-bottom: 25px;
    padding-bottom: 12px;
    border-bottom: 2px solid #e9ecef;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}

.form-section-header i {
    color: #4A5BD9;
}

/* Helper text styling */
.form-text {
    font-size: 14px;
    margin-top: 6px;
}

/* Grid adjustments for wider layout */
.col-md-6 {
    width: 50%;
}

/* Container for wider layout */
.container-wide {
    max-width: 100%;
    padding-left: 20px;
    padding-right: 20px;
}
</style>
@endsection

@section('content')
<div class="registration-container">
    <div class="registration-card">
        <!-- Header -->
        <div class="registration-header">
            <div class="registration-symbol">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Employee Registration</h2>
            <p>Create your account to get started with our employee management system</p>
        </div>

        <!-- Body -->
        <div class="registration-body">
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
                    <strong style="color: #dc3545;">Registration Failed</strong>
                    <ul class="mt-3 mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
                @csrf

                <div class="row">
                    <!-- Employee Number -->
                    <div class="col-md-6 form-group">
                        <label for="EmployeeNumber" class="form-label">
                            <i class="fas fa-id-badge"></i>
                            Employee Number
                        </label>
                        <input id="EmployeeNumber" type="text" name="EmployeeNumber" 
                               class="form-control @error('EmployeeNumber') is-invalid @enderror" 
                               value="{{ old('EmployeeNumber') }}" 
                               placeholder="e.g., EMP001" 
                               required>
                        @error('EmployeeNumber')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            Full Name
                        </label>
                        <input id="name" type="text" name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="Enter your full name" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Gender -->
                    <div class="col-md-6 form-group">
                        <label for="gender" class="form-label">
                            <i class="fas fa-venus-mars"></i>
                            Gender
                        </label>
                        <select id="gender" name="gender" 
                                class="form-select @error('gender') is-invalid @enderror" 
                                required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input id="email" type="email" name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="example@company.com" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Profile Photo Upload -->
                <div class="form-group">
                    <label for="profile_photo" class="form-label">
                        <i class="fas fa-camera"></i>
                        Profile Photo (Optional)
                    </label>
                    <input id="profile_photo" type="file" name="profile_photo" 
                           class="form-control @error('profile_photo') is-invalid @enderror"
                           accept="image/*">
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        Supported formats: JPG, PNG, GIF. Max size: 2MB
                    </small>
                    @error('profile_photo')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Role (Default to Employee) -->
                <div class="form-group">
                    <label for="role_display" class="form-label">
                        <i class="fas fa-user-tag"></i>
                        Role
                    </label>
                    <input id="role_display" type="text" class="form-control" value="Employee" disabled>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        All registered users are assigned the Employee role by default
                    </small>
                    <input type="hidden" name="role_id" value="2"> <!-- Employees are registered with role_id=2 -->
                </div>

                <div class="row">
                    <!-- Password -->
                    <div class="col-md-6 form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <input id="password" type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Create a strong password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                        <div id="passwordStrength" class="password-strength">
                            <span class="strength-label"></span>
                            <div class="strength-bar">
                                <div class="strength-bar-fill"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i>
                            Confirm Password
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" 
                               class="form-control" 
                               placeholder="Re-enter your password" 
                               required>
                        <div id="passwordMatch" class="mt-2" style="font-size: 14px;">
                            <!-- Password match indicator will be shown here -->
                        </div>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="password-requirements mb-4">
                    <div class="fw-semibold mb-2" style="color: #2E3A87;">
                        <i class="fas fa-shield-alt me-1"></i>Password Requirements:
                    </div>
                    <ul>
                        <li id="reqLength" class="requirement-not-met">At least 8 characters</li>
                        <li id="reqUppercase" class="requirement-not-met">At least one uppercase letter</li>
                        <li id="reqLowercase" class="requirement-not-met">At least one lowercase letter</li>
                        <li id="reqNumber" class="requirement-not-met">At least one number</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="registerButton">
                        <span class="submit-text">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');
    const registerButton = document.getElementById('registerButton');
    
    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        const strengthBar = document.querySelector('.strength-bar-fill');
        const strengthLabel = document.querySelector('.strength-label');
        const strengthContainer = document.getElementById('passwordStrength');
        
        // Reset classes
        strengthContainer.className = 'password-strength';
        
        // Check length
        if (password.length >= 8) {
            strength++;
            document.getElementById('reqLength').className = 'requirement-met';
        } else {
            document.getElementById('reqLength').className = 'requirement-not-met';
        }
        
        // Check uppercase
        if (/[A-Z]/.test(password)) {
            strength++;
            document.getElementById('reqUppercase').className = 'requirement-met';
        } else {
            document.getElementById('reqUppercase').className = 'requirement-not-met';
        }
        
        // Check lowercase
        if (/[a-z]/.test(password)) {
            strength++;
            document.getElementById('reqLowercase').className = 'requirement-met';
        } else {
            document.getElementById('reqLowercase').className = 'requirement-not-met';
        }
        
        // Check numbers
        if (/[0-9]/.test(password)) {
            strength++;
            document.getElementById('reqNumber').className = 'requirement-met';
        } else {
            document.getElementById('reqNumber').className = 'requirement-not-met';
        }
        
        // Update strength display
        if (password.length === 0) {
            strengthLabel.textContent = '';
            strengthBar.style.width = '0%';
            strengthBar.style.backgroundColor = '#e9ecef';
        } else if (strength <= 1) {
            strengthContainer.classList.add('strength-weak');
            strengthLabel.textContent = 'Weak';
        } else if (strength === 2) {
            strengthContainer.classList.add('strength-medium');
            strengthLabel.textContent = 'Medium';
        } else if (strength === 3) {
            strengthContainer.classList.add('strength-strong');
            strengthLabel.textContent = 'Strong';
        } else {
            strengthContainer.classList.add('strength-very-strong');
            strengthLabel.textContent = 'Very Strong';
        }
        
        return strength;
    }
    
    // Password match checker
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword.length === 0) {
            passwordMatch.innerHTML = '';
            passwordMatch.style.color = '';
            return;
        }
        
        if (password === confirmPassword) {
            passwordMatch.innerHTML = '<i class="fas fa-check-circle me-1"></i>Passwords match';
            passwordMatch.style.color = '#28a745';
        } else {
            passwordMatch.innerHTML = '<i class="fas fa-times-circle me-1"></i>Passwords do not match';
            passwordMatch.style.color = '#dc3545';
        }
    }
    
    // Event listeners
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });
    
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    // Form submission
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        const strength = checkPasswordStrength(password);
        
        // Check password strength
        if (strength < 2) {
            e.preventDefault();
            alert('Please create a stronger password. Password should be at least medium strength.');
            passwordInput.focus();
            return false;
        }
        
        // Check password match
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please confirm your password.');
            confirmPasswordInput.focus();
            return false;
        }
        
        // Show loading state
        const submitText = registerButton.querySelector('.submit-text');
        const spinner = registerButton.querySelector('.spinner-border');
        
        if (submitText && spinner) {
            submitText.classList.add('d-none');
            spinner.classList.remove('d-none');
            registerButton.disabled = true;
        }
    });
    
    // File upload preview (optional)
    const fileInput = document.getElementById('profile_photo');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                }
            }
        });
    }
    
    // Auto-focus on first input
    document.getElementById('EmployeeNumber').focus();
});
</script>
@endsection