@extends('layouts.frontend')
<?php $CallCenterNumber = \App\CallCenterPhoneNumber::first() ? $CallCenterNumber = \App\CallCenterPhoneNumber::first()->number : $CallCenterNumber = '' ?>
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
            <h1>Медицинские центры</h1>
            @if (isset($hospitals))
                <div class="hospitals hospitals_small">
                    @foreach($hospitals as $hospital)
                        <div class="hospitals__item">
                            <div class="hospitals__item__cover">
                                <img src="{{ $hospital->getLogo() }}">
                                <div class="hospitals__item__prices_mobile">
                                    @if (!empty($hospital->typeResearchesPrice()))
                                        @foreach($hospital->typeResearchesPrice() as $price)
                                            <span class="hospitals__item__action__type-price mobile">{{ $price }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="hospitals__item__info">
                                <a class="hospitals__item__name" href="hospitals/{{$hospital->id}}">{{$hospital->name}}</a>
                                <div class="hospitals__item__tags">
                                    @if (!empty($hospital->getTags()))
                                        @foreach($hospital->getTags() as $tag)
                                            @if(!empty($tag))
                                                <span class="hospitals__item__tag">{{ $tag }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="hospitals__item__address">
                                    <span class="hospitals__item__address__district">{{ !empty($hospital->getDistrict) ? $hospital->getDistrict->name : '' }}</span>
                                    {{ $hospital->address }}<br>
                                    {{ $hospital->subway }}
                                </div>
                                <ul class="hospitals__item__timeToWork">
                                        @foreach($hospital->getWeekWorksTime() as $time)
                                            <li>{{ $time }}</li>
                                        @endforeach
                                </ul>
                                <div class="hospitals__item__phone">{{ $CallCenterNumber }}</div>
                            </div>
                            <div class="hospitals__item__action">
                                <button class="btn btn-info checkin">Записаться</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <?php echo $hospitals->render(); ?>
            @elseif(!isset($hospitals))
                <div class="alert alert-info">Нет медицинских центров</div>
            @endif
        </div>
    </div>
@endsection