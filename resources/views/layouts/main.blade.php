<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Казино')</title>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
</head>

<body>

@include('partials.nav')

<!-- Begin page content -->
<div class="container">
    @yield('content')
</div>
@if(auth::check())
@include('partials.online')
<script src="{{asset('js/OnlineTime.js')}}"></script>
@endif
<footer class="footer">
    <div class="container">
        <p class="text-muted"></p>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@yield('script')
</body>
</html>
