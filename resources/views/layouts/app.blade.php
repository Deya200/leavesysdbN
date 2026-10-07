<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta & Title -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Leave Management System</title>
  <link rel="icon" type="image/png" href="{{ asset('logo3.png') }}">

  <!-- Fonts & Styles -->
 @vite(['resources/sass/app.scss', 'resources/js/app.js'])
 
  <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">

  <!-- Custom Styles -->
  <style>
    :root {
      --font-primary: 'Maiandra GD', 'Inter', system-ui, -apple-system, sans-serif;
      --color-bg: #f9fafb;
      --color-surface: #ffffff;
      --color-primary: #3b4c9b;
      --color-primary-light: #5266c2;
      --color-primary-dark: #2d3a7a;
      --color-secondary: #64748b;
      --color-slate-50: #f8fafc;
      --color-slate-100: #f1f5f9;
      --color-slate-200: #e2e8f0;
      --color-slate-300: #cbd5e1;
      --color-slate-400: #94a3b8;
      --color-slate-500: #64748b;
      --color-slate-600: #475569;
      --color-slate-700: #334155;
      --color-slate-800: #1e293b;
      --color-slate-900: #111827;

      --color-text-main: #111827;
      --color-text-muted: #6b7280;

      --sidebar-width: 260px;
      --header-height: 64px;
      --footer-height: 64px;

      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-indigo: 0 4px 6px -1px rgba(59, 130, 246, 0.2);

      --radius-md: 0.375rem;
      --radius-lg: 0.5rem;
      --radius-xl: 0.75rem;
      --radius-2xl: 1rem;

      --glass-bg: rgba(255, 255, 255, 0.9);
      --glass-border: rgba(255, 255, 255, 0.5);
      --glass-blur: blur(12px);
    }

    body {
      font-family: var(--font-primary);
      background-color: var(--color-bg);
      color: var(--color-text-main);
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    /* Global Transition */
    .transition-all {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
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
        height: 100vh !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1050 !important;
        transform: none !important;
        visibility: visible !important;
        border-right: 1px solid rgba(0, 0, 0, 0.05);
      }

      header {
        margin-left: var(--sidebar-width);
        width: calc(100% - var(--sidebar-width));
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
      border: 1px solid var(--color-slate-200);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      margin-bottom: 2rem;
      transition: all 0.2s ease;
    }

    .card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }

    .card-glass {
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      border: 1px solid var(--glass-border);
    }

    .card-header {
      background-color: transparent;
      border-bottom: 1px solid var(--color-slate-100);
      padding: 1.25rem 1.5rem;
      font-weight: 600;
      color: var(--color-slate-900);
    }

    /* Modern Buttons */
    .btn {
      border-radius: var(--radius-md);
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: all 0.2s;
    }

    .btn-primary {
      background-color: var(--color-primary);
      border-color: var(--color-primary);
      box-shadow: var(--shadow-indigo);
    }

    .btn-primary:hover {
      background-color: var(--color-primary-dark);
      border-color: var(--color-primary-dark);
      transform: translateY(-1px);
    }


    /* Utility Classes */
    .btn-icon-glass {
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--radius-md);
      border: 1px solid var(--color-slate-200);
      background: white;
      color: var(--color-slate-600);
      transition: all 0.2s;
    }

    .btn-icon-glass:hover {
      background: var(--color-slate-50);
      color: var(--color-primary);
      border-color: var(--color-slate-300);
    }

    .hover-bg-slate-100:hover {
      background-color: var(--color-slate-100) !important;
    }

    .text-slate-400 {
      color: var(--color-slate-400) !important;
    }

    .text-slate-500 {
      color: var(--color-slate-500) !important;
    }

    .text-slate-600 {
      color: var(--color-slate-600) !important;
    }

    .text-slate-800 {
      color: var(--color-slate-800) !important;
    }

    /* Worksy Dashboard Components */
    .dashboard-container {
      width: 100%;
      padding-left: 0.5rem;
    }

    .profile-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding: 1rem 0;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: var(--shadow-md);
    }

    .profile-details h2 {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--color-slate-900);
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }

    .profile-meta {
      display: flex;
      gap: 2rem;
    }

    .profile-meta span {
      display: flex;
      flex-direction: column;
    }

    .profile-meta strong {
      font-size: 0.75rem;
      color: var(--color-slate-400);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .profile-meta b {
      font-size: 0.9375rem;
      color: var(--color-slate-700);
      font-weight: 600;
    }

    /* Tabs */
    .dashboard-tabs {
      display: flex;
      gap: 2rem;
      border-bottom: 1px solid var(--color-slate-200);
      margin-bottom: 2.5rem;
    }

    .tab-item {
      padding: 0.75rem 0;
      color: var(--color-slate-500);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9375rem;
      position: relative;
      transition: all 0.2s;
    }

    .tab-item:hover {
      color: var(--color-primary);
    }

    .tab-item.active {
      color: var(--color-primary);
    }

    .tab-item.active::after {
      content: '';
      position: absolute;
      bottom: -1px;
      left: 0;
      width: 100%;
      height: 2px;
      background: var(--color-primary);
    }

    /* Metrics Grid */
    .metric-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .metric-card {
      background: white;
      padding: 1.5rem;
      border-radius: var(--radius-xl);
      display: flex;
      align-items: center;
      gap: 1.25rem;
      box-shadow: var(--shadow-sm);
      transition: transform 0.2s;
    }

    .metric-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-md);
    }

    .metric-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }

    .available .metric-icon {
      background: rgba(52, 211, 153, 0.1);
      color: #059669;
    }

    .pending .metric-icon {
      background: rgba(251, 191, 36, 0.1);
      color: #d97706;
    }

    .approved .metric-icon {
      background: rgba(99, 102, 241, 0.1);
      color: #4f46e5;
    }

    .rejected .metric-icon {
      background: rgba(248, 113, 113, 0.1);
      color: #dc2626;
    }

    .metric-content h3 {
      font-size: 1.0625rem;
      font-weight: 700;
      color: var(--color-slate-800);
      margin-bottom: 0.25rem;
    }

    .metric-content p {
      font-size: 0.8125rem;
      color: var(--color-slate-400);
      margin-bottom: 0;
    }

    /* Filter Bar */
    .filter-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      gap: 1rem;
    }

    .search-input-wrapper {
      position: relative;
      flex-grow: 1;
      max-width: 300px;
    }

    .search-input-wrapper i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--color-slate-400);
    }

    .search-input-wrapper input {
      padding-left: 2.75rem;
      border-radius: var(--radius-lg);
      font-size: 0.875rem;
      height: 42px;
    }

    .filter-chips {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .filter-chip {
      padding: 0.375rem 0.75rem;
      background: white;
      border: 1px solid var(--color-slate-200);
      border-radius: 20px;
      font-size: 0.8125rem;
      font-weight: 600;
      color: var(--color-slate-600);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
    }

    .filter-chip i {
      font-size: 0.75rem;
      color: var(--color-slate-400);
    }

    /* Modern Table */
    .modern-table-card {
      background: white;
      border-radius: var(--radius-xl);
      overflow: hidden;
    }

    .table-modern {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    .table-modern th {
      background: var(--color-slate-50);
      padding: 1rem 1.5rem;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--color-slate-500);
      border-bottom: 1px solid var(--color-slate-100);
    }

    .table-modern td {
      padding: 1.25rem 1.5rem;
      font-size: 0.875rem;
      color: var(--color-slate-600);
      border-bottom: 1px solid var(--color-slate-50);
      vertical-align: middle;
    }

    .table-modern tr:last-child td {
      border-bottom: none;
    }

    .hover-up:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
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
  <!-- Scripts are handled in the head -->

  <script>
    // Dark Mode Configuration
    const DARK_CLASS = 'dark-mode';
    const STORAGE_KEY = 'darkMode';

    // Helper function to update icon
    function updateThemeIcon(isDark) {
      const btn = document.getElementById('darkModeToggle');
      if (!btn) return;
      
      const icon = btn.querySelector('i');
      if (icon) {
        icon.classList.remove('fa-moon', 'fa-sun');
        icon.classList.add(isDark ? 'fa-sun' : 'fa-moon');
      }

      btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
    }

    // Helper function to apply theme
    function applyTheme(isDark) {
      if (isDark) {
        document.body.classList.add(DARK_CLASS);
      } else {
        document.body.classList.remove(DARK_CLASS);
      }
      localStorage.setItem(STORAGE_KEY, isDark ? 'on' : 'off');
      updateThemeIcon(isDark);
    }

    // Global toggleTheme function for inline onclick usage
    window.toggleTheme = function () {
      const isDarkNow = document.body.classList.contains(DARK_CLASS);
      applyTheme(!isDarkNow);
    };

    document.addEventListener('DOMContentLoaded', function () {
      // Initialize tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
      })

      // Initialize dark mode from localStorage
      const btn = document.getElementById('darkModeToggle');

      if (btn) {
        const persisted = localStorage.getItem(STORAGE_KEY);
        const isDark = persisted === 'on';

        // Apply saved preference
        applyTheme(isDark);

        // Add click event listener
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          const isDarkNow = document.body.classList.contains(DARK_CLASS);
          applyTheme(!isDarkNow);
        });
        if (window.innerWidth >= 768) {
          mainEl.style.transition = 'margin-left 200ms ease, width 200ms ease';
          mainEl.style.marginLeft = sidebarWidth;
          mainEl.style.width = 'calc(100% - ' + sidebarWidth + ')';
        }
      }

      function removeSidebarPush() {
        mainEl.style.marginLeft = '';
        mainEl.style.width = '';
      }

      sidebarEl.addEventListener('show.bs.offcanvas', function () {
        applySidebarPush();
      });
      sidebarEl.addEventListener('hidden.bs.offcanvas', function () {
        removeSidebarPush();
      });

      // If user resizes while open/closed, reset
      window.addEventListener('resize', function () {
        if (window.innerWidth < 768) {
          removeSidebarPush();
        }
      });
    });
  </script>

  @include('layouts.profile-offcanvas')

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
  </form>

  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
  <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
  <script>
    function confirmLogout() {
      Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of the system!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('logout-form').submit();
        }
      })
    }

    document.addEventListener('DOMContentLoaded', function () {
      // auto-dismiss any Bootstrap alert message
      document.querySelectorAll('.alert-dismissible').forEach((alertElem) => {
        setTimeout(() => {
          const bsAlert = bootstrap.Alert.getOrCreateInstance(alertElem);
          bsAlert.close();
        }, 4300);
      });

      // Toast-style notification (popup for a few seconds)
      function showToast(type, message) {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: type,
          title: message,
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true,
          background: 'rgba(32, 34, 40, 0.95)',
          color: '#fff',
          customClass: {
            popup: 'border border-1 border-light'
          }
        });
      }

      @if(session('success'))
        showToast('success', '{!! addslashes(session('success')) !!}');
      @elseif(session('error'))
        showToast('error', '{!! addslashes(session('error')) !!}');
      @endif
    });
  </script>

  @yield('scripts')
</body>

</html>