@extends('layouts.frontend')

@section('content')
    <div class="container gridin">
        <div class="gridin__rightside">
            <div class="sidelist">
                <div class="sidelist__header">Виды исследований</div>
                <ul class="sidelist__body">
                    @foreach($researches as $research)
                        <li><a href="/researches/{{$research->id}}">{{$research->name}}</a></li>
                    @endforeach
                </ul>
            </div>

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
            <h1>{{ $name }}</h1>
            @if (isset($gallerySmall))
            <div class="gallery">
                <div class="gallery__big">
                    <img src="{{ $galleryBig[0] }}" class="gallery__big__elem">
                </div>
                <div class="gallery__nav">
                    <a href="#" class="gallery__nav__control gallery__nav__control_up"></a>
                    <div class="gallery__nav__wrapper">
                        @foreach($gallerySmall as $key => $value)
                        <img src="{{ $value }}" class="gallery__nav__elem" data-orig="{{ $galleryBig[$key] }}">
                        @endforeach
                    </div>
                    <a href="#" class="gallery__nav__control gallery__nav__control_down"></a>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection