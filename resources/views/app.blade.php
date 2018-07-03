<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta title="QR checks">
    <!-- Бьуеф тфьу=ЭмшуцзщкеЭ сщтеуте=Эцшвер=вумшсу-цшверб штшешфд-ысфду=1ЭЮ ершы зфкфь ырщгдв иу фсешмфеув--Ю
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::asset('css/app.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">
    <link rel="stylesheet" href="{{URL::asset('css/vueful/app.style.css')}}">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>
</head>

<body>
<div class="">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <a class="nav-item nav-link {{ Request::segment(1) === 'checks' ? null : 'text-dark' }}" href="{{url('checks')}}">Checks</a>
            <a class="nav-item nav-link {{ Request::segment(1) === 'products' ? null : 'text-dark' }}" href="{{url('products')}}">Products</a>
            <a class="nav-item nav-link {{ Request::segment(1) === 'payments' ? null : 'text-dark' }}" href="{{url('payments')}}">Payments</a>
            <a class="nav-item nav-link {{ Request::segment(1) === 'tags' ? null : 'text-dark' }}" href="{{url('tags')}}">Tags</a>
            <a class="nav-item nav-link {{ Request::segment(1) === 'users' ? null : 'text-dark' }}" href="{{url('users')}}">Users</a>
        </div>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
        </ul>
    </nav>
    @include('common.errors')
    @include('common.messages')
</div>


<script src="{{URL::asset('/js/init.js')}}"></script>

<script src="http://cdn.date-fns.org/v1.9.0/date_fns.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/vueful/components.bundle.js') }}"></script>

<div id="app">
    @yield('content')
</div>


<script src="{{URL::asset('/js/app.js')}}"></script>

</body>
</html>