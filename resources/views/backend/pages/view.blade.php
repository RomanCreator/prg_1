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
                    <label class="col-sm-3">Относительный путь</label>
                    <div class="col-sm-9">
                        <a href="{{ $path }}" target="_blank">{{ $path }}</a>
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Заголовок страницы</label>
                    <div class="col-sm-9">
                        {{ isset($title) ? $title : '' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Ключевые слова</label>
                    <div class="col-sm-9">
                        {{ isset($keywords) ? $keywords : '' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Краткое описание</label>
                    <div class="col-sm-9">
                        {{ isset($description) ? $description : '' }}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Контент страницы</label>
                    <div class="col-sm-9">
                        {!! isset($content) ? $content : '' !!}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Создана</label>
                    <div class="col-sm-9">
                        <strong>{{ date('H:i:s d.m.Y', strtotime($created_at)) }}</strong>
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Обновлена</label>
                    <div class="col-sm-9">
                        <strong>{{ date('H:i:s d.m.Y', strtotime($updated_at)) }}</strong>
                    </div>
                </div>

                <a href="{{ isset($controllerPathList) ? $controllerPathList : url('/home/') }}" class="btn btn-default">Назад</a>
            </div>
        </div>
    </div>
@endsection