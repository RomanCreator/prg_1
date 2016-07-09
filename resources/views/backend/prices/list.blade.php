@extends('layouts.backend')

@section('title', 'Прайс лист')

@include('homemenu')

@section('content')
    <div class="container-fluid">
        <h1>Прайс лист</h1>
        @include('backend.common.form.contextmessages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @can('add', new App\Price())
                        <a class="btn btn-default" href="{{ url('/home/prices/create/') }}" data-toggle="tooltip" data-placement="bottom" title="Добавить элемент прайс листа">
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
                                    <th>Название учреждения</th>
                                    <th>Название исследования</th>
                                    <th>Цена (от-до)</th>
                                    <th class="column_text-right">Опции</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $item)
                                        <tr>
                                            <td>
                                                @if($item->status == 0)
                                                    <span class="label label-danger">{{ $item->id }}</span>
                                                @else
                                                    <span class="label label-success">{{ $item->id }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $item->hospital->name }}
                                            </td>
                                            <td>
                                                {{ $item->research->name }}
                                            </td>
                                            <td>
                                                {{ $item->price_from }}
                                                @if(!is_null($item->price_to))
                                                    - {{ $item->price_to }}
                                                @endif

                                                руб.
                                            </td>
                                            <td class="column_text-right">
                                                @can('edit', new App\Price())
                                                <a class="btn btn-info" href="{{ url('/home/prices/'.$item->id.'/edit/') }}" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Редактировать</a>
                                                @endcan
                                                @cannot('edit', new App\Price())
                                                @can('view', new App\Price())
                                                <a class="btn btn-info" href="{{ url('/home/prices/'.$item->id.'/') }}"><i class="fa fa-eye" aria-hidden="true"></i> Показать</a>
                                                @endcan
                                                @endcannot
                                                @can('delete', new App\Price())
                                                <form action="{{ url('/home/prices/'.$item->id.'/') }}" method="POST" class="form_action">
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
                                <strong>Ни одного элемента не добавлено.</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection