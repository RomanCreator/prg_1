@extends('layouts.backend')

@section('title', 'Права доступа')

@include('homemenu')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @can('add', new App\RolePermission())
                        <a class="btn btn-default" href="{{ url('/home/permission/add/') }}" data-toggle="tooltip" data-placement="bottom" title="Добавить право доступа">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Роль</th>
                                <th>Сущность</th>
                                <th>Действие</th>
                                <th>Опции</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->rp_id }}</td>
                                    <td>{{ $item->rp_role_name }}</td>
                                    <td>{{ $item->rp_entity_name }}</td>
                                    <td>{{ $item->rp_action }}</td>
                                    <td>
                                        <a class="btn btn-info" href="{{ url('/home/permission/edit/'.$item->rp_id.'/') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Редактировать</a>
                                        <a class="btn btn-danger" href="{{ url('/home/permission/delete/'.$item->rp_id.'/') }}"><i class="fa fa-trash-o" aria-hidden="true"></i> Удалить</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection