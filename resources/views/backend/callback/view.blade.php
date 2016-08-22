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
                    <label class="col-sm-3">Клиент</label>
                    <div class="col-sm-9">
                        {{ $name }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Телефон</label>
                    <div class="col-sm-9">
                        {{ $phone }}
                    </div>
                </div>

                @if ($message)
                <div class="row">
                    <label class="col-sm-3">Сообщение</label>
                    <div class="col-sm-9">
                        {{ $message }}
                    </div>
                </div>
                @endif

                @if ($research)
                <div class="row">
                    <label class="col-sm-3">Исследование</label>
                    <div class="col-sm-9">
                        {{ $research->name }}
                    </div>
                </div>
                @endif

                @if ($hospital)
                <div class="row">
                    <label class="col-sm-3">Медицинское учреждение</label>
                    <div class="col-sm-9">
                        {{ $hospital->name }}
                    </div>
                </div>
                @endif

                <div class="row">
                    <label class="col-sm-3">Текущий статус</label>
                    <div class="col-sm-9">
                        {{ $status }}
                    </div>
                </div>

                <a href="{{ isset($controllerPathList) ? $controllerPathList : url('/home/') }}" class="btn btn-default">Назад</a>
            </div>
        </div>
    </div>
@endsection