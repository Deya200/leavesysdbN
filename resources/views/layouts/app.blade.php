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
      --color-bg: #f3f4f6;
      --color-surface: #ffffff;
      --color-primary: #4f46e5; /* Indigo 600 */
      --color-primary-dark: #4338ca;
      --color-secondary: #64748b;
      --color-text-main: #111827;
      --color-text-muted: #6b7280;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --radius-md: 0.5rem;
      --radius-lg: 0.75rem;
    }

    body {
      font-family: var(--font-primary);
      background-color: var(--color-bg);
      color: var(--color-text-main);
      -webkit-font-smoothing: antialiased;
    }

    /* Transitions */
    body, .card, .btn, .form-control {
      transition: all 0.2s ease-in-out;
    }

    h1, h2, h3, h4, h5, h6 {
      font-weight: 600;
      color: var(--color-text-main);
    }

    /* Card Styling */
    .card {
      background: var(--color-surface);
      border: none;
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      margin-bottom: 1.5rem;
    }

    .card:hover {
      box-shadow: var(--shadow-md);
    }

    .card-header {
      background-color: transparent;
      border-bottom: 1px solid #e5e7eb;
      padding: 1.25rem 1.5rem;
      font-weight: 600;
      color: var(--color-text-main);
    }

    .card-body {
      padding: 1.5rem;
    }

    /* Button Styling */
    .btn {
      border-radius: var(--radius-md);
      padding: 0.5rem 1rem;
      font-weight: 500;
    }

    .btn-primary {
      background-color: var(--color-primary);
      border-color: var(--color-primary);
    }

    .btn-primary:hover {
      background-color: var(--color-primary-dark);
      border-color: var(--color-primary-dark);
    }

    /* Table Styling */
    .table {
      margin-bottom: 0;
      vertical-align: middle;
    }
    
    .table thead th {
      background-color: #f9fafb;
      color: var(--color-text-muted);
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
      border-bottom: 1px solid #e5e7eb;
      padding: 0.75rem 1.5rem;
    }

    .table tbody td {
      padding: 1rem 1.5rem;
      color: var(--color-text-main);
      border-bottom: 1px solid #f3f4f6;
    }

    .table-hover tbody tr:hover {
      background-color: #f9fafb;
    }

    /* Badge Styling */
    .badge {
      padding: 0.35em 0.65em;
      border-radius: 9999px;
      font-weight: 500;
      font-size: 0.75em;
    }

    /* Footer */
    footer {
      background-color: white;
      color: var(--color-text-muted);
      padding: 1.5rem 0;
      margin-top: auto;
      border-top: 1px solid #e5e7eb;
      font-size: 0.875rem;
    }

    /* Alerts */
    .alert {
      border: none;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-md);
    }
  </style>

  @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100">
  @include('layouts.header')
  <div class="d-flex flex-grow-1">
      @include('layouts.sidebar')
      
      <main class="flex-grow-1 px-3 py-4" style="margin-left: 0px; transition: margin-left 0.3s ease;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
      </main>
  </div>

  <footer class="text-center mt-auto">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} Leave Management System. All rights reserved.</p>
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

      // Sidebar & Main Content Logic
      const sidebar = document.getElementById('mainSidebar');
      const mainContent = document.querySelector('main');
      const sidebarWidth = 280; // Must match the width in sidebar.blade.php

      if (sidebar && mainContent) {
        const bsOffcanvas = new bootstrap.Offcanvas(sidebar);

        // Function to adjust margin
        function adjustLayout(isOpen) {
            if (window.innerWidth >= 992) { // Desktop breakpoint (lg)
                // Add extra 20px for gap
                mainContent.style.marginLeft = isOpen ? (sidebarWidth + 20) + 'px' : '0px';
            } else {
                mainContent.style.marginLeft = '0px'; // Always 0 on mobile
            }
        }

        // On show/hide events
        sidebar.addEventListener('show.bs.offcanvas', () => adjustLayout(true));
        sidebar.addEventListener('hide.bs.offcanvas', () => adjustLayout(false));

        // Initial State for Desktop
        if (window.innerWidth >= 992) {
            bsOffcanvas.show(); // Auto-show on desktop
             // adjustLayout(true) will be triggered by the show event, 
            // but we can force it immediately to avoid layout shift if needed
             mainContent.style.marginLeft = (sidebarWidth + 20) + 'px';
        }

        // Handle Window Resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 992) {
                 mainContent.style.marginLeft = '0px';
            } else {
                // If sidebar is currently open (check class 'show'), restore margin
                if (sidebar.classList.contains('show')) {
                    mainContent.style.marginLeft = (sidebarWidth + 20) + 'px';
                }
            }
        });

        // Auto-close sidebar on mobile when a link is clicked
        const navLinks = sidebar.querySelectorAll('.nav-link, .dropdown-item');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    bsOffcanvas.hide();
                }
            });
        });
      }
    });

    // Dark Mode Logic (Preserved)
    document.addEventListener('DOMContentLoaded', function() {
      const btn = document.getElementById('darkModeToggle');
      if(btn) {
          const icon = btn.querySelector('i');
          const darkClass = 'dark-mode';
    
          if (localStorage.getItem('darkMode') === 'on') {
            document.body.classList.add(darkClass);
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
          }
    
          btn.addEventListener('click', function() {
            document.body.classList.toggle(darkClass);
            const isDark = document.body.classList.contains(darkClass);
            icon.classList.toggle('fa-moon', !isDark);
            icon.classList.toggle('fa-sun', isDark);
            localStorage.setItem('darkMode', isDark ? 'on' : 'off');
          });
      }
    });
  </script>

  @yield('scripts')
</body>
</html>
