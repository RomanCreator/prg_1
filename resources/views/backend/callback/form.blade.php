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
                      action="{{ url('/home/callback/ ') }}"
                      @endif
                      @if ($controllerAction === 'edit')
                      action="{{ url('/home/callback/'.$idEntity.'/') }}"
                      @endif
                      enctype="multipart/form-data"
                >
                    {{ csrf_field() }}
                    @if ($controllerAction === 'edit')
                        {{ method_field('PUT') }}
                    @endif

                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Имя клиента</label>
                        <div class="col-sm-9">
                            <input name="name" id="name" class="form-control" value="{{ isset($name) ? $name : '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-sm-3 control-label">Телефон клиента</label>
                        <div class="col-sm-9">
                            <input name="phone" id="phone" class="form-control" value="{{ isset($phone) ? $phone : '' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Сообщение</label>
                        <div class="col-sm-9">
                            <textarea name="message" class="form-control" readonly>{{ isset($message) ? $message : '' }}</textarea>
                        </div>
                    </div>

                    @if ($research)
                     <div class="form-group">
                         <label class="col-sm-3 control-label">Исследование</label>
                         <div class="col-sm-9">
                             <input type="text" class="form-control" value="{{ $research->name }}" readonly>
                         </div>
                     </div>
                    @endif

                    @if ($hospital)
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Медицинское учреждение</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $hospital->name }}" readonly>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Комментарии:</label>
                        <div class="col-sm-9">
                            @if ($comments)
                                @foreach($comments as $comment)
                                    <div class="well well-sm">
                                        <div><span class="label label-primary">{{ $comment['name'] }}</span></div>
                                        <p>{{ $comment['comment'] }}</p>
                                    </div>
                                @endforeach
                            @endif

                            <textarea class="form-control" name="comment"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Текущий статус:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="status">
                                <option value="">{{ $status }}</option>
                                @if ($allowedStatus)
                                    @foreach($allowedStatus as $stat)
                                        <option value="{{ $stat['value'] }}">{{ $stat['name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    @include('backend.common.form.action')
                </form>
            </div>
        </div>
    </div>
@endsection