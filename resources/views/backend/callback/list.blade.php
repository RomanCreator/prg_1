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
                            <strong>К сожлению ни одной заявки не оставлено</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection