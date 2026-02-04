<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LeaveHub - Professional Leave Management System">
    <title>@yield('title') | ABC Leave Management</title>
    
    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-blue: #3b82f6;
            --accent-teal: #0d9488;
            --light-blue: #dbeafe;
            --light-teal: #ccfbf1;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--gray-700);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid var(--gray-200);
            padding: 40px;
            width: 80%;
            max-width: 100%;
            margin: 20px auto;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .auth-header p {
            color: var(--gray-600);
            margin-bottom: 0;
        }

        .auth-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            margin: 0 auto 20px;
        }

        /* Form Styling */
        .form-label {
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid var(--gray-300);
            border-radius: 8px;
            padding: 12px 16px;
            transition: var(--transition);
            background: white;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        /* Button Styling */
        .btn {
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-block {
            width: 100%;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
            border-left: 4px solid transparent;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #7f1d1d;
            border-left-color: var(--danger);
        }

        /* Links */
        .auth-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .auth-link:hover {
            color: var(--secondary-blue);
            text-decoration: underline;
        }

        /* Additional Links Section */
        .auth-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }

        .auth-links a {
            color: var(--gray-600);
            text-decoration: none;
            margin: 0 10px;
            font-size: 0.9rem;
        }

        .auth-links a:hover {
            color: var(--primary-blue);
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }
            
            .auth-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .auth-header h1 {
                font-size: 1.5rem;
            }
            
            .auth-logo {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
            color: var(--gray-600);
            font-size: 0.85rem;
        }

        .auth-footer p {
            margin: 5px 0;
        }

        /* Checkbox and Radio */
        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .form-check-input:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="auth-container">
        <!-- Auth Header -->
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h1>ABCMH</h1>
            <p>We treat,God heals</p>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-3"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content -->
        <div class="auth-content">
            @yield('content')
        </div>

        <!-- Additional Links -->
        @yield('auth-links')

        <!-- Footer -->
        <div class="auth-footer">
            <p>&copy; {{ date('Y') }} LeaveHub. All rights reserved.</p>
            <p>Version 1.2.0</p>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert:not(.permanent)');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Add loading state to submit buttons
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
                        submitBtn.disabled = true;
                    }
                });
            });

            // Add floating label effect
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>