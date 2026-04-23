<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="{{ asset('bootstrap-5.3.5-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.7.2-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>

<body>
    @yield('content')

    <!-- Bootstrap Bundle with Popper -->
    <script src="{{ asset('bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>