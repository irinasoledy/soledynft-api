@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Logs</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Logs </h3>
</div>
@include('admin.alerts')
@if(!$logs->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Cookie</th>
                    <th>Lang</th>
                    <th>Device</th>
                    <th>Currency</th>
                    <th>Country</th>
                    <th>Email</th>
                    <th>Problem</th>
                    <th>Date</th>
                    <th>Details</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $key => $log)
                <tr>
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td><small>{{ str_limit($log->user_id, 10) }}</small></td>
                    <td><span class="label label-success">{{ $log->lang }}</span></td>
                    <td><span class="label label-primary">{{ $log->device }}</span></td>
                    <td><span class="label label-warning">{{ $log->currency }}</span></td>
                    <td><span class="label label-danger">{{ $log->country }}</span></td>
                    <td><small>{{ $log->user_email }}</small></td>
                    <td><small><i>{{ $log->problem }}</i></small></td>
                    <td><small>{{ $log->date }}</small></td>
                    <td><a href="{{ route('logs.edit', $log->id) }}"><i class="fa fa-edit"></i></a> </td>
                    <td>
                        <form action="{{ route('logs.destroy', $log->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <button type="submit" class="btn-link">
                                <a href=""><i class="fa fa-trash"></i></a>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=20></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop
@section('footer')
<footer>
    @include('admin.footer')
</footer>
@stop
