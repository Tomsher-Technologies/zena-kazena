@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Edit Page Information</h1>
            </div>
        </div>
    </div>
    <div class="card">
        <ul class="nav nav-tabs nav-fill border-light">
            @foreach (\App\Models\Language::all() as $key => $language)
                <li class="nav-item">
                    <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('custom-pages.edit', ['id'=>$page->type, 'lang'=> $language->code] ) }}">
                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                        <span>{{$language->name}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
 
        <form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <input  type="hidden" name='lang' value="{{$lang}}">
            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Page Content</h6>
            </div>
            <div class="card-body px-0">
                <div class="form-group row">
                    <h5 class="mb-0 ml-3">Our Story Section</h5>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="title"
                            value="{{ $page->getTranslation('title', $lang) }}" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea  class="aiz-text-editor form-control" placeholder="{{ trans('messages.content') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content" required>{!! $page->getTranslation('content', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <h5 class="mb-0 ml-3">Our Mission Section</h5>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading1"
                            value="{{ $page->getTranslation('heading1', $lang) }}" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea  class="aiz-text-editor form-control" placeholder="{{ trans('messages.content') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content1" required>{!! $page->getTranslation('content1', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <h5 class="mb-0 ml-3">Why Choose Us Section</h5>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading2"
                            value="{{ $page->getTranslation('heading2', $lang) }}" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea  class="aiz-text-editor form-control" placeholder="{{ trans('messages.content') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content2" required>{!! $page->getTranslation('content2', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <h5 class="mb-0 ml-3">Our Collections Section</h5>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading3"
                            value="{{ $page->getTranslation('heading3', $lang) }}" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea  class="aiz-text-editor form-control" placeholder="{{ trans('messages.content') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content3" required>{!! $page->getTranslation('content3', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <h5 class="mb-0 ml-3">Our Commitment to You Section</h5>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading4"
                            value="{{ $page->getTranslation('heading4', $lang) }}" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }} <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <textarea  class="aiz-text-editor form-control" placeholder="{{ trans('messages.content') }}"
                            data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                            data-min-height="300" name="content4" required>{!! $page->getTranslation('content4', $lang) !!}</textarea>
                    </div>
                </div>
                
            </div>

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Seo Fields</h6>
            </div>
            <div class="card-body px-0">

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text"  @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.meta_title') }}" name="meta_title"
                            value="{{ $page->getTranslation('meta_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_description') }}</label>
                    <div class="col-sm-10">
                        <textarea  @if($lang == 'ae') dir="rtl" @endif class="resize-off form-control" placeholder="{{ trans('messages.meta_description') }}" name="meta_description">{!! $page->getTranslation('meta_description', $lang) !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_keywords') }}</label>
                    <div class="col-sm-10">
                        <textarea  @if($lang == 'ae') dir="rtl" @endif class="resize-off form-control" placeholder="{{ trans('messages.meta_keywords')}}" name="keywords">{!! $page->getTranslation('keywords', $lang) !!}</textarea>
                        <small class="text-muted">Separate with coma</small>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.og_title') }}"
                            name="og_title" value="{{ $page->getTranslation('og_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_description') }}</label>
                    <div class="col-sm-10">
                        <textarea  @if($lang == 'ae') dir="rtl" @endif class="resize-off form-control" placeholder="{{ trans('messages.og_description') }}" name="og_description">{!! $page->getTranslation('og_description', $lang) !!}</textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.twitter_title') }}</label>
                    <div class="col-sm-10">
                        <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.twitter_title') }}"
                            name="twitter_title" value="{{ $page->getTranslation('twitter_title', $lang) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.twitter_description') }}</label>
                    <div class="col-sm-10">
                        <textarea  @if($lang == 'ae') dir="rtl" @endif class="resize-off form-control" placeholder="{{ trans('messages.twitter_description') }}"
                            name="twitter_description">{!! $page->getTranslation('twitter_description', $lang) !!}</textarea>
                    </div>
                </div>

        
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Update Page</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        var lang = '{{ $lang }}';
       
        if(lang == 'ae'){
            setEditorDirection(true);
        }else{
            setEditorDirection(false);
        }
        function setEditorDirection(isRtl) {
            const editor = $('.aiz-text-editor').next('.note-editor').find('.note-editable');
            editor.attr('dir', isRtl ? 'rtl' : 'ltr'); // Set direction
            editor.css('text-align', isRtl ? 'right' : 'left');
        }
    });
</script>

@endsection
