@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="categoryContent">
    <div class="container">
        @if ($site == 'homewear')
            <h3>{{ trans('vars.General.homewearCatalog') }}</h3>
        @else
            <h3>{{ trans('vars.General.bijouxCatalog') }}</h3>
        @endif
        <div class="row">
            @foreach ($products as $key => $product)
                @if ($product->mainImage)
                    <div class="col-lg-3 col-md-6">
                        <div class="oneCateProd">
                            <div class="oneProduct">
                                <div class="addToWish"></div>
                                <a class="imgBlock" href="{{ url('/'. $lang->lang . '/'. $product->type . '/catalog/' . $product->category->alias . '/' . $product->alias) }}">
                                    @if ($product->mainImage)
                                        <img src="/images/products/og/{{ $product->mainImage->src }}"/>
                                    @endif
                                </a>
                                <a href="{{ url('/'. $lang->lang . '/'. $product->type . '/catalog/' . $product->category->alias . '/' . $product->alias) }}">{{ $product->translation->name }}</a>
                                <div class="price">
                                    @if ($product->personalPrice->price == $product->personalPrice->old_price)
                                        <span>
                                            {{ $product->personalPrice->price }}
                                            <span>{{ $currency->abbr }}</span>
                                        </span>
                                    @else
                                        <span>
                                            {{ $product->personalPrice->price }}
                                        </span> <span></span> /
                                        <span>
                                             {{ $product->personalPrice->old_price }}
                                        </span>
                                        <span>{{ $currency->abbr }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach
        </div>
    </div>
</main>
@include('front.partials.footer')
@stop
