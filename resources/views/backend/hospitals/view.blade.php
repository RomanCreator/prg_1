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
                        {{ $name }}
                    </div>
                </div>

                @if (isset($logo) && $logo)
                    <div class="row">
                        <label class="col-sm-3">Логотип учреждения</label>
                        <div class="col-sm-9">
                            <img src="{{ asset($logo) }}">
                        </div>
                    </div>
                @endif

                <div class="row">
                    <label class="col-sm-3">Описание учреждения</label>
                    <div class="col-sm-9">
                        {!! $description !!}
                    </div>
                </div>

                <div class="row">
                    <label class="col-sm-3">Статус медицинского учреждения</label>
                    <div class="col-sm-9">
                        @if($status)
                            Учреждение отображается на страницах сайта
                        @else
                            Учреждение не отображается на страницах сайта
                        @endif
                    </div>
                </div>

                <a href="{{ isset($controllerPathList) ? $controllerPathList : url('/home/') }}" class="btn btn-default">Назад</a>
            </div>
        </div>
    </div>
@endsection