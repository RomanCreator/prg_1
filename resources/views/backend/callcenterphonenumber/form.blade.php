@extends('layouts.backend')

@section('title')
    {{ $nameAction }}
@endsection

@include('homemenu')

@section('content')
    <div class="container-fluid">
        <h1>{{ $nameAction }}</h1>
        @include('backend.common.form.contextmessages')
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-horizontal" method="POST"
                      @if ($controllerAction === 'add')
                      action="{{ url('/home/callcenternumbers/ ') }}"
                      @endif
                      @if ($controllerAction === 'edit')
                      action="{{ url('/home/callcenternumbers/'.$idEntity.'/') }}"
                        @endif
                >
                    {{ csrf_field() }}
                    @if ($controllerAction === 'edit')
                        {{ method_field('PUT') }}
                    @endif
                    <div class="alert alert-info">
                        <strong>Внимание!</strong> На фронтенде отображается только один самый первый телефон в списке.
                    </div>
                    <div class="form-group">
                        <label for="number" class="col-sm-3 control-label">Номер телефона</label>
                        <div class="col-sm-9">
                            <input name="number" id="number" class="form-control" value="{{ isset($number) ? $number : '' }}" required>
                        </div>
                    </div>

                    @include('backend.common.form.action')
                </form>
            </div>
        </div>
    </div>
@endsection