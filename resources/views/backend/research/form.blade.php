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
                      action="{{ url('/home/research/ ') }}"
                      @endif
                      @if ($controllerAction === 'edit')
                      action="{{ url('/home/research/'.$idEntity.'/') }}"
                      @endif
                      enctype="multipart/form-data"
                >
                    {{ csrf_field() }}
                    @if ($controllerAction === 'edit')
                        {{ method_field('PUT') }}
                    @endif

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Название исследования</label>
                        <div class="col-sm-9">
                            <input name="name" id="name" class="form-control" value="{{ isset($name) ? $name : '' }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diagram" class="col-sm-3 control-label">Диаграмма исследования</label>
                        <div class="col-sm-9">
                            <input name="diagram" id="diagram" type="file" data-toggle="imagepicker" data-src="{{ isset($diagram) ? $diagram : '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Описание исследлования</label>
                        <div class="col-sm-9">
                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      rows="10" data-toggle="ckeditor">{!! isset($description) ? $description : '' !!}</textarea>
                        </div>
                    </div>

                    @if ($controllerAction === 'edit')
                        <div class="form-group">
                            <label for="gallery" class="col-sm-3 control-label">Галерея фотографий</label>
                            <div class="col-sm-9">
                                <input name="gallery[]" id="gallery" type="file" data-toggle="imagepickermult" accept="image/*" multiple
                                       data-upload-images="@foreach($gallery as $gal){{ $gal }},@endforeach"
                                       data-upload-images-orig="@foreach($gallerySrc as $gal){{ $gal }},@endforeach"
                                >
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Отображать на внутренних страницах</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default
                                @if ($showState)
                                        active
                                @endif
                                ">
                                <input type="checkbox" autocomplete="off" name="show_state" value="1"
                                @if($showState)
                                    checked
                                @endif
                                > <i class="fa fa-power-off" aria-hidden="true"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Позиция в списке на внутренних страницах</label>
                        <div class="col-sm-9">
                            <input name="show_position" type="text" class="form-control" value="{{ isset($show_position) ? $show_position : '' }}">
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="state" class="col-sm-3 control-label">Статус исследования</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default
                                @if (isset($state) && $state)
                                        active
                                @endif
                                ">
                                    <input type="checkbox" autocomplete="off" name="state" value="1"
                                    @if (isset($state) && $state)
                                        checked
                                    @endif
                                    > <i class="fa fa-power-off" aria-hidden="true"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    @if ($controllerAction === 'edit')
                        <!--Тут форма создания и редактирования прикрепленных страниц к исследованию-->
                    @endif

                    @include('backend.common.form.action')
                </form>
            </div>
        </div>
    </div>
@endsection