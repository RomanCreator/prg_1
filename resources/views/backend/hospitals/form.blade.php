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
                        <label for="district" class="col-sm-3 control-label">Район города</label>
                        <div class="col-sm-9">
                            <select name="district" class="form-control" id="district">
                                @if($districts)
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ $district->selected }}>{{ $district->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="worktime" class="col-sm-3 control-label">Время работы</label>
                        <div class="col-sm-9">
                            <input type="text" name="worktime" class="form-control" data-toggle="weekwork" id="worktime" value="{{isset($worktime) ? $worktime : ''}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tags" class="control-label col-sm-3">Теги (через , )</label>
                        <div class="col-sm-9">
                            <input type="text" name="tags" class="form-control" id="tags" value="{{ isset($tags) ? $tags : '' }}">
                        </div>
                    </div>

                    @if (isset($typeResearches))
                    <div class="form-group">
                        <label class="control-label col-sm-3">Типы диагностики</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                @foreach($typeResearches as $typeResearch)
                                    <label class="btn btn-default {{ $typeResearch->active }}">
                                        <input type="checkbox" autocomplete="off" name="type_researches[]" value="{{ $typeResearch->id }}" {{ isset($typeResearch->active) ? 'checked' : '' }}>
                                        {{ $typeResearch->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type_researches_price" class="control-label col-sm-3">Базовые цены на типы исследований (через ",")</label>
                        <div class="col-sm-9">
                            <input type="text" name="type_researches_price" class="form-control" id="type_researches_price" value="{{ isset($type_researches_price) ? $type_researches_price : '' }}">
                        </div>
                    </div>
                    @endif

                    @if (isset($tomographTypes))
                    <div class="form-group">
                        <label class="control-label col-sm-3">Типы томографов</label>
                        <div class="col-sm-9">
                            <div class="btn-group" data-toggle="buttons">
                                @foreach($tomographTypes as $tomographType)
                                    <label class="btn btn-default {{ $tomographType->active }}">
                                        <input type="checkbox" autocomplete="off" name="tomograph_types[]" value="{{ $tomographType->id }}" {{ isset($tomographType->active) ? 'checked' : '' }}>
                                        {{ $tomographType->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="therapeutic_areas" class="control-label col-sm-3">Лечебные направления (через ",")</label>
                        <div class="col-sm-9">
                            <input type="text" name="therapeutic_areas" class="form-control" id="therapeutic_areas" value="{{ isset($therapeutic_areas) ? $therapeutic_areas : '' }}" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="doctor_price" class="control-label col-sm-3">Прием врача от (руб.)</label>
                        <div class="col-sm-9">
                            <input type="text" name="doctor_price" class="form-control" id="doctor_price" value="{{isset($doctor_price) ? $doctor_price : ''}}">
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