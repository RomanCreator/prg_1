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
                    <label class="col-sm-3">Название исследования</label>
                    <div class="col-sm-9">
                        {{ $name }}
                    </div>
                </div>

                @if (isset($diagram) && $diagram)
                <div class="row">
                    <label class="col-sm-3">Диаграма исследования</label>
                    <div class="col-sm-9">
                        <img src="{{ asset($diagram) }}">
                    </div>
                </div>
                @endif

                <div class="row">
                    <label class="col-sm-3">Описание исследования</label>
                    <div class="col-sm-9">
                        {!! $description !!}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Статус исследования</label>
                    <div class="col-sm-9">
                        @if($status)
                            Исследование отображается на страницах сайта
                        @else
                            Исследование не отображается на страницах сайта
                        @endif
                    </div>
                </div>

                <a href="{{ isset($controllerPathList) ? $controllerPathList : url('/home/') }}" class="btn btn-default">Назад</a>
            </div>
        </div>
    </div>
@endsection