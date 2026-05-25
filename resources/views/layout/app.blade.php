<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ОчУмелые ручки')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>

<body>
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <a href="{{ route('home') }}"> <img src="{{ asset('img/logo.png') }}" alt="Logo"> </a>
            </div>
            <div class="title">
                <a href="{{ route('home') }}">Клуб любителей творчества «ОчУмелые ручки»</a>
            </div>
            <div class="auth">
                @auth
                @if(auth()->user()->role === 'master')
                <a href="{{ route('cabinet') }}">Кабинет</a>
                @else
                <span style="color: #00044c; font-size: 10pt; font-weight: bold;">{{ auth()->user()->name }}</span>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:#00044c; font-weight:bold; cursor:pointer;">Выход</button>
                </form>
                @else
                <a href="{{ route('login') }}">Вход</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="row row--nogutter">
        <div class="menu-burger">
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <div class="main">
        @yield('content')
    </div>

    <div class="row row--nogutter">
        <div class="line"></div>
    </div>

    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120в</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, 2017</div>
            </div>
        </div>
    </div>

    <script>
        // скрипт для бургер меню
        document.querySelector('.burger')?.addEventListener('click', function() {
            let menu = document.querySelector('.menu');
            if (menu) menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
    </script>
</body>

</html>