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
                      action="{{ url('/home/hospitals/ ') }}"
                      @endif
                      @if ($controllerAction === 'edit')
                      action="{{ url('/home/hospitals/'.$idEntity.'/') }}"
                      @endif
                      enctype="multipart/form-data"
                >
                    {{ csrf_field() }}
                    @if ($controllerAction === 'edit')
                        {{ method_field('PUT') }}
                    @endif

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Название медицинского учреждения</label>
                        <div class="col-sm-9">
                            <input name="name" id="name" class="form-control" value="{{ isset($name) ? $name : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diagram" class="col-sm-3 control-label">Логотип медицинского учреждения</label>
                        <div class="col-sm-9">
                            <input name="logo" id="logo" type="file" data-toggle="imagepicker" data-src="{{ isset($logo) ? $logo : '' }}">
                        </div>
                    </div>

                    @if ($controllerAction === 'edit')
                        <div class="form-group">
                            <label for="gallery" class="col-sm-3 control-label">Галерея фотографий</label>
                            <div class="col-sm-9">
                                <input name="gallery[]" id="gallery" type="file" data-toggle="imagepickermult" accept="image/*" multiple
                                data-upload-images="@foreach($gallery as $gal){{ $gal }},@endforeach"
                                >
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Описание медицинского учреждения</label>
                        <div class="col-sm-9">
                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      rows="10" data-toggle="ckeditor" required>{!! isset($description) ? $description : '' !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">Адрес</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="address" id="address" data-toggle="ymap" data-id-modal="map" data-id-map="hosmap" data-hidden-name="technical_address" data-tech-data="{{ isset($technical_address) ? $technical_address : '' }}" value="{{ isset($address) ? $address : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-sm-3 control-label">Статус медицинского учреждения</label>
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