<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta & Title -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Leave Management System</title>
  
  <!-- Bootstrap & CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
  <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
    /* Dropdown Arrow for main content, if used */
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
  @php
    $leaveRequests = $leaveRequests ?? collect();
    $recentleaveRequests = $recentleaveRequests ?? collect();
  @endphp

  @include('layouts.header')
  @include('layouts.sidebar')

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
    // Only JS for dropdowns/tooltips/confirmLogout as needed in main content
    document.addEventListener('DOMContentLoaded', function () {
      const parentToggles = document.querySelectorAll('.parent-toggle');
      parentToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
          const arrow = this.querySelector('.dropdown-arrow');
          arrow.classList.toggle('rotated');
        });
      });
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
         return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
    function confirmLogout() {
      if (confirm('Are you sure you want to log out?')) {
        document.getElementById('logout-form').submit();
      }
    }
  </script>
  <script>
  // Dark Mode Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('darkModeToggle');
        const icon = btn.querySelector('i');
        const darkClass = 'dark-mode';

        // Load mode from localStorage
        if(localStorage.getItem('darkMode') === 'on') {
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
    });

    btn.addEventListener('click', function() {
    document.body.classList.toggle(darkClass);
    const isDark = document.body.classList.contains(darkClass);
    icon.classList.toggle('fa-moon', !isDark);
    icon.classList.toggle('fa-sun', isDark);
    icon.style.transform = 'rotate(' + (isDark ? 180 : 0) + 'deg)';
    localStorage.setItem('darkMode', isDark ? 'on' : 'off');
});
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
