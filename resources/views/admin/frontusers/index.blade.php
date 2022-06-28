@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Front Users</li>
    </ol>
</nav>

<div class="title-block">
    <h3 class="title"> Front Users </h3>
    @include('admin.list-elements', [
    'actions' => [
            'Add new user' => route('frontusers.create'),
        ]
    ])
</div>

<div class="list-content">
    <div class="tab-area">
        @include('admin.alerts')
    </div>
    @if(!$users->isEmpty())
    <div class="card">
        <div class="card-block">
            <table class="table table-hover table-striped" id="tablelistsorter">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Change Password</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr id="{{ $user->id }}">
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $user->name ?? trans('variables.another_name') }}
                        </td>
                        <td>
                            {{ $user->email ?? trans('variables.another_name') }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('frontusers.edit', $user->id) }}">
                            <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('frontusers.editPassword', $user->id) }}">
                            <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td class="destroy-element">
                            <form action="{{ route('frontusers.destroy', $user->id) }}" method="post">
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
</div>
@stop
@section('footer')
<footer>
    @include('admin.footer')
</footer>
@stop
