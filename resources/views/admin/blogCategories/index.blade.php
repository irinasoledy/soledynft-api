@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Blog Categories</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Blog Categories </h3>
</div>

<div id="cover">

    <blog-categories-add-new :langs="{{ $langs }}"></blog-categories-add-new>

    <blog-categories :langs="{{ $langs }}"></blog-categories>

</div>
<script src="{{asset('/soledy/admin/js/app_admin.js?'.uniqid())}}"></script>
<link rel="stylesheet" href="{{ asset('admin/css/nestable.css') }}">
@stop
