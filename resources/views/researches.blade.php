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
            <h1>Исследования</h1>
            @if (isset($researches))
                <div class="hospitals hospitals_small">
                    @foreach($researches as $research)
                        <div class="hospitals__item">
                            @if (!empty($research->getLogo()))
                            <div class="hospitals__item__cover">
                                <img src="{{ $research->getLogo() }}">
                            </div>
                            @endif
                            <div class="hospitals__item__info">
                                <a class="hospitals__item__name" href="researches/{{$research->id}}">{{$research->name}}</a>
                                <div>
                                    {{ str_limit(strip_tags($research->description), 100, '...') }}
                                </div>
                            </div>
                            <div class="hospitals__item__action">
                            </div>
                        </div>
                    @endforeach
                </div>
                <?php echo $researches->render(); ?>
            @elseif(!isset($researches))
                <div class="alert alert-info">Нет доступных исследований</div>
            @endif
        </div>
    </div>
@endsection