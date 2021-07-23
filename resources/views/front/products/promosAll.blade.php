@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="promo">
    <div class="titlePage">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3>{{ trans('vars.PagesNames.pagePromotions') }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if ($promotions->count() > 0)
        @foreach ($promotions as $key => $promotion)
            <section>
                <a href="{{ url('/'.$lang->lang.'/promos/'.$promotion->type.'/'.$promotion->id) }}">
                    <div class="container">
                        <div class="row promoInner">
                            <div class="col-12">
                                <div class="promoTitle">
                                    {{ trans('vars.DetailsProductSet.productsFrom') }} {{ $promotion->translation->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($promotion->img)
                        <img src="/images/promotions/{{ $promotion->img }}" alt="{{ $promotion->translation->name }}">
                    @endif
                    <p>{{ $promotion->translation->description }}</p>
                    <a href="{{ url('/'.$lang->lang.'/promos/'.$promotion->type.'/'.$promotion->id) }}">{{ $promotion->translation->btn_text }}</a>
                </a>
            </section>
        @endforeach
    @endif
</main>
@include('front.partials.footer')
@stop
