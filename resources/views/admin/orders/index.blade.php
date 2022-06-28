@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item" aria-current="collection"><a href="{{ url('/back/crm-orders-list') }}">Orders</a></li>
        <li class="breadcrumb-item active" aria-current="collection">Orders </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Orders </h3>
    @include('admin.list-elements', [
    'actions' => []
    ])
</div>


<div id="cover">

    <div class="card">
        <div class="card-block">

            <order-search></order-search>

        </div>
    </div>

    <div class="card">
        <div class="card-block">
            <crm-shipping></crm-shipping>
        </div>
    </div>

    <div class="card">
        <div class="card-block">
            <crm-payment></crm-payment>
        </div>
    </div>

</div>

<script src="{{asset('admin/js/app_admin.js?'.uniqid())}}"></script>
@stop
