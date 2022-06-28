@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="gallery">Google API Content </li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Google API Content </h3>
</div>

<div class="card card-block">

        <div class="row">
            <div class="col-md-6">
                <h6>Insert/Update all products:</h6>
                <form class="" action="{{ url('/back/google-api-content/insert-content') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="">Country:</label>
                        <select class="form-control" name="country">
                            @foreach ($countries as $key => $country)
                                <option value="{{ $country->iso }}">{{ $country->translation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Language:</label>
                        <select class="form-control" name="lang">
                            @foreach ($langs as $key => $oneLang)
                                <option value="{{ $oneLang->lang }}">{{ $oneLang->lang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Currency:</label>
                        <select class="form-control" name="currency_id">
                            @foreach ($currencies as $key => $currency)
                                <option value="{{ $currency->id }}">{{ $currency->abbr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Products from:</label>
                        <select class="form-control" name="type">
                            <option value="homewear">Homewear</option>
                            <option value="bijoux">Bijoux</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Handle" class="btn btn-primary">
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <h6>Add products to list</h6>
                <form class="" action="{{ url('/back/google-api-content/insert-new-content') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="">Countries</label>
                        <select class="form-control" name="country">
                            @foreach ($countries as $key => $country)
                                <option value="{{ $country->iso }}">{{ $country->translation->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Languages</label>
                        <select class="form-control" name="lang">
                            @foreach ($langs as $key => $oneLang)
                                <option value="{{ $oneLang->lang }}">{{ $oneLang->lang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Currency:</label>
                        <select class="form-control" name="currency_id">
                            @foreach ($currencies as $key => $currency)
                                <option value="{{ $currency->id }}">{{ $currency->abbr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Products from:</label>
                        <select class="form-control" name="type">
                            <option value="homewear">Homewear</option>
                            <option value="bijoux">Bijoux</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Handle" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

    <hr>
</div>


@stop
@section('footer')
<footer>
    @include('admin.footer')
</footer>
@stop
