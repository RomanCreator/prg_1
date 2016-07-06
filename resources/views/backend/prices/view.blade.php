@extends('layouts.backend')

@section('title')
    {{ $nameAction }}
@endsection

@include('homemenu')

@section('content')
    <div class="container-fluid">
        <h1>{{ $nameAction }}</h1>
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="row">
                    <label class="col-sm-3">Название учреждения</label>
                    <div class="col-sm-9">
                        {{ $hospital->name }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Название исследования</label>
                    <div class="col-sm-9">
                        {{ $research->name }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Цена (Цена от)</label>
                    <div class="col-sm-9">
                        {{ $price_from }}
                    </div>
                </div>

                @if (isset($price_to) && $price_to)
                    <div class="row">
                        <label class="col-sm-3">Цена до</label>
                        <div class="col-sm-9">
                            {{ $price_to }}
                        </div>
                    </div>
                @endif

                <div class="row">
                    <label class="col-sm-3">Описание исследования для конкретного учреждения</label>
                    <div class="col-sm-9">
                        {!! $description !!}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Статус пункта прайс листа</label>
                    <div class="col-sm-9">
                        @if($status)
                            Отображается на страницах сайта
                        @else
                            Не отображается на страницах сайта
                        @endif
                    </div>
                </div>

                <a href="{{ isset($controllerPathList) ? $controllerPathList : url('/home/') }}" class="btn btn-default">Назад</a>
            </div>
        </div>
    </div>
@endsection