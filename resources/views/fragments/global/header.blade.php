<header class="navbar navbar-default navbar-static-top">
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

        <div class="promophone">
            <span class="promophone__label">Звоните нам</span>
            <div class="promophone__number">
                8 800 888-00-00
            </div>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="mainmenu">
                <li class="mainmenu-item">
                    <a href="/information/" class="mainmenu-item-label">
                        <span class="delimiter">Информация</span>
                    </a>
                </li>
                <li class="mainmenu-item">
                    <a href="/hospitals" class="mainmenu-item-label">
                        <span>Медицинские центры</span>
                    </a>
                </li>
                <li class="mainmenu-item">
                    <a href="/researches" class="mainmenu-item-label">
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
                <!--
                <li class="mainmenu-item">
                    <a href="#" class="mainmenu-item-label">
                        <span>Поиск по карте</span>
                    </a>
                </li> -->
            </ul>

        </div>
    </div>
    <div class="divider"></div>
</header>