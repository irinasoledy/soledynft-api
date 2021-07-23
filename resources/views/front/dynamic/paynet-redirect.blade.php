@extends('../front.app')
@section('content')
@include('front.partials.header')


<main class="cartContent">
    <div class="container">
        <div class="space" style="height:170px"></div>
        <h2 class="text-center">Redirecting to a payment gateway of paynet....</h2>
        <div style="visibility:hidden">
            @if ($form)
                {!! $form !!}
            @endif
        </div>
        <div class="space" style="height:50px"></div>

    </div>
</main>


<script>
    document.forms[0].submit();
</script>


@include('front.partials.footer')
@stop
