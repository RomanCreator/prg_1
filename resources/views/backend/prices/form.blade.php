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
                      action="{{ url('/home/prices/ ') }}"
                      @endif
                      @if ($controllerAction === 'edit')
                      action="{{ url('/home/prices/'.$idEntity.'/') }}"
                      @endif
                      enctype="multipart/form-data"
                >
                    {{ csrf_field() }}
                    @if ($controllerAction === 'edit')
                        {{ method_field('PUT') }}
                    @endif

                    <div class="form-group">
                        <label for="hospital_id" class="col-sm-3 control-label">Медицинское учреждение</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="hospital_id" id="hospital_id" required>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}" {{ $hospital->selected }}>{{ $hospital->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="research_id" class="col-sm-3 control-label">Исследование</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="research_id" id="research_id" required>
                                @foreach($researches as $research)
                                    <option value="{{ $research->id }}" {{ $research->selected }}>{{ $research->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price_from" class="col-sm-3 control-label">Цена (Цена от)</label>
                        <div class="col-sm-9">
                            <input type="text" name="price_from" id="price_from" class="form-control" value="{{ isset($price_from) ? $price_from : '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price_to" class="col-sm-3 control-label">Цена до</label>
                        <div class="col-sm-9">
                            <input type="text" name="price_to" id="price_to" class="form-control" value="{{ isset($price_to) ? $price_to : '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Описание медицинского исследования для конкретного учреждения</label>
                        <div class="col-sm-9">
                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      rows="10" data-toggle="ckeditor">{!! isset($description) ? $description : '' !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">Статус пункта прайс-листа</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default
                                @if (isset($status) && $status)
                                        active
                                @endif
                                        ">
                                    <input type="checkbox" autocomplete="off" name="status" id="status" value="1"
                                           @if (isset($status) && $status)
                                           checked
                                            @endif
                                    > <i class="fa fa-power-off" aria-hidden="true"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    @include('backend.common.form.action')
                </form>
            </div>
        </div>
    </div>
@endsection