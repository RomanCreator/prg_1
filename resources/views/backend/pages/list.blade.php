@extends('layouts.backend')

@section('title', 'Статические страницы')

@include('homemenu')

@section('content')
    <div class="container-fluid">
        <h1>Статические страницы</h1>
        @include('backend.common.form.contextmessages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @can('add', new App\StaticPage())
                        <a class="btn btn-default" href="{{ url('/home/pages/create/') }}" data-toggle="tooltip" data-placement="bottom" title="Добавить новую статическую страницу">
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
                                <th>Относительный путь</th>
                                <th>Заголовок страницы</th>
                                <th class="column_text-right">Опции</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->path }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td class="column_text-right">
                                        @can('edit', new App\StaticPage())
                                        <a class="btn btn-info" href="{{ url('/home/pages/'.$item->id.'/edit/') }}" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Редактировать</a>
                                        @endcan
                                        @cannot('edit', new App\StaticPage())
                                        @can('view', new App\StaticPage())
                                        <a class="btn btn-info" href="{{ url('/home/pages/'.$item->id.'/') }}"><i class="fa fa-eye" aria-hidden="true"></i> Показать</a>
                                        @endcan
                                        @endcannot
                                        @can('delete', new App\StaticPage())
                                        <form action="{{ url('/home/pages/'.$item->id.'/') }}" method="POST" class="form_action">
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
                        @else
                            <div class="alert alert-info" role="alert">
                                <strong>Ни одного элемента не добавлено.</strong>
                            </div>
                        @endif
                        <?php echo $list->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection