@extends('../front.app')
@section('content')
@include('front.partials.header')
<main class="cartContent cartClass">
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <h3>{{ trans('vars.Cart.cartYour') }}</h3>
            </div>
            <div class="col-12">
                <div class="row productsList">
                    <div class="col-12">
                        @if (Auth::guard('persons')->user())
                        <cart-mob :items="{{ $cartProducts }}"
                            :mode="'cart'"
                            site="{{ $site }}"
                            ></cart-mob>
                        @else
                        @if (!is_null($unloggedUser))
                        <cart-mob :items="{{ $cartProducts }}"
                            :mode="'guest'"
                            site="{{ $site }}"
                            ></cart-mob>
                        @else
                        <cart-mob :items="{{ $cartProducts }}"
                            :mode="'auth'"
                            site="{{ $site }}"
                            ></cart-mob>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Session::has('payError'))
    <div class="modals">
        <div class="modal show" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modalContent">
                        <div class="closeModal" data-dismiss="modal">
                            <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                        </div>
                        {{ Session::get('payError') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</main>
@include('front.partials.footer')
@stop
