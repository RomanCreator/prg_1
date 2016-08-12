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
            <h1>Медицинские центры</h1>
            @if (isset($hospitals))
                <div class="hospitals hospitals_small">
                    @foreach($hospitals as $hospital)
                        <div class="hospitals__item">
                            <img class="hospitals__item__cover" src="{{ $hospital->getLogo() }}">
                            <div class="hospitals__item__info">
                                <a class="hospitals__item__name" href="hospitals/{{$hospital->id}}">{{$hospital->name}}</a>
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
                                <div class="hospitals__item__phone">(812) 490-75-73</div>
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