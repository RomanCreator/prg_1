@extends('layouts.backend')

@section('title', 'Административная панель')

@section('backendmenu')
    @if (Auth::user()->hasRole('Administrator'))
        <li><a href="{{ url('/home/hospitals/') }}"><i class="fa fa-hospital-o" aria-hidden="true"></i> Медицинские учреждения</a> </li>
        <li><a href="{{ url('/home/prices/') }}"><i class="fa fa-rub" aria-hidden="true"></i> Прайс-лист</a></li>
    @endif

    @if (Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Operator'))
        <li><a href="{{ url('/home/callback/') }}"><i class="fa fa-mobile" aria-hidden="true"></i> Заявки на обратный звонок</a></li>
    @endif

    @if (Auth::user()->hasRole('Administrator'))
        <li>
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Справочники <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('/home/reference/research/') }}">Исследования</a></li>
            </ul>
        </li>
        <li>
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Администрирование <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ url('/home/users/') }}">Пользователи</a></li>
                <li><a href="{{ url('/home/pages/') }}">Статические страницы</a></li>
                <li><a href="{{ url('/home/roles/') }}">Роли пользователей</a></li>
                @can('listModel', new App\RolePermission())
adasdas
                @endcan
                <li><a href="{{ url('/home/permissions/') }}">Права доступа</a></li>
            </ul>
        </li>
    @endif


@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

        </div>
    </div>
</div>
@endsection
