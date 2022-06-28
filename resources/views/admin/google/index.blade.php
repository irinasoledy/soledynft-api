@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="gallery">Google API </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Google API </h3>
</div>

<div class="alert alert-warning text-center alert-block">
    Please wait..<br>
    do not reload the page until the process is completed
    <div class="">
        <img src="/soledy/admin/4V0b.gif">
    </div>
</div>

<div class="card card-block">
    <div class="row">
        <div class="col-md-12">
            <h6>Export Data:</h6>
        </div>
        <div class="col-md-2">
            <label for="">Step 1</label>
            <a href="{{ url('/back/google-api/get-categories-id') }}" class="btn btn-primary btn-block" target="_blank">Get categories id</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 2</label>
            <a href="{{ url('/back/google-api/get-parameters-id') }}" class="btn btn-primary btn-block"  target="_blank">Get parameters id</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 3</label>
            <a href="{{ url('/back/google-api/get-subproducts-id') }}" class="btn btn-primary btn-block" target="_blank">Get Subproducts id</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 4</label>
            <a href="{{ url('/back/google-api/get-trans-data') }}" class="btn btn-primary btn-block" target="_blank">Get Trans data</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 5</label>
            <a href="{{ url('/back/google-api/get-brands') }}" class="btn btn-primary btn-block" target="_blank">Get Brands</a>
        </div>
    </div>
    <hr> <hr>
    <div class="row">
        <div class="col-md-12">
            <h6>Handlle Images:</h6>
        </div>
        <div class="col-md-2">
            <label for="">Handle Products Images</label>
            <a href="/back/handle-products-images" class="btn btn-primary btn-block run-alert" onclick="return confirm('Are you sure you want to handle all images?');">
                Run  <span class="badge">{{ count(\File::files(public_path('images/products/uploads'))) }}</span>
            </a>
        </div>
        <div class="col-md-2">
            <label for="">Handle Collections Images</label>
            <a href="/back/handle-collections-images" class="btn btn-primary btn-block run-alert" onclick="return confirm('Are you sure you want to handle all images?');">
                Run  <span class="badge">{{ count(\File::files(public_path('images/collections/uploads'))) }}</span>
            </a>
        </div>
        <div class="col-md-2">
            <label for="">Handle Sets Images</label>
            <a href="/back/handle-sets-images" class="btn btn-primary btn-block run-alert" onclick="return confirm('Are you sure you want to handle all images?');">
                Run  <span class="badge">{{ count(\File::files(public_path('images/sets/uploads'))) }}</span>
            </a>
        </div>
        <div class="col-md-2">
            <label for="">Handle Promotions Images</label>
            <a href="/back/handle-promo-images" class="btn btn-primary btn-block run-alert" onclick="return confirm('Are you sure you want to handle all images?');">
                Run  <span class="badge">{{ count(\File::files(public_path('images/promotions/uploads'))) }}</span>
            </a>
        </div>
    </div>
    <hr><hr>
    <div class="row">
        <div class="col-md-12">
            <h6>Upload Products:</h6>
        </div>
        <div class="col-md-2">
            <label for="">Step 1</label>
            <a href="{{ url('/back/google-api/upload-products') }}" class="btn btn-primary btn-block">Upload Products</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 2</label>
            <a href="{{ url('/back/google-api/upload-parameters') }}" class="btn btn-primary btn-block">Upload Parameters</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 3</label>
            <a href="{{ url('/back/google-api/upload-prices') }}" class="btn btn-primary btn-block">Upload Prices</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 4</label>
            <a href="{{ url('/back/google-api/upload-stocks') }}" class="btn btn-primary btn-block">Upload Stocks</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 5</label>
            <a href="{{ url('/back/google-api/upload-images') }}" class="btn btn-primary btn-block">Upload Images</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 6</label>
            <a href="{{ url('/back/google-api/upload-translations') }}" class="btn btn-primary btn-block">Upload Translation</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h6>Upload Collections:</h6>
        </div>
        <div class="col-md-2">
            <label for="">Step 1</label>
            <a href="{{ url('/back/google-api/upload-collections') }}" class="btn btn-primary btn-block">Upload Colections</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 2</label>
            <a href="{{ url('/back/google-api/upload-sets') }}" class="btn btn-primary btn-block">Upload Sets</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 3</label>
            <a href="{{ url('/back/google-api/add-products-to-sets') }}" class="btn btn-primary btn-block">Add Products to Sets</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <h6>Upload Promotions:</h6>
        </div>
        <div class="col-md-2">
            <label for="">Step 1</label>
            <a href="{{ url('/back/google-api/upload-promotions') }}" class="btn btn-primary btn-block">Upload Promos</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 2</label>
            <a href="{{ url('/back/google-api/add-products-to-promos') }}" class="btn btn-primary btn-block">Add Prods to Promos</a>
        </div>
        <div class="col-md-2">
            <label for="">Step 3</label>
            <a href="{{ url('/back/google-api/add-sets-to-promos') }}" class="btn btn-primary btn-block">Add Sets to Promos</a>
        </div>
    </div>
</div>

@stop
@section('footer')
<footer>
    @include('admin.footer')
</footer>

<style media="screen">
    .alert-block{
        display: none;
        position: relative;
    }
    .alert-block img{
        width: 50px;
        position: absolute;
        right: 10px;
        top: 10px;
    }
</style>
<script>
    $('.run-alert').on('click', function(){
        $('.alert-block').show();
    });
</script>
@stop
