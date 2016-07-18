<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>


    <!-- Scripts -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="application/javascript" src="/js/bootstrap.js"></script>

    <!-- Fonts -->

    <!-- Styles -->
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/font-awesome.css">
    <link rel="stylesheet" href="/css/style.css">





    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}


</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Переключение навигации</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/img/logo.png">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="mainmenu">
                    <li class="mainmenu-item">
                        <a href="#" class="mainmenu-item-label">
                            <span class="delimiter">Информация</span>
                        </a>
                    </li>
                    <li class="mainmenu-item">
                        <a href="#" class="mainmenu-item-label">
                            <span>Медицинские центры</span>
                        </a>
                    </li>
                    <li class="mainmenu-item">
                        <a href="#" class="mainmenu-item-label">
                            <span>Исследования</span>
                        </a>
                        <ul class="mainmenu-item-dropdown">
                            <li class="mainmenu-item-dropdown-item">
                                <a href="#" class="mainmenu-item-dropdown-label">
                                    <span>МРТ</span>
                                </a>
                            </li>
                            <li class="mainmenu-item-dropdown-item">
                                <a href="#" class="mainmenu-item-dropdown-label">
                                    <span>КТ</span>
                                </a>
                            </li>
                            <li class="mainmenu-item-dropdown-item">
                                <a href="#" class="mainmenu-item-dropdown-label">
                                    <span>Подготовка</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="mainmenu-item">
                        <a href="#" class="mainmenu-item-label">
                            <span>Поиск по карте</span>
                        </a>
                    </li>
                </ul>


            </div>
        </div>
    </nav>

    @yield('content')
</body>
</html>
