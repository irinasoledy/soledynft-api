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

    <promo-sets-mobile
                :other_sets="{{ $promotion->sets }}"
                site="{{ $site }}"
            >
    </promo-sets-mobile>

</main>
@include('front.partials.footer')
@stop
