<!DOCTYPE html>
<html lang="en" data-theme="light" class="scroll-smooth">
<head>
  <!-- Meta & Title -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="description" content="LeaveHub - Professional Leave Management System">
  <title>@yield('title', 'Dashboard') | ABCMH</title>
  
  <!-- Bootstrap & Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  
  <!-- Favicon & PWA -->
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <meta name="theme-color" content="#3D519F">
  
  <!-- FIXED LAYOUT CSS -->
  <style>
    /* ===== CLEAN LAYOUT STRUCTURE ===== */
    :root {
        --sidebar-width: 320px;
        --header-height: 70px;
    }
    
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        background: #f8f9fa !important;
    }
    
    body {
        background: #f8f9fa !important;
    }
    
    /* Main Layout Container */
    .layout-container {
        display: flex;
        min-height: 100vh;
        width: 100%;
        position: relative;
    }
    
    /* Main Content Wrapper - Full Width */
    .main-content-wrapper {
        flex: 1;
        width: 100% !important;
        min-width: 100% !important;
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
        transition: margin-left 0.3s ease, width 0.3s ease;
    }
    
    /* Content Area - Full Width */
    .page-content-area {
        flex: 1;
        width: 100% !important;
        padding: 20px;
        background: #f8f9fa;
        transition: padding 0.3s ease;
    }
    
    
    /* Fixed Footer - Full Width */
    .app-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 70px;
        z-index: 1030;
        background: white;
        border-top: 1px solid #e9ecef;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        width: 100% !important;
        transition: left 0.3s ease, width 0.3s ease;
    }
    
    /* Content wrapper */
    .content-wrapper {
        width: 100% !important;
        margin-top: var(--header-height);
        margin-bottom: 70px;
        min-height: calc(100vh - var(--header-height) - 70px);
    }
    
    /* Page content should be full width */
    .page-content {
        width: 100% !important;
    }
    
    /* Container fluid should be full width */
    .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    /* Dashboard content specific */
    .dashboard-content {
        width: 100% !important;
        padding: 20px !important;
        background: #f8f9fa !important;
    }
    
    /* Mobile menu button - FIXED: Only show on mobile */
    .mobile-menu-toggle {
        position: fixed !important;
        top: 15px;
        left: 15px;
        z-index: 1060 !important;
    }
    
    
    
    /* ===== FIXED SIDEBAR PUSH EFFECT ===== */
    /* On desktop (≥992px) - Sidebar pushes content */
    @media (min-width: 992px) {
        /* Fix: Remove body margin-left that was causing issues */
        body.sidebar-open {
            margin-left: 0 !important;
        }
        
        /* Fix: Push main content wrapper when sidebar is open */
        body.sidebar-open .main-content-wrapper {
            margin-left: var(--sidebar-width) !important;
            width: calc(100vw - var(--sidebar-width)) !important;
        }
        
        body.sidebar-open .app-header,
        body.sidebar-open .app-footer {
            left: var(--sidebar-width) !important;
            width: calc(100vw - var(--sidebar-width)) !important;
        }
        
        /* FIXED: Allow inner content to be full width */
        body.sidebar-open .page-content-area,
        body.sidebar-open .dashboard-content {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: visible !important;
        }
        
        body.sidebar-open .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            padding-right: 20px !important;
            padding-left: 20px !important;
            overflow-x: visible !important;
        }
        
        /* FIXED: Ensure content area scrolls properly */
        body.sidebar-open .page-content {
            width: 100% !important;
            overflow-x: auto !important;
        }
        
        /* Fix: Make sidebar push correctly */
        .offcanvas.offcanvas-start {
            transform: translateX(-100%) !important;
            transition: transform 0.3s ease !important;
        }
        
        .offcanvas.offcanvas-start.show {
            transform: translateX(0) !important;
        }
        
        /* Fix: Hide backdrop on desktop */
        .offcanvas-backdrop {
            display: none !important;
            background-color: transparent !important;
        }
        
        /* Fix: Hide mobile toggle on desktop */
        .mobile-menu-toggle {
            display: none !important;
        }
        
        /* Fix: Show desktop toggle on desktop */
        #desktopSidebarToggle {
            display: flex !important;
        }
    }
    
    /* ===== MOBILE OVERLAY EFFECT ===== */
    @media (max-width: 991.98px) {
        .page-content-area {
            padding: 15px;
        }
        
        /* On mobile, sidebar overlays content */
        .offcanvas.offcanvas-start {
            transform: translateX(-100%) !important;
        }
        
        .offcanvas.offcanvas-start.show {
            transform: translateX(0) !important;
        }
        
        /* Make sure backdrop is visible on mobile */
        .offcanvas-backdrop {
            display: block !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        /* Fix: Hide desktop toggle on mobile */
        #desktopSidebarToggle {
            display: none !important;
        }
        
        /* Fix: Show mobile toggle on mobile */
        .mobile-menu-toggle {
            display: block !important;
        }
    }
    
    /* Sidebar specific styles */
    .offcanvas-start {
        width: 320px !important;
        z-index: 1050 !important;
    }
    
    .offcanvas {
        background-color: #fdfdfd !important;
        color: #000 !important;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden !important;
    }
    
    .offcanvas-header {
        border-bottom: 1px solid #e0e0e0 !important;
    }
    
    .offcanvas-header .btn-close {
        opacity: 0.7;
        background-size: 0.8em;
        padding: 0.5rem;
        margin: -0.5rem -0.5rem -0.5rem auto;
    }
    
    .offcanvas-header .btn-close:hover {
        opacity: 1;
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .offcanvas-title {
        color: #000;
        font-weight: 600;
        display: flex;
        align-items: center;
        font-size: 1.1rem;
    }
    
    /* Sidebar link styles */
    .offcanvas .nav-link {
        border-radius: 8px;
        transition: all 0.2s ease;
        margin: 2px 4px;
        color: #333333 !important;
        text-decoration: none !important;
        font-weight: 500;
    }
    
    .offcanvas .nav-link:hover {
        background: rgba(61, 81, 159, 0.1);
        color: #3D519F !important;
        transform: translateX(3px);
    }
    
    .offcanvas .nav-link.active {
        background: rgba(61, 81, 159, 0.15);
        color: #3D519F !important;
        border-left: 3px solid #3D519F;
        font-weight: 600;
    }
    
    .offcanvas .collapse .nav-link {
        padding-left: 20px;
        margin-left: 8px;
        border-left: 2px solid rgba(0, 0, 0, 0.08);
        color: #555555 !important;
        font-size: 0.9rem;
    }
    
    .offcanvas .collapse .nav-link:hover {
        border-left-color: #3D519F;
        background: rgba(61, 81, 159, 0.05);
    }
    
    /* Dropdown menu button styles */
    .offcanvas .btn-outline-primary {
        border: 2px solid #3D519F;
        color: #3D519F;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .offcanvas .btn-outline-primary:hover {
        background: #3D519F;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(61, 81, 159, 0.2);
    }
    
    /* REMOVE ALL SCROLLBARS FROM SIDEBAR */
    .offcanvas-body {
        overflow: hidden !important;
        overflow-y: hidden !important;
        overflow-x: hidden !important;
    }
    
    .offcanvas nav {
        overflow: hidden !important;
    }
    
    /* Force no scroll on the entire sidebar */
    #mainSidebar,
    #mainSidebar * {
        overflow: hidden !important;
    }
    
    /* Backdrop styling */
    .offcanvas-backdrop {
        z-index: 1045 !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .offcanvas-start {
            width: 280px !important;
        }
    }
    
    /* Utility Classes */
    .text-gradient-primary {
        background: linear-gradient(135deg, #3D519F 0%, #6A82E6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
  </style>
  
  @yield('meta')
  @yield('styles')
</head>
<body class="bg-light-subtle">
  <!-- Preloader -->
  <div id="preloader" class="preloader">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <!-- Sidebar as Offcanvas -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="mainSidebar" aria-labelledby="mainSidebarLabel" style="width: 320px; background: #fdfdfd; z-index: 1050;">
    <div class="offcanvas-header border-bottom py-3" style="background: #fdfdfd; border-color: #e0e0e0 !important;">
        <h5 class="offcanvas-title" id="mainSidebarLabel">
            
          Menu
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
   <div class="offcanvas-body p-0 d-flex flex-column" style="height: calc(100% - 60px); max-height: 100vh; overflow: hidden;">
        <nav class="flex-grow-1">
            <ul class="nav flex-column px-2 pt-2">
                <!-- Main Navigation -->
                
               <!-- Main Navigation -->
@if(auth()->user()->role_id === 1)
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('dashboard') }}" style="text-decoration: none;">
        <i class="fas fa-home"></i> Home
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('admin.verification') }}" style="text-decoration: none;">
        <i class="fas fa-shield-alt"></i> Admin Verification
    </a>
</li>

<!-- User Management Button (Added Here) -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('admin.users.index') }}" style="text-decoration: none;">
        <i class="fas fa-users-cog"></i> User Management
    </a>
</li>

<!-- Employees Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarEmployees" role="button" aria-expanded="false" aria-controls="sidebarEmployees" style="text-decoration: none;">
        <i class="fas fa-users"></i> Employees
    </a>
    <div class="collapse ps-4" id="sidebarEmployees">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('employees.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Employees</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('employees.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Employee</a></li>
        </ul>
    </div>
</li>

<!-- Departments Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarDepartments" role="button" aria-expanded="false" aria-controls="sidebarDepartments" style="text-decoration: none;">
        <i class="fas fa-building"></i> Departments
    </a>
    <div class="collapse ps-4" id="sidebarDepartments">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('departments.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Departments</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('departments.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Department</a></li>
        </ul>
    </div>
</li>

<!-- Positions Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarPositions" role="button" aria-expanded="false" aria-controls="sidebarPositions" style="text-decoration: none;">
        <i class="fas fa-briefcase"></i> Positions
    </a>
    <div class="collapse ps-4" id="sidebarPositions">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('positions.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Positions</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('positions.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Positions</a></li>
        </ul>
    </div>
</li>

<!-- Leave Types Dropdown -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarLeaveTypes" role="button" aria-expanded="false" aria-controls="sidebarLeaveTypes" style="text-decoration: none;">
        <i class="fas fa-calendar"></i> Leave Types
    </a>
    <div class="collapse ps-4" id="sidebarLeaveTypes">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('leave_types.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Leave Types</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('leave_types.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Leave Type</a></li>
            <li><a class="nav-link btn btn-outline-primary text-dark mt-2 mb-1 mx-2 py-2" href="{{ route('leave_requests.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Apply Leave</a></li>
        </ul>
    </div>
</li>

<!-- Grades -->
<li class="nav-item">
    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" data-bs-toggle="collapse" href="#sidebarGrades" role="button" aria-expanded="false" aria-controls="sidebarGrades" style="text-decoration: none;">
        <i class="fas fa-layer-group"></i> Grades
    </a>
    <div class="collapse ps-4" id="sidebarGrades">
        <ul class="nav flex-column">
            <li><a class="nav-link text-dark py-2" href="{{ route('grades.index') }}" style="text-decoration: none;"><i class="fas fa-eye"></i> View Grades</a></li>
            <li><a class="nav-link text-dark py-2" href="{{ route('grades.create') }}" style="text-decoration: none;"><i class="fas fa-plus"></i> Add Grade</a></li>
        </ul>
    </div>
</li>
@endif

                @if(auth()->user()->role_id === 2)
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('dashboard') }}" style="text-decoration: none;">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('supervisor.index') }}" style="text-decoration: none;">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item position-relative">
                        <a href="{{ route('leave_requests.index') }}" class="nav-link text-dark d-flex align-items-center gap-2 py-3" style="text-decoration: none;">
                            <i class="fas fa-bell"></i> Leave Requests
                            @php
                                $pendingRequests = $leaveRequests->whereIn('RequestStatus', ['Pending', 'Pending Supervisor Approval', 'Pending Admin Approval'])->count();
                            @endphp
                            @if($pendingRequests > 0)
                                <span class="badge bg-danger ms-2">
                                    {{ $pendingRequests }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role_id === 3)
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('dashboards.employee') }}" style="text-decoration: none;">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('leave_requests.create') }}" style="text-decoration: none;">
                            <i class="fas fa-calendar-plus"></i> Apply for New Leave
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 py-3" href="{{ route('notifications') }}" style="text-decoration: none;">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- Footer -->
        <div class="border-top p-3 small text-secondary" style="border-color: #e0e0e0 !important;">
            <div class="d-flex flex-column gap-1">
                <span>© 2025 CYBERTECH</span>
                <div>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">About</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Blog</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Terms</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Privacy</a>
                    <a href="#" class="text-secondary me-2" style="text-decoration: none;">Security</a>
                    <a href="#" class="text-secondary" style="text-decoration: none;">Status</a>
                </div>
            </div>
        </div>
    </div>
  </div>

  <!-- Mobile Sidebar Toggle - Only for mobile -->
  <button class="btn btn-primary d-lg-none mobile-menu-toggle" id="mobileMenuToggle" data-bs-toggle="offcanvas" data-bs-target="#mainSidebar">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Main Layout Container -->
  <div class="layout-container">
    <!-- Main Content Area -->
    <div class="main-content-wrapper" id="mainContentWrapper">
      <!-- Fixed Header -->
      <header class="app-header">
        @include('layouts.header')
      </header>

      <!-- Content Wrapper -->
      <div class="content-wrapper">
        <!-- Main Content -->
        <main class="page-content-area" id="main-content">
          <!-- System Alerts -->
          <div class="alerts-container">
            <div class="container-fluid">
              @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                  <div class="d-flex align-items-center">
                    <div class="alert-icon me-3">
                      <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="alert-heading mb-1">Success!</h6>
                      <p class="mb-0">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>
              @endif

              @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                  <div class="d-flex align-items-center">
                    <div class="alert-icon me-3">
                      <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="alert-heading mb-1">Attention Required</h6>
                      <p class="mb-0">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>
              @endif

              @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                  <div class="d-flex align-items-center">
                    <div class="alert-icon me-3">
                      <i class="fas fa-exclamation-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="alert-heading mb-1">Heads Up</h6>
                      <p class="mb-0">{{ session('warning') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>
              @endif

              @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                  <div class="d-flex align-items-center">
                    <div class="alert-icon me-3">
                      <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="alert-heading mb-1">Information</h6>
                      <p class="mb-0">{{ session('info') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>
              @endif
            </div>
          </div>

          <!-- Page Content -->
          <div class="page-content">
            <div class="container-fluid">
              <!-- Page Header -->
              <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="page-header-right">
                    <div class="d-flex align-items-center gap-3">
                      @yield('page-actions')
                      
                      
                    </div>
                  </div>
                </div>
              </div>

              <!-- Main Content Area -->
              <div class="main-content">
                @yield('content')
              </div>
            </div>
          </div>
        </main>
      </div>

      <!-- Fixed Footer -->
      <footer class="app-footer py-3">
        <div class="container-fluid h-100">
          <div class="row align-items-center h-100">
            <div class="col-md-6">
              <div class="d-flex align-items-center">
                <div class="footer-logo me-3">
                  <i class="fas fa-calendar-check text-primary"></i>
                </div>
                <div>
                  <p class="mb-0 text-muted small">
                    &copy; {{ date('Y') }} <span class="fw-semibold text-primary">LeaveHub</span> - Professional Leave Management System
                  </p>
                  <small class="text-muted">v1.0.0</small>
                </div>
              </div>
            </div>
            <div class="col-md-6 text-md-end">
              <div class="d-flex justify-content-md-end gap-3">
                <a href="#" class="text-muted text-decoration-none small"><i class="fas fa-shield-alt me-1"></i> Privacy</a>
                <a href="#" class="text-muted text-decoration-none small"><i class="fas fa-file-contract me-1"></i> Terms</a>
                <a href="#" class="text-muted text-decoration-none small"><i class="fas fa-question-circle me-1"></i> Help</a>
                <a href="#" class="text-muted text-decoration-none small"><i class="fas fa-envelope me-1"></i> Contact</a>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!-- Toast Container -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <!-- Toast notifications will be inserted here -->
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  
  @yield('scripts')
  @stack('scripts')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Hide preloader
      const preloader = document.getElementById('preloader');
      if (preloader) {
        setTimeout(() => {
          preloader.style.opacity = '0';
          preloader.style.visibility = 'hidden';
        }, 300);
      }

      // Get sidebar element
      const sidebar = document.getElementById('mainSidebar');
      
      if (sidebar) {
        // Listen for sidebar show event
        sidebar.addEventListener('show.bs.offcanvas', function() {
          if (window.innerWidth >= 992) {
            // On desktop, add sidebar-open class to body
            document.body.classList.add('sidebar-open');
            console.log('Sidebar opened on desktop');
            
            // FIXED: Adjust content scrolling
            const mainContent = document.querySelector('.main-content-wrapper');
            if (mainContent) {
              mainContent.style.overflowX = 'auto';
            }
          }
        });
        
        // Listen for sidebar hide event
        sidebar.addEventListener('hide.bs.offcanvas', function() {
          if (window.innerWidth >= 992) {
            // On desktop, remove sidebar-open class from body
            document.body.classList.remove('sidebar-open');
            console.log('Sidebar closed on desktop');
            
            // FIXED: Reset content scrolling
            const mainContent = document.querySelector('.main-content-wrapper');
            if (mainContent) {
              mainContent.style.overflowX = 'visible';
            }
          }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
          const bsSidebar = bootstrap.Offcanvas.getInstance(sidebar);
          if (window.innerWidth < 992) {
            // On mobile, ensure sidebar-open class is removed
            document.body.classList.remove('sidebar-open');
            // Auto-close sidebar if open when switching to mobile
            if (bsSidebar && sidebar.classList.contains('show')) {
              bsSidebar.hide();
            }
          } else {
            // On desktop, check current state
            if (bsSidebar && sidebar.classList.contains('show')) {
              document.body.classList.add('sidebar-open');
              // FIXED: Adjust content scrolling
              const mainContent = document.querySelector('.main-content-wrapper');
              if (mainContent) {
                mainContent.style.overflowX = 'auto';
              }
            } else {
              document.body.classList.remove('sidebar-open');
              // FIXED: Reset content scrolling
              const mainContent = document.querySelector('.main-content-wrapper');
              if (mainContent) {
                mainContent.style.overflowX = 'visible';
              }
            }
          }
        });
        
        // Prevent scrolling in sidebar
        sidebar.addEventListener('wheel', function(e) {
          if (e.target.closest('#mainSidebar')) {
            e.preventDefault();
            e.stopPropagation();
            return false;
          }
        }, { passive: false });
        
        sidebar.addEventListener('touchmove', function(e) {
          if (e.target.closest('#mainSidebar')) {
            e.preventDefault();
            e.stopPropagation();
            return false;
          }
        }, { passive: false });
        
        // Close sidebar when clicking on links (for mobile)
        const navLinks = sidebar.querySelectorAll('.nav-link:not([data-bs-toggle="collapse"])');
        navLinks.forEach(link => {
          link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
              const bsSidebar = bootstrap.Offcanvas.getInstance(sidebar);
              if (bsSidebar) {
                bsSidebar.hide();
              }
            }
          });
        });
      }

      // Dark Mode Toggle
      const darkModeToggle = document.getElementById('darkModeToggle');
      if (darkModeToggle) {
        const icon = darkModeToggle.querySelector('i');
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const savedTheme = localStorage.getItem('theme') || 'system';
        
        function applyTheme(theme) {
          const isDark = theme === 'dark' || (theme === 'system' && prefersDarkScheme.matches);
          document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
          document.body.classList.toggle('dark-mode', isDark);
          
          if (icon) {
            icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
          }
          localStorage.setItem('theme', theme);
        }
        
        applyTheme(savedTheme);
        
        darkModeToggle.addEventListener('click', function() {
          const currentTheme = localStorage.getItem('theme') || 'light';
          const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
          applyTheme(newTheme);
          showToast(`Switched to ${newTheme === 'dark' ? 'dark' : 'light'} mode`, 'info');
        });
      }
      
      // Initialize Bootstrap components
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
      
      // Auto-dismiss alerts
      const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
      alerts.forEach(alert => {
        setTimeout(() => {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        }, 5000);
      });
      
      // Toast notification helper
      window.showToast = function(message, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
          <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
            <div class="d-flex">
              <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
          </div>
        `;
        
        document.querySelector('.toast-container').insertAdjacentHTML('beforeend', toastHtml);
        const toast = new bootstrap.Toast(document.getElementById(toastId));
        toast.show();
      };
      
      // Adjust content height on resize
      function adjustContentHeight() {
        const header = document.querySelector('.app-header');
        const footer = document.querySelector('.app-footer');
        const contentWrapper = document.querySelector('.content-wrapper');
        
        if (header && footer && contentWrapper) {
          const headerHeight = header.offsetHeight;
          const footerHeight = footer.offsetHeight;
          
          contentWrapper.style.minHeight = `calc(100vh - ${headerHeight}px - ${footerHeight}px)`;
          contentWrapper.style.marginTop = `${headerHeight}px`;
          contentWrapper.style.marginBottom = `${footerHeight}px`;
        }
      }
      
      window.addEventListener('resize', adjustContentHeight);
      adjustContentHeight();
    });
  </script>
</body>
</html>