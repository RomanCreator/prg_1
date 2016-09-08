@extends('layouts.backend')

@section('title', 'Телефоны Центра МРТ')

@include('homemenu')

@section('content')
    <div class="container-fluid">
        <h1>Телефоны Центра МРТ</h1>
        <div class="alert alert-warning">
            <strong>Внимание!</strong> На внешней части сайта отображается только первый номер в списке номеров.
        </div>
        @include('backend.common.form.contextmessages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @can('add', new App\CallCenterPhoneNumber())
                            <a class="btn btn-default" href="{{ url('/home/callcenternumbers/create/') }}" data-toggle="tooltip" data-placement="bottom" title="Добавить новый телефон">
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
                                    <th>Телефон</th>
                                    <th class="column_text-right">Опции</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $item)
                                    <tr>
                                        <td>
                                            {{ $item->id }}
                                        </td>
                                        <td>
                                            {{ $item->number }}
                                        </td>
                                        <td class="column_text-right">
                                            @can('edit', new App\CallCenterPhoneNumber())
                                                <a class="btn btn-info" href="{{ url('/home/callcenternumbers/'.$item->id.'/edit/') }}" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Редактировать</a>
                                            @endcan
                                            @cannot('edit', new App\CallCenterPhoneNumber())
                                                @can('view', new App\CallCenterPhoneNumber())
                                                    <a class="btn btn-info" href="{{ url('/home/callcenternumbers/'.$item->id.'/') }}"><i class="fa fa-eye" aria-hidden="true"></i> Показать</a>
                                                @endcan
                                            @endcannot
                                            @can('delete', new App\CallCenterPhoneNumber())
                                                <form action="{{ url('/home/callcenternumbers/'.$item->id.'/') }}" method="POST" class="form_action">
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