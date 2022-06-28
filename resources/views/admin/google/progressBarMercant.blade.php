@extends('admin.google.app')
@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<div class="wrapp">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
@php
    $allProds = \App\Models\Product::where('active', 1)->where($siteType, 1)->get();
    $prods =  \App\Models\Product::where('active', 1)->where($siteType, 1)->whereIn('id', $insertedProducts)->get();
@endphp

<div class="container">
    <div class="row">
        <div class="col-12">
            @if (count($prods) == $allProds->count())
                <h3 class="text-center text-success">Success products was inserted!</h3> <br>
                <a class="text-center" href="{{url('/back/google-api-content')}}">Back</a> <br>
            @else
                <h3 class="text-center">Inserting...</h3> <br>
            @endif
        </div>
        <div class="col-12">
            <p class="text-primary text-center">was inserted <b>{{ count($insertedProducts) }}</b> from <b>{{ $allProds->count() }}</b> items.</p>
        </div>
    </div>
</div>

<div class="container">
    <div class="progress">
        <span class="sr-only">{{ count($insertedProducts) }}% Complete</span>
        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="{{ count($insertedProducts) }}" aria-valuemin="0" aria-valuemax="{{ $allProds->count()  }}" style="width: {{ count($insertedProducts) - 3 }}%;">
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            @foreach ($prods->reverse() as $key => $prod)
                <p><small>{{ $key + 1 }}. {{ $prod->translationByLang($currentLang->id)->name }}</small></p>
            @endforeach
        </div>
    </div>
</div>
    @if (count($prods) !== $allProds->count())
        <div id="continue"></div>
    @endif
</div>

<script>
$('.count').each(function () {
    $(this).prop('Counter',0).animate({
        Counter: $(this).text()
    }, {
        duration: 10000,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});

if ($('#continue')) {
    setTimeout(function(){
        console.log('refresh');
        window.location.href = "https://annepopova.com/back/google-api-content/recursive-insert/{{$siteType}}/{{$currentLang->lang}}/{{$currency}}/{{$country}}";
    }, 2000);
}

</script>

@stop
