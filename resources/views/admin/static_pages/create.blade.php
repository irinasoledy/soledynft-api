@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.min.js" integrity="sha512-GoORoNnxst42zE3rYPj4bNBm0Q6ZRXKNH2D9nEmNvVF/z24ywVnijAWVi/09iBiVDQVf3UlZHpzhAJIdd9BXqw==" crossorigin="anonymous"></script>

@section('content')


<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('static-pages.index') }}">Static Pages</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Static Page</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Static Page </h3>
    @include('admin.list-elements', [
    'actions' => [
            "Add new" => route('static-pages.create'),
        ]
    ])
</div>
@include('admin.alerts')

<div class="list-content">
    <div class="card">
        <div class="card-block">
            <form class="form-reg" role="form" method="POST" action="{{ route('static-pages.store') }}" id="add-form" enctype="multipart/form-data">
                {{ csrf_field() }}
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
                            <div class="form-group">
                                <label for="name-{{ $lang->lang }}">Title [{{ $lang->lang }}]</label>
                                <input type="text" name="title_{{ $lang->lang }}" class="form-control">
                            </div>
                            <div class="ckeditor form-group">
                                <label for="body-{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                                <textarea rows="20" name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="meta_title_{{ $lang->lang }}">Seo title [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords_{{ $lang->lang }}">Seo Keyword[{{ $lang->lang }}]</label>
                                <input type="text" name="meta_keywords_{{ $lang->lang }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="meta_description_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_description_{{ $lang->lang }}" class="form-control">
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="submit" value="Save"class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@section('footer')
<footer>
    @include('admin.footer')
</footer>
@stop
