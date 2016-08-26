<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(isset($keywords) && !empty($keywords))
        <meta name="keywords" content="{{ $keywords }}">
    @endif
    @if(isset($description) && !empty($description))
        <meta name="description" content="$description">
    @endif
    <title>{{ isset($title) ? $title : '' }}</title>


    @include('fragments.global.scriptsandstyles')





    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">-->
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}


</head>
<body id="app-layout">
@include('fragments.global.header')
<div class="container gridin">
    <div class="gridin__rightside">
        @if ($researches->count())
        <div class="sidelist">
            <div class="sidelist__header">Виды исследований</div>
            <ul class="sidelist__body">
                @foreach($researches as $research)
                    <li><a href="/researches/{{$research->id}}">{{$research->name}}</a></li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="sidesearch" method="post" action="/search">
            <input type="text" class="form-element sidesearch__elem" name="search" placeholder="Поиск">
        </form>

        <div class="phone-panel phone-panel_right-side">
            <div class="phone-panel__body">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="phone" placeholder="Введите номер телефона" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="Ваше имя" required>
                    </div>
                </form>
            </div>
            <div class="phone-panel__footer">
                Заказать звонок
            </div>
        </div>
    </div>
    <div class="gridin__content">
        @yield('content')
    </div>
</div>
@include('fragments.global.footer')
</body>
</html>
