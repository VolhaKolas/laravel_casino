<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Casino') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/input.css') }}" rel="stylesheet">
    <link href="{{ asset('css/checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('css/message.css') }}" rel="stylesheet">
    <link href="{{ asset('css/screen.css') }}" rel="stylesheet">
    <link href="{{ asset('css/players.css') }}" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
</head>
<body>


{{\Casino\TableId::id1()}}

<input type="hidden" id="user" data-user="{{ Illuminate\Support\Facades\Auth::id() }}">

    <div id="app">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Casino
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="/play" class="dropdown-toggle" role="button" aria-expanded="false">
                                    Играть
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="/editpass" class="dropdown-toggle" role="button" aria-expanded="false">
                                    Изменить пароль
                                </a>
                            </li>

                            <li class="dropdown">
                                <a href="/edit" class="dropdown-toggle" role="button" aria-expanded="false">
                                    Редактировать профиль
                                </a>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->login }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>


    @if(!Auth::guest() and 1 == \Casino\User::offer()[0] and 1 != \Casino\User::answer()[0])
        <div id="screen" style="display: block">
            @include('layouts.getoffer')
        </div>
    @elseif(!Auth::guest() and 0 != count(\Casino\User::loginToAnswer()) and 1 == \Casino\User::answer()[0])
        <div id="waiting" style="display: block">
            @include('layouts.waiting')
        </div>
    @elseif(!Auth::guest())
        <div id="screen">
            @include('layouts.getoffer')
        </div>
    @endif


    @if(!Auth::guest() and 1 == \Casino\User::gameBegin())
        <div id="game" style="display: block">
            @include('layouts.game')
        </div>
    @else
        <div id="game">
            @include('layouts.game')
        </div>
    @endif


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/preview.js') }}"></script>
    <script src="{{ asset('js/checkbox.js') }}"></script>
    @if(!Auth::guest())
    <script src="{{ asset('js/socket.js') }}"></script>
    @endif

</body>
</html>
