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
                      action="{{ url('/home/tomograph_types/ ') }}"
                      @endif
                      @if ($controllerAction === 'edit')
                      action="{{ url('/home/tomograph_types/'.$idEntity.'/') }}"
                      @endif
                      enctype="multipart/form-data"
                >
                    {{ csrf_field() }}
                    @if ($controllerAction === 'edit')
                        {{ method_field('PUT') }}
                    @endif

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Название типа томографа</label>
                        <div class="col-sm-9">
                            <input name="name" id="name" class="form-control" value="{{ isset($name) ? $name : '' }}" required>
                        </div>
                    </div>

                    @include('backend.common.form.action')
                </form>
            </div>
        </div>
    </div>
@endsection