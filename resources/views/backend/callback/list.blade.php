@extends('layouts.backend')

@section('title', 'Медицинские учреждения')

@include('homemenu')

@section('content')
    <div class="container-fluid">
        <h1>Медицинские учреждения</h1>
        @include('backend.common.form.contextmessages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-info" role="alert">
                            <strong>Ни одного элемента не добавлено.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection