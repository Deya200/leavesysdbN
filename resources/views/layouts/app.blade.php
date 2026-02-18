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
      --color-bg: #f8fafc;
      --color-surface: #ffffff;
      --color-primary: #4f46e5; 
      --color-primary-dark: #3730a3;
      --color-secondary: #64748b;
      --color-text-main: #0f172a;
      --color-text-muted: #64748b;
      --sidebar-width: 280px;
      --header-height: 70px;
      
      --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      
      --radius-md: 0.5rem;
      --radius-lg: 0.75rem;
      --radius-xl: 1rem;
    }

    body {
      font-family: var(--font-primary);
      background-color: var(--color-bg);
      color: var(--color-text-main);
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }
    
    /* Layout Logic */
    .wrapper {
        display: flex;
        width: 100%;
        min-height: calc(100vh - var(--header-height));
    }

    @media (min-width: 992px) {
        #mainSidebar {
            width: var(--sidebar-width) !important;
            height: calc(100vh - var(--header-height)) !important;
            position: fixed !important;
            top: var(--header-height) !important;
            left: 0 !important;
            z-index: 1000 !important;
            transform: none !important;
            visibility: visible !important;
            border-right: 1px solid rgba(0,0,0,0.05);
            background: #1e293b; /* Dark sidebar */
        }
        
        main {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 2.5rem !important;
            min-height: calc(100vh - var(--header-height));
        }
        
        .offcanvas-backdrop {
            display: none !important;
        }
    }
    
    @media (max-width: 991.98px) {
        main {
            margin-left: 0;
            width: 100%;
            padding: 1.5rem;
        }
    }

    /* Modern Card Styles */
    .card {
      background: var(--color-surface);
      border: 1px solid rgba(0, 0, 0, 0.05);
      border-radius: var(--radius-xl);
      box-shadow: var(--shadow-sm);
      margin-bottom: 2rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-4px);
    }
    
    .card-header {
      background-color: transparent;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      font-weight: 700;
      color: var(--color-text-main);
    }

    /* Modern Buttons */
    .btn {
      border-radius: var(--radius-lg);
      padding: 0.625rem 1.25rem;
      font-weight: 600;
      transition: all 0.2s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
        border: none;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
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
  
  <div class="wrapper">
      @include('layouts.sidebar')
      
      <main class="flex-grow-1">
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
