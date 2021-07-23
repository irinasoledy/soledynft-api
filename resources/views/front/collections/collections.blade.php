@extends('front.app')
@section('content')
@include('front.partials.header')

<main class="oneProductContent collectionContent oneProductContentNew">
    @php
        if (is_null($mainSet)) {
            $mainSet = json_encode('empty');
        }
    @endphp

    {{-- <collection-mobile
                    :main_set="{{ $mainSet }}"
                    :other_sets="{{ $otherSets }}"
                    :collection="{{ $collection }}"
                    :similars="{{ $similars }}"
                    site="{{ $site }}"
                    >
    </collection-mobile> --}}

    <collection
        :main_set="{{ $mainSet }}"
        :other_sets="{{ $otherSets }}"
        :collection="{{ $collection }}"
        :similars="{{ $similars }}"
        site="{{ $site }}"
        >
    </collection>

</main>
@include('front.partials.footer')

@include('front.partials.modalsPage')
@stop

<style media="screen">
    .oneProductContent{
        height: auto !important;
    }
</style>
