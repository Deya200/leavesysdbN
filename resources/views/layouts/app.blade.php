<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta & Title -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Leave Management System</title>

  <!-- Fonts & Styles -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

  <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">

  <!-- Custom Styles -->
  <style>
    :root {
      --font-primary: 'Inter', system-ui, -apple-system, sans-serif;
      --color-bg: #fdfdfd;
      --color-surface: #ffffff;
      --color-primary: #6366f1;
      /* Worksy Indigo */
      --color-primary-light: #818cf8;
      --color-primary-dark: #4f46e5;
      --color-slate-50: #f8fafc;
      --color-slate-100: #f1f5f9;
      --color-slate-200: #e2e8f0;
      --color-slate-300: #cbd5e1;
      --color-slate-400: #94a3b8;
      --color-slate-500: #64748b;
      --color-slate-600: #475569;
      --color-slate-700: #334155;
      --color-slate-800: #1e293b;
      --color-slate-900: #0f172a;

      --color-text-main: #1a1a1a;
      --color-text-muted: #666666;

      --sidebar-width: 260px;
      --header-height: 64px;

      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 2px 8px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.08);
      --shadow-indigo: 0 8px 16px -4px rgba(99, 102, 241, 0.2);

      --radius-md: 0.5rem;
      --radius-lg: 0.75rem;
      --radius-xl: 1rem;
      --radius-2xl: 1.25rem;
      --footer-height: 72px;

      --glass-bg: rgba(255, 255, 255, 0.8);
      --glass-border: rgba(255, 255, 255, 0.4);
      --glass-blur: blur(8px);
    }

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    body {
      font-family: var(--font-primary);
      background-color: var(--color-bg);
      color: var(--color-text-main);
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    /* Global Transition */
    .transition-all {
        transition: all 0.2s ease;
    }

    .cursor-pointer {
        cursor: pointer;
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
      transform: translateY(-2px);
    }

    .card-glass {
      background: var(--glass-bg);
      backdrop-filter: var(--glass-blur);
      border: 1px solid var(--glass-border);
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
      box-shadow: var(--shadow-indigo);
      padding: 0.75rem 1.5rem;
      font-size: 0.875rem;
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 12px 20px -5px rgba(79, 70, 229, 0.3);
      filter: brightness(1.1);
    }


    /* Utility Classes */
    .btn-icon-glass {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      border: 1px solid var(--color-slate-200);
      background: transparent;
      color: var(--color-slate-600);
      transition: all 0.2s;
    }

    .btn-icon-glass:hover {
      background: var(--color-slate-100);
      color: var(--color-primary);
      border-color: var(--color-primary-light);
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
      background-color: var(--color-slate-50);
    }

    .transition-all {
      transition: all 0.2s ease;
    }

    .cursor-pointer {
      cursor: pointer;
    }
  </style>

  @yield('styles')
</head>

<body class="d-flex flex-column min-vh-100">

  <div class="wrapper d-flex">
    @include('layouts.sidebar')

    <div class="main-container flex-grow-1 d-flex flex-column">
      @include('layouts.header')

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

      <footer class="text-center mt-auto bg-white border-top d-flex align-items-center justify-content-center" style="height: var(--footer-height); min-height: var(--footer-height);">
        <div class="container">
          <p class="mb-0 text-muted small text-center">&copy; {{ date('Y') }} ABC leave Management system. All rights reserved.</p>
        </div>
      </footer>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
      })

      // Dark Mode Logic
      const btn = document.getElementById('darkModeToggle');
      if (btn) {
        const icon = btn.querySelector('i');
        const darkClass = 'dark-mode';

        if (localStorage.getItem('darkMode') === 'on') {
          document.body.classList.add(darkClass);
          if (icon) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
          }
        }

        btn.addEventListener('click', function () {
          document.body.classList.toggle(darkClass);
          const isDark = document.body.classList.contains(darkClass);
          if (icon) {
            icon.classList.toggle('fa-moon', !isDark);
            icon.classList.toggle('fa-sun', isDark);
          }
          localStorage.setItem('darkMode', isDark ? 'on' : 'off');
        });
      }
    });
  </script>

  <style>
    /* Final Layout Source of Truth */
    .wrapper {
      display: flex;
      width: 100%;
      min-height: 100vh;
    }

    .main-container {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex: 1;
      padding: 0.75rem !important;
    }

    @media (min-width: 992px) {
      #mainSidebar {
        width: var(--sidebar-width) !important;
        height: 100vh !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1050 !important;
        visibility: visible !important;
        transform: none !important;
        border-right: 1px solid var(--color-slate-200);
      }

      .main-container {
        margin-left: var(--sidebar-width);
      }

      header {
        position: sticky;
        top: 0;
        z-index: 1040;
        background: white !important;
        border-bottom: 1px solid var(--color-slate-200) !important;
      }

      .offcanvas-backdrop {
        display: none !important;
      }
    }

    @media (max-width: 991.98px) {
      .main-container {
        margin-left: 0;
        width: 100%;
      }
      
      main {
        padding: 1rem !important;
      }

      #mainSidebar {
        z-index: 1060 !important;
      }
    }
  </style>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>

  <script>
    function confirmLogout() {
      if (confirm('Are you sure you want to log out?')) {
        document.getElementById('logout-form').submit();
      }
    }
  </script>

  @yield('scripts')
</body>

</html>