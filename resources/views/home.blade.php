@extends('layouts.backend')

@section('title', 'Административная панель')

@section('backendmenu')
    @if (Auth::user()->hasRole('Administrator'))
        <li><a href="{{ url('/hospitals/') }}"><i class="fa fa-hospital-o" aria-hidden="true"></i> Медицинские учреждения</a> </li>
        <li><a href="{{ url('/prices/') }}"><i class="fa fa-rub" aria-hidden="true"></i> Прайс-лист</a></li>
    @endif

    @if (Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Operator'))
        <li><a href="{{ url('/callback/') }}"><i class="fa fa-mobile" aria-hidden="true"></i> Заявки на обратный звонок</a></li>
    @endif

    @if (Auth::user()->hasRole('Administrator'))
        <li>
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Справочники <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('/reference/research/') }}">Исследования</a></li>
            </ul>
        </li>
        <li>
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Администрирование <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('/users/') }}">Пользователи</a></li>
                <li><a href="{{ url('/pages/') }}">Статические страницы</a></li>
            </ul>
        </li>
    @endif


@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
