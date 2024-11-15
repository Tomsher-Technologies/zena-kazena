@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ trans('messages.category') .' '. trans('messages.information')}}</h5>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-fill border-light">
                        @foreach (\App\Models\Language::all() as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                    href="{{ route('categories.edit', ['id' => $category->id, 'lang' => $language->code]) }}">
                                    <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                        height="11" class="mr-1">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <form class="p-4" action="{{ route('categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PATCH">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ trans('messages.name') }} <i
                                    class="las la-language text-danger" title="{{ trans('messages.translatable') }}"></i></label>
                            <div class="col-md-9">
                                <input  type="text" name="name"
                                    value="{{ $category->getTranslation('name', $lang) }}" class="form-control"
                                    id="name" placeholder="{{ trans('messages.name') }}" onkeyup="makeSlug(this)">
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-sm-3 col-from-label" for="name">{{ trans('messages.slug') }}</label>
                            <div class="col-sm-9">
                                <input  type="text" placeholder="{{ trans('messages.slug') }}" id="slug"
                                    name="slug" value="{{ old('slug', $category->getTranslation('slug', $lang)) }}" class="form-control">
                                @error('slug')
                                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row  @if ($lang != 'en') d-none @endif">
                            <label class="col-md-3 col-form-label">{{ trans('messages.parent') .' '. trans('messages.category') }}</label>
                            <div class="col-md-9">
                                <select  class="select2 form-control aiz-selectpicker" name="parent_id"
                                    data-toggle="select2" data-placeholder="Choose ..."data-live-search="true"
                                    data-selected="{{ $category->parent_id }}">
                                    <option value="0">{{ trans('messages.no') .' '. trans('messages.parent') }}</option>
                                    @foreach ($categories as $acategory)
                                        <option value="{{ $acategory->id }}">{{ $acategory->getTranslation('name') }}
                                        </option>
                                        @foreach ($acategory->childrenCategories as $childCategory)
                                            @include('backend.categories.child_category', [
                                                'child_category' => $childCategory,
                                            ])
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                      
                     
                        <div class="form-group row ">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.icon') }}
                                <small>({{ trans('messages.32x32') }})</small></label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ trans('messages.browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                    <input type="hidden" name="icon" class="selected-files"
                                        value="{{ $category->getTranslation('icon', $lang) }}">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        @if ($lang == 'en')
                            <div class="form-group  row">
                                <label class="col-md-3 col-form-label">{{trans('messages.status')}}</label>
                                <div class="col-md-9">
                                    <select class="select2 form-control" name="status">
                                        <option {{ old('status', $category->is_active) == 1 ? 'selected' : '' }} value="1">{{trans('messages.enabled')}}
                                        </option>
                                        <option {{ old('status', $category->is_active) == 2 ? 'selected' : '' }} value="2">{{trans('messages.disabled')}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        @endif


                        <h5 class="mb-0 h6">{{trans('messages.seo_section')}}</h5>
                        <hr>
    
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ trans('messages.meta_title') }}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="meta_title"
                                    value="{{ old('meta_title', $category->getTranslation('meta_title', $lang)) }}"
                                    placeholder="{{ trans('messages.meta_title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ trans('messages.meta_description') }}</label>
                            <div class="col-sm-9">
                                <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', $category->getTranslation('meta_description', $lang)) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ trans('messages.meta_keywords') }}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="meta_keywords"
                                    value="{{ old('meta_keywords',$category->getTranslation('meta_keyword', $lang)) }}"
                                    placeholder="{{ trans('messages.meta_keywords') }}">
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ trans('messages.og_title') }}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="og_title"
                                    value="{{ old('og_title',$category->getTranslation('og_title', $lang)) }}"
                                    placeholder="{{ trans('messages.og_title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ trans('messages.og_description') }}</label>
                            <div class="col-sm-9">
                                <textarea name="og_description" rows="5" class="form-control">{{ old('og_description', $category->getTranslation('og_description', $lang)) }}</textarea>
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ trans('messages.twitter_title') }}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="twitter_title"
                                    value="{{ old('twitter_title', $category->getTranslation('twitter_title', $lang)) }}"
                                    placeholder="{{ trans('messages.twitter_title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ trans('messages.twitter_description') }}</label>
                            <div class="col-sm-9">
                                <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description', $category->getTranslation('twitter_description', $lang)) }}</textarea>
                            </div>
                        </div>
                       
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ trans('messages.Save') }}</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-cancel">{{trans('messages.cancel')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    function makeSlug(e) {
        var title = e.value;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('generate-slug') }}",
            type: 'GET',
            data: {
                title: title
            },
            success: function(response) {
                $('#slug').val(response);
            }
        });
    }
</script>
@endsection