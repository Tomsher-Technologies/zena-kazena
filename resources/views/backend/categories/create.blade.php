@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ trans('messages.category') .' '. trans('messages.information')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                	@csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ trans('messages.name') }}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{trans('messages.name')}}" id="name" name="name" onkeyup="makeSlug(this.value)" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ trans('messages.slug') }}</label>
                        <div class="col-sm-9">
                            <input  type="text" placeholder="{{ trans('messages.slug') }}" id="slug"
                                name="slug" value="{{ old('slug') }}" class="form-control">
                            @error('slug')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{trans('messages.parent') .' '. trans('messages.category')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0">{{ trans('messages.no') .' '. trans('messages.parent') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                    @foreach ($category->childrenCategories as $childCategory)
                                        @include('backend.categories.child_category', ['child_category' => $childCategory])
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{trans('messages.icon')}} <small>({{ trans('messages.32x32') }})</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ trans('messages.browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ trans('messages.choose_file') }}</div>
                                <input type="hidden" name="icon" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group  row">
                        <label class="col-md-3 col-form-label">{{trans('messages.status')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control" name="status">
                                <option {{ old('status') == 1 ? 'selected' : '' }} value="1">{{trans('messages.enabled')}}
                                </option>
                                <option {{ old('status') == 2 ? 'selected' : '' }} value="2">{{trans('messages.disabled')}}
                                </option>
                            </select>
                        </div>
                    </div>

                    
                    <h5 class="mb-0 h6">{{trans('messages.seo_section')}}</h5>
                    <hr>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ trans('messages.meta_title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="meta_title"
                                value="{{ old('meta_title') }}"
                                placeholder="{{ trans('messages.meta_title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ trans('messages.meta_description') }}</label>
                        <div class="col-sm-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ trans('messages.meta_keywords') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="meta_keywords"
                                value="{{ old('meta_keywords') }}"
                                placeholder="{{ trans('messages.meta_keywords') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ trans('messages.og_title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="og_title"
                                value="{{ old('og_title') }}"
                                placeholder="{{ trans('messages.og_title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ trans('messages.og_description') }}</label>
                        <div class="col-sm-9">
                            <textarea name="og_description" rows="5" class="form-control">{{ old('og_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ trans('messages.twitter_title') }}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="twitter_title"
                                value="{{ old('twitter_title') }}"
                                placeholder="{{ trans('messages.twitter_title') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{ trans('messages.twitter_description') }}</label>
                        <div class="col-sm-9">
                            <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{trans('messages.Save')}}</button>
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
    function makeSlug(val) {
        let str = val;
        let output = str.replace(/\s+/g, '-').toLowerCase();
        $('#slug').val(output);
    }
</script>
@endsection