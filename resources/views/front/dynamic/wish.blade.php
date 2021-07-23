@extends('../front.app')
@section('content')
@include('front.partials.header')
<main class="wishContent cartClass">
    <div class="container">
        <div class="row wishItems">
            <div class="col-12">
                <h3>favorites</h3>
            </div>
            <wish-block-mob :products="{{ $data['products'] }}"
                :sets="{{ $data['sets'] }}"
                site="{{ $site }}">
            </wish-block-mob>
        </div>
    </div>
</main>
@include('front.partials.footer')
@stop
