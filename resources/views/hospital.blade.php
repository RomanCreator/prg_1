@extends('layouts.frontend')

@section('content')
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
                {{ csrf_field() }}
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
            <h1>{{ isset($name) ? $name : '' }}</h1>
            @if (isset($gallerySmall) && !empty($gallerySmall))
            <div class="gallery" data-toggle="simple_gallery">
                <div class="gallery__big">
                    <img src="{{ $galleryBig[0] }}" class="gallery__big__elem">
                </div>
                <div class="gallery__nav">
                    <a href="#" class="gallery__nav__control gallery__nav__control_up"></a>
                    <a href="#" class="gallery__nav__control gallery__nav__control_left"></a>
                    <div class="gallery__nav__wrapper">
                        @foreach($gallerySmall as $key => $value)
                        <img src="{{ $value }}" class="gallery__nav__elem" data-orig="{{ $galleryBig[$key] }}">
                        @endforeach
                    </div>
                    <a href="#" class="gallery__nav__control gallery__nav__control_right"></a>
                    <a href="#" class="gallery__nav__control gallery__nav__control_down"></a>
                </div>
            </div>
            @endif

            <div class="hospital-info">
                <div class="hospital-info__data">
                    <span class="hospital-info__data__tag">
                        @if (isset($tags))
                            @foreach($tags as $tag)
                                @if(!empty($tag))
                                    <span class="hospital-info__data__tag__tag">{{ $tag }}</span>
                                @endif
                            @endforeach
                        @endif
                    </span>
                    <span class="hospital-info__data__label">{{ $district }}</span>
                    <span class="hospital-info__data__label">{{ $address }}</span>
                    <span class="hospital-info__data__label">{{ $subway }}</span>
                    <ul class="hospital-info__data__timeToWork">
                        @if (isset($timeToWork))
                            @foreach($timeToWork as $time)
                                <li>{{ $time }}</li>
                            @endforeach
                        @endif
                    </ul>
                    <span class="hospital-info__data__phone">(812) 490-75-73</span>
                </div>
                <div class="hospital-info__action">
                    <a href="#" class="btn btn-info checkin" data-id="{{ $id }}">Записаться</a>
                </div>
            </div>

            {!! $description !!}

            @if ($prices)
            <div class="price-list">
                <div class="price-list__header">
                    Цены
                </div>
                <ul class="price-list__body">
                    @foreach($prices as $price)
                        <li>  <a href="/researches/{{ $price->research->id }}">{{ $price->research->name }}</a>
                            <span class="price-list__body__pice">
                                @if (isset($price->price_to) && $price->price_to > 0)
                                    от {{ $price->price_from }}
                                @else
                                    {{ $price->price_from }}
                                @endif
                                 руб
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
@endsection