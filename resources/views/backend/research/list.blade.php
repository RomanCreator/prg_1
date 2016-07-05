@extends('layouts.backend')

@section('title', 'Исследования')

@include('homemenu')

@section('content')
    <div class="container-fluid">
        <h1>Исследования</h1>
        @include('backend.common.form.contextmessages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @can('add', new App\Research())
                        <a class="btn btn-default" href="{{ url('/home/research/create/') }}" data-toggle="tooltip" data-placement="bottom" title="Добавить новое исследование">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                        @endcan
                    </div>
                    <div class="panel-body">
                        @if ($list->count() != 0)
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название исследования</th>
                                <th class="column_text-right">Опции</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $item)
                                <tr>
                                    <td>
                                        @if($item->state == 0)
                                            <span class="label label-danger">{{ $item->id }}</span>
                                        @else
                                            <span class="label label-success">{{ $item->id }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td class="column_text-right">
                                        @can('edit', new App\Research())
                                        <a class="btn btn-info" href="{{ url('/home/research/'.$item->id.'/edit/') }}" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Редактировать</a>
                                        @endcan
                                        @cannot('edit', new App\Research())
                                        @can('view', new App\Research())
                                        <a class="btn btn-info" href="{{ url('/home/research/'.$item->id.'/') }}"><i class="fa fa-eye" aria-hidden="true"></i> Показать</a>
                                        @endcan
                                        @endcannot
                                        @can('delete', new App\Research())
                                        <form action="{{ url('/home/research/'.$item->id.'/') }}" method="POST" class="form_action">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button class="btn btn-danger" type="submit" data-toggle="countdown"><i class="fa fa-trash-o" aria-hidden="true"></i> Удалить</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <?php echo $list->render(); ?>
                        @else
                            <div class="alert alert-info" role="alert">
                                <strong>К сожлению не добавлено ни одного исследования</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection