<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta & Title -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Leave Management System</title>
  
  <!-- Bootstrap & CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    :root {
      --primary-white: #ffffff;
      --neutral-gray: #f5f5f5;
      --accent-blue: #76c6f3;
      --deep-shadow-gray: #d1d1d1;
    }
    .profile-btn {
      display: inline-block;
      width: 70px;
      height: 70px;
      border-radius: 60%;
      overflow: hidden;
    }
    .profile-photo-navbar {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #ccc;
    }
    .card-custom {
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease-in-out;
    }
    .card-custom:hover {
      transform: scale(1.02);
    }
    .nav-link .badge {
      font-size: 14px;
      padding: 6px;
      border-radius: 50%;
    }
    .btn-rounded {
      border-radius: 20px;
    }
    .btn-circle {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
    }
    body {
      font-family: 'Gill Sans';
      background-color: rgba(226, 232, 238, 0.93);
      color: #d1d1d1;
    }
    .with-padding {
      padding-left: 7cm;
    }
    .form-text {
      color: black;
      font-family: 'Gill Sans';
      font-size: large;
    }
    /* Table Header & Main Table */
    #table-header {
      background: rgb(2, 43, 114);
      color: white;
      padding: 20px 0;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      font-weight: bold;
    }
    #main-table {
      background: rgb(162, 204, 236);
      color: white;
    }
    /* Header */
    header {
      color: black;
      padding: 20px 0;
    }
    header h1 {
      font-weight: 600;
      margin: 0;
    }
    header .btn-outline-light {
      border-color: rgba(255, 255, 255, 0.5);
      color: white;
    }
    header .btn-outline-light:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    /* Cards */
    .card {
      border: none;
      border-radius: 10px;
      background: white;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: 0.3s;
    }
    .card-header {
      background-color: #3D519F;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(78, 213, 225, 0.81);
    }
    .card-title a {
      color: #333;
      text-decoration: none;
    }
    .card-title a:hover {
      text-decoration: underline;
    }
    .card-text {
      color: #666;
    }
    /* Dropdown Arrow */
    .dropdown-arrow {
      transition: transform 0.3s ease;
    }
    .dropdown-arrow.rotated {
      transform: rotate(180deg);
    }
    /* Main Content */
    #main-content {
      margin-left: 60px;
      transition: margin-left 0.3s ease;
    }
    #main-content.expanded {
      margin-left: 250px;
    }
    .content {
      margin-left: 60px;
      transition: margin-left 0.3s;
      padding: 20px;
    }
    .content.expanded { 
      margin-left: 220px; 
    }
    /* Offcanvas Menu */
    .offcanvas-menu {
      display: flex;
      flex-direction: column;
      width: 60px;
      height: 100vh;
      background-color: #3D519F;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      overflow-x: auto;
      z-index: 1045;
    }
    .offcanvas-menu.expanded {
      width: 250px;
    }
    .offcanvas-menu img.logo {
      width: 40px;
      height: 40px;
      margin: 10px;
      transition: transform 0.3s, width 0.3s, height 0.3s;
    }
    .offcanvas-menu.expanded img.logo {
      width: 80px;
      height: 80px;
      transform: translateX(55px);
    }
    .offcanvas-menu .toggle-btn {
      background: none;
      border: none;
      color: white;
      margin: 10px;
      font-size: 1.2rem;
    }
    .offcanvas-menu .nav {
      margin-top: 20px;
    }
    .offcanvas-menu .nav-link {
      color: white;
      padding: 10px 20px;
      display: block;
      text-decoration: none;
      transition: background 0.3s;
    }
    .offcanvas-menu .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    .offcanvas-menu ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }
    .offcanvas-menu .nav-link i {
      font-size: 18px;
      margin-right: 10px;
    }
    .menu-text {
      opacity: 0;
      transition: opacity 0.3s, transform 0.3s;
      transform: translateX(-20px);
    }
    .offcanvas-menu.expanded .menu-text {
      opacity: 1;
      transform: translateX(0);
    }
    .submenu {
      padding-left: 20px;
    }
    .offcanvas .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    .offcanvas .nav-link i {
      margin-right: 10px;
    }
    /* Table Styles */
    .table {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .table thead {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
    }
    .table th, .table td {
      padding: 12px;
    }
    .table tbody tr:hover {
      background: rgba(0, 0, 0, 0.05);
    }
    tr.hover-up {
      transform: translateY(-5px);
      transition: transform 0.3s ease;
      background-color: rgb(20, 102, 174);
    }
    tr.hover-up td {
      background-color: rgb(20, 102, 174);
    }
    
    /* Welcome Section */
    .wtext {
      color: black;
      text-align: center;
      font-family: 'Gill Sans';
      font-size: 50px !important;
      padding-left: 45px;
    }
    .wtext2 {
      color: black;
      text-align: center;
      padding-left: 5px;
    }
    /* Illustration positioning */
    .illustration {
      position: absolute;
      left: 0;
      top: 0;
      transform: translate(-15%, -20%);
      max-width: 300px;
    }
    /* Footer */
    footer {
      background-color: #3D519F;
      color: white;
      padding: 20px 0;
      margin-top: 40px;
      box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
    }
    footer p {
      margin: 0;
    }
  </style>

  @yield('styles')
</head>
<body>
  <!-- IMPORTANT: Define default collections if variables aren't available -->
  @php
    $leaveRequests = $leaveRequests ?? collect();
    $recentleaveRequests = $recentleaveRequests ?? collect();
  @endphp

  <!-- Header -->
  <header class="py-3">
    <div class="container-fluid">
      <div class="row align-items-center">
        <!-- Offcanvas Sidebar -->
        <div class="offcanvas-menu" id="sidebar" style="background-color:#3D519F;">
          <!-- Logo -->
          <img src="{{ asset('logo3.png') }}" alt="Logo" class="logo">
          <!-- Toggle Button -->
          <button id="menuToggle" class="toggle-btn"><i class="fas fa-bars"></i></button>
          <ul class="nav flex-column mt-4">
            <!-- Admin Pages (Only Admins) -->
            @if(auth()->user()->role_id === 1)
              <!-- Admin Dashboard -->
              <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboards.admin') }}">
                  <i class="fas fa-home"></i>
                  <span class="menu-text">Dashboard</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.leave_requests') }}" class="nav-link">
                  <span class="menu-text">Admin Verification</span>
                </a>
              </li>
              <!-- Employees Dropdown -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapseEmployees" aria-expanded="false" aria-controls="collapseEmployees">
                  <i class="fas fa-users"></i>
                  <span class="menu-text">Employees</span>
                  <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <div class="collapse" id="collapseEmployees">
                  <ul class="submenu">
                    <li><a class="nav-link" href="{{ route('employees.index') }}"><i class="fas fa-eye"></i>View Employees</a></li>
                    <li><a class="nav-link" href="{{ route('employees.create') }}"><i class="fas fa-plus"></i>Add Employee</a></li>
                  </ul>
                </div>
              </li>
              <!-- Departments Dropdown -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapseDepartments" aria-expanded="false" aria-controls="collapseDepartments">
                  <i class="fas fa-building"></i>
                  <span class="menu-text">Departments</span>
                  <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <div class="collapse" id="collapseDepartments">
                  <ul class="submenu">
                    <li><a class="nav-link" href="{{ route('departments.index') }}"><i class="fas fa-eye"></i>View Departments</a></li>
                    <li><a class="nav-link" href="{{ route('departments.create') }}"><i class="fas fa-plus"></i>Add Department</a></li>
                  </ul>
                </div>
              </li>
              <!-- Leave Requests Notification -->
              <li class="nav-item">
                <a href="{{ route('leave_requests.index') }}" class="nav-link position-relative">
                  <i class="fas fa-bell fa-lg"></i>
                  @php
                    $pendingRequests = $leaveRequests->whereIn('RequestStatus', ['Pending', 'Pending Supervisor Approval', 'Pending Admin Approval'])->count();
                  @endphp
                  @if($pendingRequests > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      {{ $pendingRequests }}
                    </span>
                  @endif
                </a>
              </li>
              <!-- Positions Dropdown -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapsePositions" aria-expanded="false" aria-controls="collapseEmployees">
                  <i class="fas fa-briefcase"></i>
                  <span class="menu-text">Positions</span>
                  <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <div class="collapse" id="collapsePositions">
                  <ul class="submenu">
                    <li><a class="nav-link" href="{{ route('positions.index') }}"><i class="fas fa-eye"></i>View Positions</a></li>
                    <li><a class="nav-link" href="{{ route('positions.create') }}"><i class="fas fa-plus"></i>Add Positions</a></li>
                  </ul>
                </div>
              </li>
              <!-- Leave Types Dropdown -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapseLeaveTypes" aria-expanded="false" aria-controls="collapseEmployees">
                  <i class="fas fa-calendar"></i>
                  <span class="menu-text">Leave Types</span>
                  <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <div class="collapse" id="collapseLeaveTypes">
                  <ul class="submenu">
                    <li><a class="nav-link" href="{{ route('leave_types.index') }}"><i class="fas fa-eye"></i>View Leave Types</a></li>
                    <li><a class="nav-link" href="{{ route('leave_types.create') }}"><i class="fas fa-plus"></i>Add Leave Types</a></li>
                    <li class="nav-item">
                      <a class="nav-link btn btn-outline-primary text-white" href="{{ route('leave_requests.create') }}">Apply Leave</a>
                    </li>
                  </ul>
                </div>
              </li>
            @endif

            <!-- Supervisor Pages (Only Supervisor) -->
            @if(auth()->user()->role_id === 2)
              <li class="nav-item">
                <a class="nav-link" href="{{ route('supervisor.index') }}">
                  <i class="fas fa-home"></i>
                  <span class="menu-text">Dashboard</span>
                </a>
              </li>
            @endif

            <!-- Employee Pages (Only Employees) -->
            @if(auth()->user()->role_id === 3)
              <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboards.employee') }}">
                  <i class="fas fa-home"></i>
                  <span class="menu-text">Dashboard</span>
                </a>
              </li>
              <li>
                <a href="{{ route('leave_requests.create') }}" class="nav-link">
                  <i class="fas fa-calendar-plus me-2"></i>
                  <span class="menu-text">Apply for New Leave</span>
                </a>
              </li>
            @endif
          </ul>
        </div>
        
        @yield('sidebar')
        @yield('header')

        <!-- Profile and Logout Buttons -->
        <div class="d-flex justify-content-end" style="color:#3D519F;">
          <a href="{{ route('profile.edit') }}" class="profile-btn">
            @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
              <img src="{{ asset(Auth::user()->profile_photo) }}" class="profile-photo-navbar" alt="Profile Picture">
            @else
              <img src="{{ asset('images/default-avatar.png') }}" class="profile-photo-navbar" alt="Default Profile Picture">
            @endif
          </a>
          <!-- Logout Button -->
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-rounded">
              <i class="fas fa-sign-out-alt"></i><br>Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content and Notifications -->
  @if (session('success'))
    <div class="alert alert-dismissible fade show shadow-sm text-center mx-auto" role="alert" style="background-color: #155724; color: white; max-width: 600px; margin-top: 80px;">
      <strong>Success!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-dismissible fade show shadow-sm text-center mx-auto" role="alert" style="background-color: #8B0000; color: white; max-width: 600px; margin-top: 80px;">
      <strong>Error!</strong> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Content Wrapper -->
  <div class="container-fluid mt-4">
    <div class="row">
      <div class="col" id="main-content">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const menuToggle = document.getElementById('menuToggle');
      const sidebar = document.getElementById('sidebar');
      const content = document.getElementById('main-content');
      // Toggle sidebar expansion
      menuToggle.addEventListener('click', function () {
        sidebar.classList.toggle('expanded');
        content.classList.toggle('expanded');
      });
    });
    // Rotate the dropdown arrow when toggling
    document.addEventListener('DOMContentLoaded', function () {
      const parentToggles = document.querySelectorAll('.parent-toggle');
      parentToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
          const arrow = this.querySelector('.dropdown-arrow');
          arrow.classList.toggle('rotated');
        });
      });
    });
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function () {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
    // Logout confirmation
    function confirmLogout() {
      if (confirm('Are you sure you want to log out?')) {
        document.getElementById('logout-form').submit();
      }
    }
  </script>
  @yield('scripts')

  <!-- Footer -->
  <footer class="text-white text-center py-3 mt-4">
    <p class="mb-0">&copy; {{ date('Y') }} ABC Mission Hospital. All rights reserved.</p>
    <p class="mb-0">Developed by MUST INTERNS</p>
  </footer>
</body>
</html>
