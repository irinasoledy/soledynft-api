@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payment Methods</a></li>
        <li class="breadcrumb-item active" aria-current="blog">Edit Payment Method</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit Payment Method</h3>
</div>
@include('admin.alerts')
<div class="list-content">
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-md-9">
                    <form class="form-reg" role="form" method="POST" action="{{ route('payments.update', $payment->id) }}" id="add-form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-area">
                                    <ul class="nav nav-tabs nav-tabs-bordered">
                                        @if (!empty($langs))
                                            @foreach ($langs as $lang)
                                                <li class="nav-item">
                                                    <a href="#{{ $lang->lang }}" class="nav-link  {{ $loop->first ? ' open active' : '' }}"
                                                        data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                @if (!empty($langs))
                                    @foreach ($langs as $lang)
                                        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
                                            @foreach($payment->translations as $translation)
                                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                                <div class="form-group">
                                                    <label for="name-{{ $lang->lang }}">Name [{{ $lang->lang }}]</label>
                                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $translation->name }}">
                                                </div>
                                                @endif
                                            @endforeach
                                            <hr>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Save" class="btn btn-primary">
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <span class="label label-warning info-absolute">Info</span>
                    <h6>Countries:</h6>

                    @if ($payment->countries()->count())
                        <ol>
                            @foreach ($payment->countries()->get() as $key => $countryPayment)
                                <li>{{ $key + 1 }}. {{ $countryPayment->country()->translation->name }}</li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin.footer')
</footer>
@stop
