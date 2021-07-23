@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="promo">
    <div class="titlePage">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>{{ trans('vars.PagesNames.pagePromotions') }} {{ $promotion->translation->name }}</h3>
                </div>
            </div>
        </div>
    </div>

    <section>
        @if ($promotion->img)
            <img src="/images/promotions/{{ $promotion->img }}" alt="{{ $promotion->translation->name }}">
        @endif
        <div class="container">
            <div class="row promoInner">
                <div class="col-12">
                    <div class="promoTitle">
                        {{ trans('vars.DetailsProductSet.productsFrom') }} {{ $promotion->translation->name }}
                    </div>
                </div>
                @if ($promotion->products->count() > 0)
                <div class="container">
                    @if ($promotion->products)
                    <div class="row">
                        <div class="col-12" style="padding:0">
                            <div style="display:block">
                                <div class="additional">
                                    @foreach ($promotion->products as $key => $product)
                                    <div class="col-xl-12">
                                        <div class="oneProduct">
                                            <div class="addToWish"></div>
                                            <a href="{{ url('/' . $lang->lang . '/' . $product->product->type .'/catalog/' . $product->product->category->alias. '/'. $product->product->alias) }}" class="imgBlock">
                                                @if ($product->product->mainImage)
                                                    <img src="/images/products/md/{{ $product->product->mainImage->src }}"/>
                                                @else
                                                    <img src="/images/no-image-ap.jpg"/>
                                                @endif
                                            </a>
                                            <a href="{{ url('/' . $lang->lang . '/' . $product->product->type .'/catalog/' . $product->product->category->alias. '/'. $product->product->alias) }}">{{ $product->product->translation->name }}</a>
                                            <div class="price">
                                                <span>
                                                    {{ $product->product->personalPrice->price }}
                                                    @if ($product->product->personalPrice->old_price == $product->product->personalPrice->price)
                                                        {{ $currency->abbr }}
                                                    @endif
                                                </span>
                                                @if ($product->product->personalPrice->old_price !== $product->product->personalPrice->price)
                                                    <span>{{ $product->product->personalPrice->old_price }} {{ $currency->abbr }}</span>
                                                @else
                                                    <span></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </section>

</main>
@include('front.partials.footer')
@stop
