<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Leave Management System')</title>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('bootstrap-5.3.5-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/all.min.css') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
