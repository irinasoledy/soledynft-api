@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="categoryContent">
    <div class="container">
        <h3>{{ $category->translation->name }}</h3>
        <section class="catOne__filter-buttons">
            <div class="filter__buttons-container" id="filterButtonsContainer">
              <button id="filterButton" class="filterButton">
                <div class="filterButton__icon">
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="fas"
                    data-icon="filter"
                    class="svg-inline--fa fa-filter fa-w-16"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 512 512"
                    >
                    <path
                      fill="currentColor"
                      d="M487.976 0H24.028C2.71 0-8.047 25.866 7.058 40.971L192 225.941V432c0 7.831 3.821 15.17 10.237 19.662l80 55.98C298.02 518.69 320 507.493 320 487.98V225.941l184.947-184.97C520.021 25.896 509.338 0 487.976 0z"
                    ></path>
                  </svg>
                </div>
                {{ trans('vars.DetailsProductSet.filter') }}
              </button>
              <button id="sortButton" class="sortButton">
                <div class="sortButton__icon">
                  <svg
                    aria-hidden="true"
                    focusable="false"
                    data-prefix="fas"
                    data-icon="sort"
                    class="svg-inline--fa fa-sort fa-w-10"
                    role="img"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 320 512"
                    >
                  <path
                    fill="currentColor"
                    d="M41 288h238c21.4 0 32.1 25.9 17 41L177 448c-9.4 9.4-24.6 9.4-33.9 0L24 329c-15.1-15.1-4.4-41 17-41zm255-105L177 64c-9.4-9.4-24.6-9.4-33.9 0L24 183c-15.1 15.1-4.4 41 17 41h238c21.4 0 32.1-25.9 17-41z"
                  ></path>
                </svg>

                </div>
                Sort
              </button>
            </div>
        </section>
      {{--   <div class="filter__buttons-container">
          <div id="filterButton" class="filterButton">{{ trans('vars.DetailsProductSet.filter') }}</div>
            <div id="sortButton" class="sortButton">Sort</div>
             <parameters-filter-mob :category="{{ $category }}" site="{{ $site }}"></parameters-filter-mob>
        </div>
        --}}
        <!-- filter fixed -->
        <div id="filter" class="filter">
             <form class="filter__inner" id="formFilter">
                 <filter-block :category="{{ $category }}" site="{{ $site }}"></filter-block>
            </form>
        </div>

        <!-- sort fixed -->
        <div id="sort" class="filter">
          <form class="filter__inner">
              <sort-block :category="{{ $category }}" site="{{ $site }}"></sort-block>
          </form>
        </div>

        <category-mob :category="{{ $category }}" :product="0" site="{{ $site }}"></category-mob>
    </div>
</main>
@include('front.partials.footer')
@stop


@section('microdataGoogle')

    <script type="application/ld+json">
    [
      @foreach ($products as $key => $product)
        @php
            $color = getProductColor($product->id, 2);
        @endphp
          {
            "@context": "http://schema.org/",
              "@type": "Product",
              "sku": "{{ $product->code }}",
              "gtin14" : "{{ $product->ean_code }}",
              "mpn": "{{ $product->ean_code }}",
              @if ($product->FBImage) {
                  "image": "/images/producs/fbq/{{ $product->FBImage->src }}",
              @endif
              "name": "{{ $product->translation->name }}",
              "description": "{{ $product->translation->atributes }}",
              "brand": {
                "@type": "Thing",
                "name": "Anne Popova"
              },
              "color" : "{{ $color ?? '' }}",
              "offers": {
                "@type": "Offer",
                "priceCurrency": "{{ $currency->abbr }}",
                "price": "{{ $product->personalPrice->price }}",
                "itemCondition": "http://schema.org/UsedCondition",
                "availability": "in_stock"
              }
         }
         @if ($key !== count($products) - 1)
            ,
         @endif
     @endforeach
    ]
</script>

@stop
