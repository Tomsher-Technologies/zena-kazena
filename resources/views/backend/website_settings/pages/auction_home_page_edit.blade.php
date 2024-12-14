@extends('backend.layouts.app')
@section('content')

    <div class="row">
        <div class="col-xl-10 mx-auto">
            <h4 class="fw-600">Auction Home Page Settings</h4>


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

                <div class="card-header @if($lang != 'en') d-none @endif">
                    <h5 class="mb-0">Video Section</h5>
                </div>
                <div class="card-body @if($lang != 'en') d-none @endif">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="video">Choose Video <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="file" name="video" id="video" accept="video/*">
                                <br><br>
                                @if($page->video != NULL)
                                    <h6>Preview:</h6>
                                    <video width="400" controls>
                                        <source src="{{ asset($page->video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="content5">{{ trans('messages.content') }} </label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.content') }}" name="content5" @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('content5',$lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="text-right">
                            <input type="hidden" name="page_type" value="highlights_section">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <form class="p-4" action="{{ route('business_settings.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                    <div class="card-header px-0">
                        <h6 class="fw-600 mb-0">How it works Section</h6>
                    </div>
                    <div class="card-body px-0">
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="title">{{ trans('messages.heading') }}</label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="title"
                                    value="{{ $page->getTranslation('title', $lang) }}">
                            </div>
                        </div>

                        <div class="form-group row @if ($lang != 'en') d-none @endif">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">{{ trans('messages.icon') }} 1</label>
                            <div class="col-md-10">
                                <input type="file" name="image1" class="form-control" accept="image/*">

                                @if ($page->image1)
                                    <div class="file-preview box sm">
                                        <div  class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                            <div
                                                class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <img src="{{ asset($page->image1) }}" class="img-fit">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="sub_title">{{ trans('messages.title') }} 1</label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.title') }} 1" name="sub_title"
                                    value="{{ $page->getTranslation('sub_title', $lang) }}">
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.content') }}1</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.content') }} 1" name="content"  @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('content',$lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row @if ($lang != 'en') d-none @endif">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">{{ trans('messages.icon') }} 2</label>
                            <div class="col-md-10">
                                <input type="file" name="image2" class="form-control" accept="image/*">

                                @if ($page->image2)
                                    <div class="file-preview box sm">
                                        <div  class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                            <div
                                                class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <img src="{{ asset($page->image2) }}" class="img-fit">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="title1">{{ trans('messages.title') }} 2</label>
                            <div class="col-sm-10">
                                <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.title') }} 2"
                                    name="title1" value="{{ $page->getTranslation('title1',$lang) }}">
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="content1">{{ trans('messages.content') }} 2</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.content') }} 2" name="content1" @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('content1',$lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row @if ($lang != 'en') d-none @endif">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">{{ trans('messages.icon') }} 3</label>
                            <div class="col-md-10">
                                <input type="file" name="image3" class="form-control" accept="image/*">

                                @if ($page->image3)
                                    <div class="file-preview box sm">
                                        <div  class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                            <div
                                                class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <img src="{{ asset($page->image3) }}" class="img-fit">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="title2">{{ trans('messages.title') }} 3</label>
                            <div class="col-sm-10">
                                <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.title') }} 3"
                                    name="title2" value="{{ $page->getTranslation('title2',$lang) }}">
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="content2">{{ trans('messages.content') }} 3</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.content') }} 3" name="content2" @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('content2',$lang) !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row @if ($lang != 'en') d-none @endif">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">{{ trans('messages.icon') }} 4</label>
                            <div class="col-md-10">
                                <input type="file" name="image4" class="form-control" accept="image/*">

                                @if ($page->image4)
                                    <div class="file-preview box sm">
                                        <div  class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                            <div
                                                class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <img src="{{ asset($page->image4) }}" class="img-fit">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="title3">{{ trans('messages.title') }} 4</label>
                            <div class="col-sm-10">
                                <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.title') }} 4"
                                    name="title3" value="{{ $page->getTranslation('title3',$lang) }}">
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="content3">{{ trans('messages.content') }} 4</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.content') }} 4" name="content3" @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('content3',$lang) !!}</textarea>
                            </div>
                        </div>
                       
        
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Discover Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans("messages.heading") }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading1" @if($lang == 'ae') dir="rtl" @endif value="{{ old('heading1', $page->getTranslation('heading1', $lang)) }}" required>
                            </div>
                        </div>
                       
                        <div class="form-group @if($lang != 'en') d-none @endif">
                            <label>Categories (Max 4)</label>
                            <div class="new_collection-categories-target">
                                <input type="hidden" name="types[]" value="auction_discover_categories">
                                <input type="hidden" name="page_type" value="new_collection">
                                <input type="hidden" name="page_id" value="{{ $page_id }}">
                                <input type="hidden" name="lang" value="{{ $lang }}">
                                
                                @if (get_setting('auction_discover_categories') != null && get_setting('auction_discover_categories') != 'null')
                                    @foreach (json_decode(get_setting('auction_discover_categories'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col">
                                                <div class="form-group">
                                                    <select class="form-control aiz-selectpicker" name="auction_discover_categories[]" data-live-search="true" data-selected={{ $value }}
                                                        required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                            @foreach ($category->childrenCategories as $childCategory)
                                                                @include('backend.categories.child_category', [
                                                                    'child_category' => $childCategory,
                                                                ])
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='<div class="row gutters-5">
								<div class="col">
									<div class="form-group">
										<select class="form-control aiz-selectpicker" name="auction_discover_categories[]" data-live-search="true" required>
                                            <option value="">Select Category</option>
											@foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                            @include('backend.categories.child_category', [
                                                'child_category' => $childCategory,
                                            ])
                                            @endforeach
                                            @endforeach
										</select>
									</div>
								</div>
								<div class="col-auto">
									<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
										<i class="las la-times"></i>
									</button>
								</div>
							</div>'
                                data-target=".new_collection-categories-target">
                                Add New
                            </button>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Home Banner 1 --}}

            <div class="card  @if($lang != 'en') d-none @endif">
                <div class="card-header">
                    <h5 class="mb-0">Mid Banners</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="types[]" value="auction_home_mid_banner">
                        <input type="hidden" name="name" value="auction_home_mid_banner">
                        <input type="hidden" name="page_type" value="auction_home_mid_banner">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        @error('auction_home_mid_banner')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- <div class="form-group">
                            <label>Status</label>
                            <div class="home-banner1-target">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="status"
                                        {{ get_setting('home_mid_banner_status') == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div> --}}
                        @php
                            $small_banners = json_decode($current_banners['auction_home_mid_banner']->value);
                        @endphp
                        <div class="form-group">
                            <label>Banner 1</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="auction_home_mid_banner[]" data-live-search="true">
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($small_banners[0]) && $banner->id == $small_banners[0] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Banner 2</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="auction_home_mid_banner[]" data-live-search="true">
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($small_banners[1]) && $banner->id == $small_banners[1] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">New Arrivals</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading2" value="{{ old('heading2', $page->getTranslation('heading2', $lang)) }}" required>
                            </div>
                        </div>

                    
                        <div class="form-group row @if($lang != 'en') d-none @endif">
                            <label class="col-md-2 col-from-label">{{ trans('messages.products') }} (Max 4)</label>
                            <div class="col-md-10">
                                <input type="hidden" name="types[]" value="auction_new_arrival_products">
                                <input type="hidden" name="page_type" value="auction_new_arrival_products">
                                <select name="auction_new_arrival_products[]" class="form-control aiz-selectpicker" multiple
                                    data-live-search="true" title="Select Products" data-selected="{{ get_setting('auction_new_arrival_products') }}">
                                    {{-- <option disabled value=""></option> --}}
                                    @foreach ($products as $key => $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card  @if($lang != 'en') d-none @endif">
                <div class="card-header">
                    <h5 class="mb-0">Banner</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="types[]" value="auction_home_center_banner">
                        <input type="hidden" name="name" value="auction_home_center_banner">
                        <input type="hidden" name="page_type" value="auction_home_center_banner">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        @error('auction_home_center_banner')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        @php
                            $center_banners = json_decode($current_banners['auction_home_center_banner']->value);
                        @endphp
                        <div class="form-group">
                            <label>Banner</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="auction_home_center_banner[]" data-live-search="true">
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($center_banners[0]) && $banner->id == $center_banners[0] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                       
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Special Products</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading4" value="{{ old('heading4', $page->getTranslation('heading4', $lang)) }}" required>
                            </div>
                        </div>

                    
                        <div class="form-group row @if($lang != 'en') d-none @endif">
                            <label class="col-md-2 col-from-label">{{ trans('messages.products') }} (Max 4)</label>
                            <div class="col-md-10">
                                <input type="hidden" name="types[]" value="auction_special_products">
                                <input type="hidden" name="page_type" value="auction_special_products">
                                <select name="auction_special_products[]" class="form-control aiz-selectpicker" multiple
                                    data-live-search="true" title="Select Products" data-selected="{{ get_setting('auction_special_products') }}">
                                    {{-- <option disabled value=""></option> --}}
                                    @foreach ($products as $key => $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card @if($lang != 'en') d-none @endif">
                <div class="card-header">
                    <h5 class="mb-0">Mid Section Banners</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="types[]" value="auction_home_mid_section_banner">
                        <input type="hidden" name="name" value="auction_home_mid_section_banner">

                        @error('auction_home_mid_section_banner')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        @php
                            $mid_section_banner = (isset($current_banners['auction_home_mid_section_banner'])) ? json_decode($current_banners['auction_home_mid_section_banner']->value) : [];
                        @endphp

                        <div class="form-group">
                            <label>Banner 1</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="auction_home_mid_section_banner[]" data-live-search="true" required>
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($mid_section_banner[0]) && $banner->id == $mid_section_banner[0] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Banner 2</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="auction_home_mid_section_banner[]" data-live-search="true" required>
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($mid_section_banner[1]) && $banner->id == $mid_section_banner[1] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Banner 3</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="auction_home_mid_section_banner[]" data-live-search="true" required>
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($mid_section_banner[2]) && $banner->id == $mid_section_banner[2] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shop By Brand</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading5" value="{{ old('heading5', $page->getTranslation('heading5', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="text-right">
                            <input type="hidden" name="page_type" value="highlights_section">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Associated With Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading6" value="{{ old('heading6', $page->getTranslation('heading6', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row @if ($lang != 'en') d-none @endif">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">{{ trans('messages.images') }}</label>
                            <div class="col-md-10">
                                <input type="file" name="images[]" multiple class="form-control" accept="image/*">

                                @if ($page->image)
                                    <div class="file-preview box sm">
                                        @php
                                            $photos = explode(',', $page->image);
                                        @endphp
                                        @foreach ($photos as $photo)
                                            <div
                                                class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                <div
                                                    class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                    <img src="{{ asset($photo) }}" class="img-fit">
                                                </div>
                                                <div class="remove">
                                                    <button class="btn btn-sm btn-link remove-galley"
                                                        data-url="{{ $photo }}" type="button">
                                                        <i class="la la-close"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-right">
                            <input type="hidden" name="page_type" value="highlights_section">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
           
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shop By Partners</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading7" value="{{ old('heading7', $page->getTranslation('heading7', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="text-right">
                            <input type="hidden" name="page_type" value="highlights_section">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
           

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Newsletter Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_type" value="auction_home_newsletter">
                        <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.heading') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.heading') }}" name="heading8" value="{{ old('heading8', $page->getTranslation('heading8', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.sub_heading') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.sub_heading') }}" name="heading9"  value="{{ old('heading9', $page->getTranslation('heading9', $lang)) }}" required>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card">


                <form class="p-4" action="{{ route('business_settings.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="page_id" value="{{ $page_id }}">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                    <div class="card-header px-0">
                        <h6 class="fw-600 mb-0">Seo Fields</h6>
                    </div>
                    <div class="card-body px-0">
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_title') }}</label>
                            <div class="col-sm-10">
                                <input type="text" @if($lang == 'ae') dir="rtl" @endif  class="form-control" placeholder="{{ trans('messages.meta_title') }}" name="meta_title"
                                    value="{{ $page->getTranslation('meta_title', $lang) }}">
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_description') }}</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.meta_description') }}" name="meta_description"  @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('meta_description',$lang) !!}</textarea>
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.meta_keywords') }}</label>
                            <div class="col-sm-10">
                                <textarea  @if($lang == 'ae') dir="rtl" @endif class="resize-off form-control" placeholder="{{ trans('messages.meta_keywords') }}" name="keywords">{!! $page->getTranslation('keywords',$lang) !!}</textarea>
                                <small class="text-muted">Separate with coma</small>
                            </div>
                        </div>
        
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_title') }}</label>
                            <div class="col-sm-10">
                                <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.og_title') }}"
                                    name="og_title" value="{{ $page->getTranslation('og_title',$lang) }}">
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.og_description') }}</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.og_description') }}" name="og_description" @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('og_description',$lang) !!}</textarea>
                            </div>
                        </div>
        
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{trans('messages.twitter_title') }}</label>
                            <div class="col-sm-10">
                                <input type="text"  @if($lang == 'ae') dir="rtl" @endif class="form-control" placeholder="{{ trans('messages.twitter_title') }}"
                                    name="twitter_title" value="{{ $page->getTranslation('twitter_title',$lang) }}">
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ trans('messages.twitter_description') }}</label>
                            <div class="col-sm-10">
                                <textarea class="resize-off form-control" placeholder="{{ trans('messages.twitter_description') }}"
                                    name="twitter_description" @if($lang == 'ae') dir="rtl" @endif >{!! $page->getTranslation('twitter_description',$lang) !!}</textarea>
                            </div>
                        </div>
        
                       
        
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            AIZ.plugins.bootstrapSelect('refresh');
        });

        $('.remove-galley').on('click', function() {
            thumbnail = $(this)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('page.delete_image') }}',
                data: {
                    url: $(thumbnail).data('url'),
                    id: '{{ $page->id }}'
                },
                success: function(data) {
                    if (data == 1) {
                        $(thumbnail).closest('.file-preview-item').remove();
                        AIZ.plugins.notify('success', "{{ trans('messages.image').trans('messages.deleted_msg') }}");
                    } else {
                        AIZ.plugins.notify('danger', "{{ trans('messages.something_went_wrong')}}");
                    }
                    
                }
            });
        });

    </script>
@endsection
