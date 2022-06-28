@extends('admin.app')
@include('admin.nav-bar')
@include('admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('static-pages.index') }}">Static Pages</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit static page</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Edit static page </h3>
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
            <form class="form-reg" role="form" method="POST" action="{{ route('static-pages.update', $page->id) }}" id="add-form" enctype="multipart/form-data">
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
                            @foreach($page->translations as $translation)
                            @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                <div class="form-group">
                                    <label for="name-{{ $lang->lang }}">Title [{{ $lang->lang }}]</label>
                                    <input type="text" name="title_{{ $lang->lang }}" class="form-control" value="{{ $translation->name }}">
                                </div>
                            @endif
                            @endforeach

                            @foreach($page->translations as $translation)
                                @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                    <div class="ckeditor form-group">
                                        <label for="body-{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                                        <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}" class="form-control" rows="20">{!! $translation->body !!}</textarea>
                                    </div>
                                @endif
                            @endforeach
                        <hr>
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                            <div class="form-group">
                                <label>Seo Title [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_title_{{ $lang->lang }}" class="form-control" value="{{ $translation->meta_title }}">
                            </div>
                        @endif
                        @endforeach
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                            <div class="form-group">
                                <label>Seo Keywords [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_keywords_{{ $lang->lang }}" class="form-control" value="{{ $translation->meta_keywords }}">
                            </div>
                        @endif
                        @endforeach
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                            <div class="form-group">
                                <label>Seo Title [{{ $lang->lang }}]</label>
                                <input type="text" name="meta_description_{{ $lang->lang }}" class="form-control" value="{{ $translation->meta_description }}">
                            </div>
                        @endif
                        @endforeach
                    </div>
                    @endforeach
                    @endif
                    </div>
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary" value="Save">
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
