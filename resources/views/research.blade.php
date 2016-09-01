@extends('layouts.frontend')

@section('content')
    <div class="container gridin">
        <div class="gridin__rightside">
            @if ($researchesTab->count())
            <div class="sidelist">
                <div class="sidelist__header">Виды исследований</div>
                <ul class="sidelist__body">
                    @foreach($researchesTab as $research)
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
            <h1>{{ $name }}</h1>

            {!! $description !!}

        </div>
    </div>
@endsection