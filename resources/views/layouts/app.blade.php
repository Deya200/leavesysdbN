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

  <!-- Custom Styles -->
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

    .form-text {
      color: black;
      font-family: 'Gill Sans';
      font-size: large;
    }

    /* Table Styling Fix */
    .table,
    .table th,
    .table td {
      background-color: white !important;
      color: black !important;
    }

    tr.hover-up,
    tr.hover-up td {
      background-color: white !important;
      color: black !important;
    }

    .table thead {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      color: white !important;
    }

    .table tbody tr:hover {
      background: rgba(0, 0, 0, 0.05);
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 10px;
      background: white;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(78, 213, 225, 0.81);
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
  @include('layouts.header')
  @include('layouts.sidebar')

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
    document.addEventListener('DOMContentLoaded', function () {
      const parentToggles = document.querySelectorAll('.parent-toggle');
      parentToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
          const arrow = this.querySelector('.dropdown-arrow');
          arrow.classList.toggle('rotated');
        });
      });

      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
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
        icon.style.transform = 'rotate(' + (isDark ? 180 : 0) + 'deg)';
        localStorage.setItem('darkMode', isDark ? 'on' : 'off');
      });
    });
  </script>

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

  @yield('scripts')

  <footer class="text-white text-center py-3 mt-4">
    <p class="mb-0">&copy; {{ date('Y') }} Leave Management System.</p>
    <p class="mb-0">Developed by MUST TEAM</p>
  </footer>
</body>
</html>
