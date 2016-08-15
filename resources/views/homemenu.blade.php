@section('backendmenu')
    @if (Auth::user()->hasRole('Administrator'))
        @can('index', new App\Hospital())
        <li><a href="{{ url('/home/hospitals/') }}"><i class="fa fa-hospital-o" aria-hidden="true"></i> Медицинские учреждения</a> </li>
        @endcan
        @can('index', new App\Price())
        <li><a href="{{ url('/home/prices/') }}"><i class="fa fa-rub" aria-hidden="true"></i> Прайс-лист</a></li>
        @endcan
    @endif

    @if (Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Operator'))
        <li><a href="{{ url('/home/callback/') }}"><i class="fa fa-mobile" aria-hidden="true"></i> Заявки на обратный звонок</a></li>
    @endif

    @if (Auth::user()->hasRole('Administrator'))
        <li>
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-book" aria-hidden="true"></i> Справочники <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                @can('index', new App\TomographType())
                    <li><a href="{{ url('/home/tomograph_types/') }}"><i class="fa fa-object-group" aria-hidden="true"></i> Типы томографов</a></li>
                @endcan
                @can('index', new App\TypeResearch())
                <li><a href="{{ url('/home/type_researches/') }}"><i class="fa fa-code-fork" aria-hidden="true"></i> Типы исследований</a></li>
                @endcan
                @can('index', new App\Research())
                <li><a href="{{ url('/home/research/') }}"><i class="fa fa-heartbeat" aria-hidden="true"></i> Исследования</a></li>
                @endcan
                @can('index', new App\District())
                <li><a href="{{ url('/home/districts/') }}"><i class="fa fa-map-signs" aria-hidden="true"></i> Районы города</a> </li>
                @endcan
            </ul>
        </li>
        <li>
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cogs" aria-hidden="true"></i> Администрирование <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                @can('index', new App\User())
                <li><a href="{{ url('/home/users/') }}"><i class="fa fa-users" aria-hidden="true"></i> Пользователи</a></li>
                @endcan

                @can('index', new App\StaticPage())
                <li><a href="{{ url('/home/pages/') }}"><i class="fa fa-file-text-o" aria-hidden="true"></i> Статические страницы</a></li>
                @endcan

                @can('index', new App\Role())
                <li><a href="{{ url('/home/roles/') }}"><i class="fa fa-user-secret" aria-hidden="true"></i> Роли пользователей</a></li>
                @endcan

                @can('index', new App\RolePermission())
                <li><a href="{{ url('/home/permissions/') }}"><i class="fa fa-user-times" aria-hidden="true"></i> Права доступа</a></li>
                @endcan
            </ul>
        </li>
    @endif
@endsection