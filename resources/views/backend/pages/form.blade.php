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
                      action="{{ url('/home/pages/ ') }}"
                      @endif
                      @if ($controllerAction === 'edit')
                      action="{{ url('/home/pages/'.$idEntity.'/') }}"
                        @endif
                >
                    {{ csrf_field() }}
                    @if ($controllerAction === 'edit')
                        {{ method_field('PUT') }}
                    @endif

                    <div class="form-group">
                        <label for="path" class="col-sm-3 control-label">Относительный путь к странице</label>
                        <div class="col-sm-9">
                            <input name="path" id="path" class="form-control" maxlength="255" value="{{ isset($path) ? $path : '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">Заголовок страницы</label>
                        <div class="col-sm-9">
                            <input name="title" id="title" class="form-control" maxlength="255" value="{{ isset($title) ? $title : '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keywords" class="col-sm-3 control-label">Ключевые слова с разделителем ","</label>
                        <div class="col-sm-9">
                            <input name="keywords" id="keywords" class="form-control" maxlength="255" value="{{ isset($keywords) ? $keywords : '' }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Краткое описание содержания страницы</label>
                        <div class="col-sm-9">
                            <textarea name="description"
                                      id="description"
                                      class="form-control"
                                      rows="5"
                                      maxlength="255">{{ isset($description) ? $description : '' }}</textarea>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="content" class="col-sm-3 control-label">Контент страницы</label>
                        <div class="col-sm-9">
                            <textarea name="content"
                                      id="content"
                                      class="form-control"
                                      rows="10" data-toggle="ckeditor">{!! isset($content) ? $content : '' !!}</textarea>
                        </div>
                    </div>

                    @if ($controllerAction === 'edit')
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Создана</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" readonly value="{{ date('H:i:s d.m.Y', strtotime($created_at)) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Обновлена</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" readonly value="{{ date('H:i:s d.m.Y', strtotime($updated_at)) }}">
                            </div>
                        </div>
                    @endif

                    @include('backend.common.form.action')
                </form>
@endsection