@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('logs.index') }}">Logs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Log details</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Log details </h3>

</div>
@include('admin.alerts')

<div class="list-content">
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Problem:</label>
                        <input type="text" class="form-control" value="{{ $log->problem }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Date:</label>
                        <input type="text" class="form-control" value="{{ $log->date }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">User Cookie Id:</label>
                        <input type="text" class="form-control" value="{{ $log->user_id }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">User Email:</label>
                        <input type="text" class="form-control" value="{{ $log->user_email }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Language:</label>
                        <input type="text" class="form-control" value="{{ $log->lang }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Country:</label>
                        <input type="text" class="form-control" value="{{ $log->country }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Currency:</label>
                        <input type="text" class="form-control" value="{{ $log->currency }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Device:</label>
                        <input type="text" class="form-control" value="{{ $log->device }}" disabled>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="">File:</label>
                        <input type="text" class="form-control" value="{{ $log->file }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Class:</label>
                        <input type="text" class="form-control" value="{{ $log->controller }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Method:</label>
                        <input type="text" class="form-control" value="{{ $log->method }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="">Referer:</label>
                        <input type="text" class="form-control" value="{{ $log->referrer }}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('footer')
<footer>
    @include('admin.footer')
</footer>
@stop
