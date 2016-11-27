<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Главная')</title>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link href="{{ asset('css/sticky-footer-navbar.css') }}" rel="stylesheet">
</head>

<body>

@include('partials.nav')

<!-- Begin page content -->
<div class="container">
    @yield('content')
</div>

<footer class="footer">
    <div class="container">
        <p class="text-muted"></p>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@yield('script')
</body>
</html>
