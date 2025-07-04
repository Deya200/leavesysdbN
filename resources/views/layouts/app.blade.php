<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') - Leave Management System</title>

  <!-- Bootstrap & Assets -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

  <!-- Only custom styles for main content, not header/sidebar -->
  <style>

    body, header, .dropdown-menu, .card, .modal-content, .form-control, .table, .btn {
    transition: background 0.25s, color 0.25s, border-color 0.25s;
    }

    :root {
      --primary-white: #ffffff;
      --neutral-gray: #f5f5f5;
      --accent-blue: #76c6f3;
      --deep-shadow-gray: #d1d1d1;
    }

    body {
      font-family: 'Gill Sans';
      background-color: rgba(226, 232, 238, 0.93);
      color: #d1d1d1;
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

    .card, .table {
      border-radius: 10px;
      background: white;
      box-shadow: 0 4px 6px rgba(231, 220, 220, 0.1);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(78, 213, 225, 0.81);
    }

    .table thead {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white;
    }

    .table tbody tr:hover {
      background: rgb(20, 102, 174);
    }

    .dropdown-arrow {
      transition: transform 0.3s ease;
    }

    .dropdown-arrow.rotated {
      transform: rotate(180deg);
    }

    #main-content, .content {
      margin-left: 60px;
      transition: margin-left 0.3s;
      padding: 20px;
    }

    #main-content.expanded, .content.expanded {
      margin-left: 220px;
    }

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
  @php
    $leaveRequests = $leaveRequests ?? collect();
    $recentleaveRequests = $recentleaveRequests ?? collect();
  @endphp

  <!-- Header with Sidebar -->
  <header class="py-3">
    <div class="container-fluid">
      <div class="row align-items-center">
        <!-- Sidebar Menu -->
        <div class="offcanvas-menu" id="sidebar" style="background-color:#3D519F;">
          <!-- Logo & Toggle -->
          <img src="{{ asset('logo3.png') }}" alt="Logo" class="logo">
          <button id="menuToggle" class="toggle-btn"><i class="fas fa-bars"></i></button>

          <ul class="nav flex-column mt-4">
            {{-- Admin --}}
            @if(auth()->user()->role_id === 1)
              <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                  <i class="fas fa-home"></i>
                  <span class="menu-text">Dashboard</span>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.verification') }}" class="nav-link">
                  <i class="fas fa-user-shield me-2"></i>
                  <span class="menu-text">Admin Verification</span>
                </a>
              </li>

              <!-- Employees -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapseEmployees">
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

              <!-- Departments -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapseDepartments">
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

              <!-- Positions -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapsePositions">
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

              <!-- Leave Types -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapseLeaveTypes">
                  <i class="fas fa-calendar"></i>
                  <span class="menu-text">Leave Types</span>
                  <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <div class="collapse" id="collapseLeaveTypes">
                  <ul class="submenu">
                    <li><a class="nav-link" href="{{ route('leave_types.index') }}"><i class="fas fa-eye"></i>View Leave Types</a></li>
                    <li><a class="nav-link" href="{{ route('leave_types.create') }}"><i class="fas fa-plus"></i>Add Leave Types</a></li>
                    <li>
                      <a class="nav-link btn btn-outline-primary text-white" href="{{ route('leave_requests.create') }}">Apply Leave</a>
                    </li>
                  </ul>
                </div>
              </li>

              <!-- Grades -->
              <li class="nav-item">
                <a class="nav-link parent-toggle" data-bs-toggle="collapse" data-bs-target="#collapseGrades">
                  <i class="fas fa-layer-group"></i>
                  <span class="menu-text">Grade</span>
                  <i class="fas fa-chevron-down dropdown-arrow"></i>
                </a>
                <div class="collapse" id="collapseGrades">
                  <ul class="submenu">
                    <li><a class="nav-link" href="{{ route('grades.index') }}"><i class="fas fa-eye"></i>View Grades</a></li>
                    <li><a class="nav-link" href="{{ route('grades.create') }}"><i class="fas fa-plus"></i>Add Grade</a></li>
                  </ul>
                </div>
              </li>
            @endif
            {{-- Supervisor --}}
            @if(auth()->user()->role_id === 2)
              <li class="nav-item">
                <a class="nav-link" href="{{ route('supervisor.index') }}">
                  <i class="fas fa-home"></i>
                  <span class="menu-text">Dashboard</span>
                </a>
              </li>
            @endif

            {{-- Employee --}}
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
              <li>
                <a href="{{ route('notifications') }}" class="nav-link">
                  <i class="fas fa-bell me-2"></i>
                  <span class="menu-text">Notifications</span>
                </a>
              </li>
            @endif
          </ul>
        </div>

        {{-- Optional Blade Includes --}}
        @yield('sidebar')
        @yield('header')

        <!-- Profile & Logout -->
        <div class="d-flex justify-content-end" style="color:#3D519F;">
          <a href="{{ route('profile.edit') }}" class="profile-btn">
            @if(!empty(Auth::user()->profile_photo) && file_exists(public_path(Auth::user()->profile_photo)))
              <img src="{{ asset(Auth::user()->profile_photo) }}" class="profile-photo-navbar" alt="Profile Picture">
            @else
              <img src="{{ asset('images/default-avatar.png') }}" class="profile-photo-navbar" alt="Default Profile Picture">
            @endif
          </a>

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

  {{-- Success & Error Alerts --}}
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
  <!-- Main Page Content -->
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

    // ✅ On login: fully expanded sidebar
    sidebar.style.display = 'block';
    sidebar.classList.add('expanded');
    content.classList.add('expanded');

    // ✅ Hamburger toggles sidebar visibility completely
    menuToggle.addEventListener('click', function () {
      const isHidden = sidebar.style.display === 'none';

      if (isHidden) {
        sidebar.style.display = 'block';
        sidebar.classList.add('expanded');
        content.classList.add('expanded');
      } else {
        sidebar.style.display = 'none';
        sidebar.classList.remove('expanded');
        content.classList.remove('expanded');
      }
    });

    // 🔁 Rotate dropdown arrows on parent menus
    document.querySelectorAll('.parent-toggle').forEach(toggle => {
      toggle.addEventListener('click', function () {
        const arrow = this.querySelector('.dropdown-arrow');
        arrow.classList.toggle('rotated');
      });
    });

    // 🎯 Enable Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
  });

  function confirmLogout() {
    if (confirm('Are you sure you want to log out?')) {
      document.getElementById('logout-form').submit();
    }
  }
</script>


  @yield('scripts')

  <script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

  <footer class="text-white text-center py-3 mt-4">
    <p class="mb-0">&copy; {{ date('Y') }} Leave Management System.</p>
    <p class="mb-0">Developed by</p>
  </footer>
</body>
</html>
