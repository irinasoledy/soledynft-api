@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="contactContent cartClass">
    <div class="container">
        <div class="row">

            <div class="col-12">
                <h3>{{ $page->translation->title }}</h3>
            </div>
        </div>
    </div>
    <div class="container shippSize cke">
        <div class="row" style="margin-top: 30px;">
            <div class="col-12">
                {!! $page->translation->body !!}
            </div>
        </div>
    </div>
</main>
@include('front.partials.footer')
@stop
