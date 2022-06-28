@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Static Pages</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Static Pages </h3>
    @include('admin.list-elements', [
        'actions' => [
            'Add new' => route('static-pages.create'),
            'Google Sheet Synchronize' => url('/back/static-pages/google-api/synchronize'),
        ]
    ])
</div>
@include('admin.alerts')
@if(!$pages->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Alias</th>
                    <th>Active</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $key => $page)
                <tr id="{{ $page->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $page->translation()->first()->name }}
                    </td>
                    <td>
                        <small>{{ $page->alias }}</small>
                    </td>
                    <td>
                        @if ($page->active == 1)
                            <span class="label label-primary">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('static-pages.edit', $page->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('static-pages.destroy', $page->id) }}" method="post">
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
                    <td colspan=7></td>
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
