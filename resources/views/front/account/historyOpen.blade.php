@extends('front.app')
@section('content')
@include('front.partials.header')
<main class="clientArea">
    <div class="container">
    <div class="row">

        <div class="col-12">
            <div class="user">
                <p>{{ trans('vars.General.hello') }}, {{ $userdata->name }}</p>
                <p>{{ trans('vars.Cabinet.welcomeTo') }} {{ trans('vars.Cabinet.yourOrderHistory') }}</p>
            </div>
        </div>
        <div class="col-lg-auto col-md-12">
            <div class="navArea" id="navArea">
                <div id="pageSelected">
                    {{ trans('vars.Cabinet.yourOrderHistory') }}
                    <svg
                        width="12px"
                        height="6px"
                        viewBox="0 0 12 6"
                        version="1.1"
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        >
                        <g id="AnaPopova-Site" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g
                                id="Cabinet_Mob._375-cos"
                                transform="translate(-325.000000, -156.000000)"
                                fill="#B22D00"
                                fill-rule="nonzero"
                                >
                                <polygon
                                    id="Shape"
                                    transform="translate(331.000000, 159.000000) scale(1, -1) translate(-331.000000, -159.000000) "
                                    points="331 156 325 162 330.716323 162 337 162"
                                    ></polygon>
                            </g>
                        </g>
                    </svg>
                </div>
                @include('front.account.accountMenu')
            </div>
        </div>
        <div class="col-lg col-md-12">
            <div class="row oneHistory">
                <div class="col-12">
                    <p class="titleOne">
                        <span>{{ trans('vars.Cabinet.order') }} {{ trans('vars.Cabinet.nr') }} {{ $order->order_hash }}</span>
                        <span class="status delivered">{{ $order->main_status }}</span>
                    </p>
                </div>
                <div class="col-12">
                    <div class="row justify-content-between">
                        <div class="col-auto orderDetails">
                            <p>{{ trans('vars.Cabinet.contactData') }}</p>
                            <span>{{ $order->details->contact_name }}</span>
                            <span>+{{ $order->details->code }} {{ $order->details->phone }}</span>
                            <span>{{ $order->details->email }}</span>
                        </div>
                        <div class="col-auto orderDetails">
                            <p>{{ trans('vars.Cabinet.shipiingAddress') }}</p>
                            <span>{{ $order->details->country }}</span>
                            <span>
                            @if ($order->details->region)
                            {{ $order->details->region }},
                            @endif
                            {{ $order->details->city }},
                            {{ $order->details->address }},
                            @if ($order->details->zip)
                            {{ $order->details->zip }}
                            @endif
                            </span>
                        </div>
                        <div class="col-auto orderDetails">
                            <p>{{ trans('vars.Cabinet.shipping/payment') }}</p>
                            <span>{{ $order->details->delivery }}/{{ $order->details->payment }}</span>
                            <span>{{ trans('vars.Cabinet.amount') }}: {{ $order->amount }} {{ $order->currency->abbr }}</span>
                        </div>
                        @if ($order->tracking_link)
                            <div class="col-auto orderDetails">
                                <p>{{ trans('vars.Cabinet.trackingNumber') }}</p>
                                <span><a target="_blank" href="{{ $order->tracking_link }}">{{ $order->tracking_link }}</a></span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12">
                    <p class="titleOne">
                        {{ trans('vars.Cabinet.products') }}:
                    </p>
                    <div class="row productsList">
                        <div class="col-12">
                            @if ($order->orderSubproducts()->count() > 0)
                            @foreach ($order->orderSubproducts as $key => $subproduct)
                            <div class="cartItem">
                                <a class="col-auto" href="{{ url('/'.$lang->lang.'/homewear/catalog/'.$subproduct->subproduct->product->category->alias.'/'.$subproduct->subproduct->product->alias) }}">
                                  @if ($subproduct->subproduct->product->mainImage)
                                  <img src="/images/products/og/{{ $subproduct->subproduct->product->mainImage->src }}" alt="" />
                                  @else
                                  <img src="/fronts/img/prod/oneProduct.jpg" alt="" />
                                @endif
                                </a>
                                <div class="description col-md">
                                    <a href="{{ url('/'.$lang->lang.'/homewear/catalog/'.$subproduct->subproduct->product->category->alias.'/'.$subproduct->subproduct->product->alias) }}">
                                        {{ $subproduct->subproduct->product->translation->name }}
                                    </a>
                                    <span>{{ $subproduct->subproduct->product->personalPrice->price }} {{ $currency->abbr }}</span>
                                    <div class="params">
                                        <span>{{ trans('vars.Cabinet.qty') }}: {{ $subproduct->qty }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @if ($order->orderProducts()->count() > 0)
                            @foreach ($order->orderProducts as $key => $product)
                            <div class="cartItem">
                                <a class="col-auto" href="{{ url('/'.$lang->lang.'/bijoux/catalog/'.$product->product->category->alias.'/'.$product->product->alias) }}">
                                  @if ($product->product->mainImage)
                                  <img src="/images/products/og/{{ $product->product->mainImage->src }}" alt="" />
                                  @else
                                  <img src="/fronts/img/prod/oneProduct.jpg" alt="" />
                                  @endif
                                </a>
                                <div class="description col-md">
                                    <a href="{{ url('/'.$lang->lang.'/bijoux/catalog/'.$product->product->category->alias.'/'.$product->product->alias) }}">
                                        {{ $product->product->translation->name }}
                                    </a>
                                    @if ($product->set_id)
                                        <span>{{ $product->product->personalPrice->set_price }} {{ $currency->abbr }}</span>
                                    @else
                                        <span>{{ $product->product->personalPrice->price }} {{ $currency->abbr }}</span>
                                    @endif
                                    <div class="params">
                                        <span>{{ trans('vars.Cabinet.qty') }}: {{ $product->qty }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                          <p class="titleOne">
                              {{ trans('vars.Cabinet.amount') }}: {{ $order->amount }} {{ $order->currency->abbr }}
                          </p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('front.partials.footer')
@stop
