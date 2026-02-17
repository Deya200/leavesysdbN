<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta & Title -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Leave Management System</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    :root {
      --font-primary: 'Inter', sans-serif;
      --color-bg: #f8f9fa; /* Softer light gray */
      --color-surface: #ffffff;
      --color-primary: #4f46e5; 
      --color-primary-dark: #4338ca;
      --color-secondary: #64748b;
      --color-text-main: #1e293b; /* Darker slate */
      --color-text-muted: #64748b;
      --sidebar-width: 280px;
      --header-height: 60px;
      
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      
      --radius-md: 0.75rem;
      --radius-lg: 1rem;
    }

    body {
      font-family: var(--font-primary);
      background-color: var(--color-bg);
      color: var(--color-text-main);
      -webkit-font-smoothing: antialiased;
    }
    
    /* Layout Logic */
    @media (min-width: 992px) {
        /* Force Sidebar Visible & Fixed */
        #mainSidebar {
            transform: none !important;
            visibility: visible !important;
            top: var(--header-height) !important;
            height: calc(100vh - var(--header-height)) !important;
            box-shadow: none !important; /* Remove offcanvas shadow */
        }
        
        /* Shift Main Content */
        main {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 2rem !important; /* Proper padding for desktop */
        }
        
        /* Hide backdrop if it appears */
        .offcanvas-backdrop {
            display: none !important;
        }
    }
    
    @media (max-width: 991.98px) {
        main {
            margin-left: 0;
            width: 100%;
            padding: 1rem;
        }
    }

    /* Beautification - Cards */
    .card {
      background: var(--color-surface);
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      margin-bottom: 1.5rem;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }
    
    .card-header {
      background-color: #fff;
      border-bottom: 1px solid #f1f5f9;
      padding: 1.25rem 1.5rem;
      font-weight: 700;
      color: var(--color-text-main);
      border-top-left-radius: var(--radius-lg) !important;
      border-top-right-radius: var(--radius-lg) !important;
    }

    /* Beautification - Buttons */
    .btn {
      border-radius: var(--radius-md);
      padding: 0.6rem 1.2rem;
      font-weight: 500;
      letter-spacing: 0.01em;
      transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }
    
    .btn-primary:hover {
        background-color: var(--color-primary-dark);
        border-color: var(--color-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(79, 70, 229, 0.3);
    }

    /* Beautification - Alerts */
    .alert {
      border: none;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-sm);
    }
  </style>

  @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">
  @include('layouts.header')
  <div class="d-flex flex-grow-1">
      @include('layouts.sidebar')
      
      <main class="flex-grow-1" style="margin-top: 0;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-3 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-3 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
      </main>
  </div>

  <footer class="text-center mt-auto py-3 bg-white border-top">
    <div class="container">
        <p class="mb-0 text-muted small">&copy; {{ date('Y') }} Leave Management System. All rights reserved.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
      })

      // Dark Mode Logic
      const btn = document.getElementById('darkModeToggle');
      if(btn) {
          const icon = btn.querySelector('i');
          const darkClass = 'dark-mode';
    
          if (localStorage.getItem('darkMode') === 'on') {
            document.body.classList.add(darkClass);
            if(icon) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
          }
    
          btn.addEventListener('click', function() {
            document.body.classList.toggle(darkClass);
            const isDark = document.body.classList.contains(darkClass);
            if(icon) {
                icon.classList.toggle('fa-moon', !isDark);
                icon.classList.toggle('fa-sun', isDark);
            }
            localStorage.setItem('darkMode', isDark ? 'on' : 'off');
          });
      }
    });
  </script>

  @yield('scripts')
</body>
</html>
