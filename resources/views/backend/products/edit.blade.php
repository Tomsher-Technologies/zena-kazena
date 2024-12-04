@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="mb-0 h6">Edit Product</h5>
    </div>
    <div class="">
        <form class="form form-horizontal mar-top" action="{{ route('products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    @csrf
                    <div class="card">
                        <div class="card-body p-0">
                            <ul class="nav nav-tabs nav-fill border-light">
                                @foreach (\App\Models\Language::all() as $key => $language)
                                    <li class="nav-item">
                                        <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                                            href="{{ route('products.edit', ['id' => $product->id, 'lang' => $language->code]) }}">
                                            <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                                height="11" class="mr-1">
                                            <span>{{ $language->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class=" p-4">
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.product').' '.trans('messages.name') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="name" placeholder="{{ trans('messages.product').' '.trans('messages.name') }}"
                                            value="{{ $product->getTranslation('name',$lang) }}" required>
                                    </div>
                                </div>
                                <div class="form-group row @if ($lang != 'en') d-none @endif" id="category">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.category') }}<span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                            data-selected="{{ $product->category_id }}" data-live-search="true" required>
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

                                <div class="form-group row @if ($lang != 'en') d-none @endif" id="brand">
                                    <label class="col-md-3 col-from-label">{{ trans('messages.brand') }}</label>
                                    <div class="col-md-8">
                                        @php   
                                            $brands = \App\Models\Brand::where('is_active',1)->orderBy('name','asc')->get();
                                        @endphp
                                        <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                            data-live-search="true">
                                            <option value="">{{ trans('messages.select').' '.trans('messages.brand') }}</option>
                                            @foreach ($brands as $brand)
                                                <option @if ($product->brand_id == $brand->id) selected @endif value="{{ $brand->id }}">{{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="form-group row @if ($lang != 'en') d-none @endif" id="occasion">
                                    <label class="col-md-3 col-from-label">{{ trans('messages.occasion') }}</label>
                                    <div class="col-md-8">
                                        @php   
                                            $occasions = \App\Models\Occasion::where('is_active',1)->orderBy('name','asc')->get();
                                        @endphp
                                        <select class="form-control aiz-selectpicker" name="occasion_id" id="occasion_id"
                                            data-live-search="true">
                                            <option value="">{{ trans('messages.select').' '.trans('messages.occasion') }}</option>
                                            @foreach ($occasions as $occasion)
                                                <option @if ($product->occasion_id == $occasion->id) selected @endif value="{{ $occasion->id }}">{{ $occasion->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ trans('messages.unit') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="unit"
                                            placeholder="{{ trans('messages.unit_details') }}" value="{{ $product->getTranslation('unit',$lang) }}" required>
                                    </div>
                                </div>

                                <div class="form-group row @if ($lang != 'en') d-none @endif">
                                    <label class="col-lg-3 col-from-label">{{ trans('messages.minimum_purchase_qty') }}</label>
                                    <div class="col-lg-8">
                                        <input type="number" lang="en" class="form-control" name="min_qty"
                                            value="{{ $product->min_qty <= 1 ? 1 : $product->min_qty }}" min="1"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{ trans('messages.tags') }}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                            placeholder="{{ trans('messages.type_hit_enter_add_tag') }}" value="{{ $product->getTranslation('tags',$lang) }}" >
                                        <small class="text-muted">{{ trans('messages.tag_details') }}</small>
                                    </div>
                                </div>

                                <div class="form-group row @if ($lang != 'en') d-none @endif">
                                    <label class="col-md-3 col-form-label">{{ trans('messages.slug') }}<span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" placeholder="{{ trans('messages.slug') }}" id="slug" name="slug" required
                                            class="form-control" value="{{ $product->slug }}">
                                        @error('slug')
                                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row @if ($lang != 'en') d-none @endif">
                                    <label class="col-md-3 col-from-label">{{ trans('messages.vat') }} (%) </label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="VAT" name="vat" class="form-control"  value="{{ $product->vat }}">
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="card  @if ($lang != 'en') d-none @endif">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.images') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row @if ($lang != 'en') d-none @endif">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.gallery_images') }}<small>({{ trans('messages.1000*1000') }})</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="gallery_images[]" multiple class="form-control" accept="image/*">

                                    @if ($product->photos)
                                        <div class="file-preview box sm">
                                            @php
                                                $photos = explode(',', $product->photos);
                                            @endphp
                                            @foreach ($photos as $photo)
                                                <div
                                                    class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                    <div
                                                        class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                        <img src="{{ $product->image($photo) }}" class="img-fit">
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
                            <div class="form-group row @if ($lang != 'en') d-none @endif">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.thumbnail_image') }}
                                    <small>({{ trans('messages.1000*1000') }})</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="thumbnail_image" class="form-control" accept="image/*">

                                    @if ($product->thumbnail_img)
                                        <div class="file-preview box sm">
                                            <div
                                                class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                <div
                                                    class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                    <img src="{{ $product->image($product->thumbnail_img) }}"
                                                        class="img-fit">
                                                </div>
                                                <div class="remove">
                                                    <button class="btn btn-sm btn-link remove-thumbnail" type="button">
                                                        <i class="la la-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                       
                        {{-- <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
                            <div class="col-lg-8">
                                <div id="thumbnail_img">
                                    @if ($product->thumbnail_img != null)
                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                        <div class="img-upload-preview">
                                            <img loading="lazy"  src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" class="img-responsive">
                                            <input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                    </div>


                    <div class="card @if ($lang != 'en') d-none @endif">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.discounts') }}</h5>
                        </div>
                        <div class="card-body">
                            
                            @php
                                $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                                $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                            @endphp

                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label" for="start_date">{{ trans('messages.discount').' '.trans('messages.date').' '.trans('messages.range') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control aiz-date-range"
                                        @if ($product->discount_start_date && $product->discount_end_date) value="{{ $start_date . ' to ' . $end_date }}" @endif
                                        name="date_range" placeholder="{{ trans('messages.select').' '.trans('messages.date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.discount') }}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" min="0" step="0.01"
                                        placeholder="{{ trans('messages.discount') }}" name="discount" class="form-control"
                                        value="{{ $product->discount }}">
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="amount" <?php if ($product->discount_type == 'amount') {
                                            echo 'selected';
                                        } ?>>{{ trans('messages.flat') }}</option>
                                        <option value="percent" <?php if ($product->discount_type == 'percent') {
                                            echo 'selected';
                                        } ?>>{{ trans('messages.percent') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card product-repeater  @if ($lang != 'en') d-none @endif">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.details') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.product').' '.trans('messages.type') }} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select class="form-control aiz-selectpicker" name="product_type" id="product_type" required>
                                        <option @if($product->product_type == '0') selected @endif @if($product->product_type == '1') disabled @endif value="single">{{ trans('messages.single') }}</option>
                                        <option  @if($product->product_type == '1') selected @endif value="variant">{{ trans('messages.variants') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="attributes">
                                <label class="col-md-3 col-from-label">{{ trans('messages.attributes') }} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    @php   
                                        // $attributes = \App\Models\Attribute::where('is_active',1)->orderBy('name','asc')->pluck('name','id')->toArray();

                                        $attributes = \App\Models\Attribute::where('is_active', 1)
                                                    ->orderBy('name', 'asc')
                                                    ->get()
                                                    ->mapWithKeys(function ($attribute) {
                                                        // Use getTranslation to fetch the translated name for the current locale
                                                        return [$attribute->id => $attribute->getTranslation('name', app()->getLocale())];
                                                    })
                                                    ->toArray();
                                        $attrsProd = json_decode($product->attributes);
                                    @endphp
                                    
                                    <select class="form-control aiz-selectpicker" name="main_attributes[]" multiple id="main_attributes"  data-live-search="true">
                                        
                                        @foreach ($attributes as $attrKey => $attrN)
                                        
                                            @php 
                                                $selected = '';
                                                if(!empty($attrsProd) && in_array($attrKey, $attrsProd)){
                                                    $selected = 'selected';
                                                }
                                            @endphp
                                            <option {{ $selected }}  value="{{ $attrKey }}">{{ $attrN }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @foreach($product->stocks as $key => $stocks)
                                @php

                                // echo '<pre>';
                                // print_r($stocks);
                                // die;
                                    $varients_sku = $stocks->sku;
                                    $varients_current_stock = $stocks->qty;
                                    $varients_price = $stocks->price;
                                @endphp
                                    <div id="old_product{{$key}}" data-item>
                                        <div >
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <h6 class="pro_variant_name" id="pro_variant_name">{{ trans('messages.product').' '.trans('messages.variant') }} {{ $key+1 }}</h6>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group row">
                                                <label class="col-md-3 col-from-label">{{ trans('messages.sku') }} <span class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input type="hidden" name="oldproduct[{{$key}}][stock_id]" class="form-control" value="{{ $stocks->id }}">
                                                    <input type="text" placeholder="{{ trans('messages.sku') }}" name="oldproduct[{{$key}}][sku]" class="form-control" required value="{{ $varients_sku }}">
                                                </div>
                                            </div>

                                            <div class="form-group row  imageVariant">
                                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.product').' '.trans('messages.variant').' '.trans('messages.image') }}<small>({{ trans('messages.1000*1000') }})</small></label>
                                                <div class="col-md-8">
                                                    <input type="file" name="oldproduct[{{$key}}][variant_images]" class="form-control variant_images" accept="image/*" >

                                                    @if ($stocks->image)
                                                        <div class="file-preview box sm">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mt-2 file-preview-item">
                                                                <div
                                                                    class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                                    <img src="{{ $stocks->image($stocks->image) }}"
                                                                        class="img-fit">
                                                                </div>
                                                                <div class="remove">
                                                                    <button class="btn btn-sm btn-link remove-variant" type="button" data-id="{{$stocks->id}}" data-path="{{$stocks->image}}">
                                                                        <i class="la la-close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="old_product_attributes{{$key}} old_product_attribute" >
                                                @if(!empty($attrsProd))
                                                    @foreach($attrsProd as $ii => $aprod)

                                                        @php
                                                            $prodAttrValue = get_product_attrValue($aprod,$stocks->id);
                                                            $attrValues = get_attribute_values($aprod, $prodAttrValue);
                                                        @endphp
                                                        <div class="form-group row attr{{$aprod}}" >
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" name="oldproduct[{{$key}}][choice_{{$aprod}}]" value="{{ ($attributes[$aprod] ?? '') }}" placeholder="Choice Title" readonly>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <select required class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="oldproduct[{{$key}}][choice_options_{{$aprod}}]">
                                                                    {!! $attrValues !!}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 col-from-label">{{ trans('messages.quantity') }} <span  class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input type="number" lang="en" min="0"  step="0.01" placeholder="{{ trans('messages.quantity') }}" name="oldproduct[{{$key}}][current_stock]" class="form-control" required
                                                    value="{{ $varients_current_stock }}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 col-from-label">{{ trans('messages.price') }} <span class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <input type="number" lang="en" min="0" value="{{ $stocks->price }}" step="0.01" placeholder="{{ trans('messages.price') }}" name="oldproduct[{{$key}}][price]" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group row @if($product->product_type == '0') d-none @endif">
                                                <label class="col-md-3 col-from-label">{{ trans('messages.active').' '.trans('messages.status') }} <span class="text-danger">*</span></label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="oldproduct[{{$key}}][status]" id="status" data-live-search="true" required>
                                                        <option value="">{{ trans('messages.select').' '.trans('messages.status') }}</option>
                                                        <option @if($stocks->status == "1") selected @endif value="1">{{ trans('messages.active') }}</option>
                                                        <option @if($stocks->status == "0") selected @endif value="0">{{ trans('messages.inactive') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach


                            <div data-repeater-list="products">
                                <div data-repeater-item data-new-item>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <h6 class="pro_variant_name" id="pro_variant_name">{{ trans('messages.product').' '.trans('messages.variant') }} 1</h6>
                                        </div>
                                    </div>
                                
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ trans('messages.sku') }} <span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="{{ trans('messages.sku') }}" name="sku" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row imageVariant">
                                        <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.product').' '.trans('messages.variant').' '.trans('messages.image') }} <small>({{ trans('messages.1000*1000') }})</small></label>
                                        <div class="col-md-8">
                                            <input type="file" name="variant_images" class="form-control variant_images"
                                                accept="image/*" required>
                                        </div>
                                    </div>

                                    <div class="product_attributes" >
                                        
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ trans('messages.quantity') }} <span  class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ trans('messages.quantity') }}" name="current_stock" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ trans('messages.price') }} <span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ trans('messages.price') }}" name="price" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12 text-right">
                                            <input data-repeater-delete type="button" class="btn btn-danger action-btn" value="{{ trans('messages.delete') }}" />
                                        </div>
                                    </div>  
   
                                </div>
                            </div>
                            <div class="form-group row add_variant" >
                                <div class="col-md-12">
                                    <input data-repeater-create type="button" class="btn btn-success action-btn" value="{{ trans('messages.add').' '.trans('messages.product').' '.trans('messages.variant') }} " />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{trans('messages.description') }}</label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description',$lang) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card repeater">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.tabs') }}</h5>
                        </div>
                        <div class="card-body">
                            <div data-repeater-list="tabs">
                                <div data-repeater-item>
                                    <input type="hidden" name="tab_id">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ trans('messages.heading') }}</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="tab_heading">
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ trans('messages.description') }}</label>
                                        <div class="col-md-8">
                                            <textarea class="text-area" name="tab_description"></textarea>
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-9">
                                        </div>  
                                        <div class="col-md-3 ">
                                            <input data-repeater-delete type="button" class="btn btn-danger action-btn"
                                            value="{{ trans('messages.delete') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input data-repeater-create type="button" class="btn btn-success action-btn "
                                value="{{ trans('messages.add') }}" />
                        </div>
                    </div>

                    <div class="card @if ($lang != 'en') d-none @endif">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.video') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.video').' '.trans('messages.provider') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube" <?php if ($product->video_provider == 'youtube') {
                                            echo 'selected';
                                        } ?>>{{ trans('messages.youtube') }}</option>
                                        
                                        <option value="vimeo" <?php if ($product->video_provider == 'vimeo') {
                                            echo 'selected';
                                        } ?>>{{ trans('messages.vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.video').' '.trans('messages.link') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="video_link"
                                        value="{{ $product->video_link }}" placeholder="{{ trans('messages.video').' '.trans('messages.link') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.seo_section') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.meta_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control"
                                        value="{{ $product->getSeoTranslation('meta_title',$lang) }}" name="meta_title"
                                        placeholder="Meta Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.meta_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description" rows="8" class="form-control">{{ $product->getSeoTranslation('meta_description',$lang) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.meta_keywords') }}</label>
                                <div class="col-md-8">
                                    {{-- data-max-tags="1" --}}
                                    <input type="text" class="form-control aiz-tag-input" name="meta_keywords[]"
                                        placeholder="Type and hit enter to add a keyword"
                                        value="{{ $product->getSeoTranslation('meta_keywords',$lang) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.og_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="og_title" placeholder="{{ trans('messages.og_title') }}"
                                        value="{{ $product->getSeoTranslation('og_title',$lang) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.og_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="og_description" rows="8" class="form-control">{{ $product->getSeoTranslation('og_description',$lang)  }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="twitter_title"
                                        placeholder="{{ trans('messages.twitter_title') }}"
                                        value="{{ $product->getSeoTranslation('twitter_title',$lang) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="twitter_description" rows="8" class="form-control">{{ $product->getSeoTranslation('twitter_description',$lang) }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>

                <div class="col-lg-4">

                    <div class="card bg-transparent shadow-none border-0">
                        <div class="card-body p-0">
                            <div class="btn-toolbar justify-content-end" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="submit" name="button" value="publish"
                                        class="btn btn-info action-btn">{{ trans('messages.update').' '.trans('messages.product') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.return_refund').' '.trans('messages.status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{ trans('messages.status') }}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="return_refund" value="0" @if ($product->return_refund == 1) checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.publish').' '.trans('messages.status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-6 col-from-label">{{ trans('messages.status') }}</label>
                                        <div class="col-md-6">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox" name="published" value="1"
                                                    @if ($product->published == 1) checked @endif>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3 text-right">
                        <button type="submit" name="button" class="btn btn-info">{{ trans('messages.update').' '.trans('messages.product') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('styles')
<style>
    .pro_variant_name{
        text-decoration: underline;
        text-underline-position: under;
    }
</style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
        integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.deleteOld').on('click', function(){
            var deleteId = $(this).data('id');
            $('#old_product'+deleteId).remove();
        });

        $('.remove-variant').on('click', function() {
            thumbnail = $(this)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('products.delete_varient_image') }}',
                data: {
                    url: $(thumbnail).data('path'),
                    id: $(thumbnail).data('id')
                },
                success: function(data) {
                    $(thumbnail).closest('.file-preview-item').remove();
                }
            });

        });
        
        $('.remove-thumbnail').on('click', function() {
            thumbnail = $(this)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('products.delete_thumbnail') }}',
                data: {
                    id: '{{ $product->id }}'
                },
                success: function(data) {
                    $(thumbnail).closest('.file-preview-item').remove();
                }
            });

        });
        $('.remove-galley').on('click', function() {
            thumbnail = $(this)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{ route('products.delete_gallery') }}',
                data: {
                    url: $(thumbnail).data('url'),
                    id: '{{ $product->id }}'
                },
                success: function(data) {
                    $(thumbnail).closest('.file-preview-item').remove();
                }
            });
        });
    </script>

    @php
        $tabs = [];
        foreach ($product->tabs as $key => $tab) {
            $tabs[$key]['tab_id'] = $tab->id;
            $tabs[$key]['tab_heading'] = $tab->heading;
            $tabs[$key]['tab_description'] = $tab->content;
        }
        $productStockCount = count($product->stocks);
    @endphp

    

    <script>
       
        $('.add_variant,#attributes,.pro_variant_name').hide();
        let buttons = [
                    ["font", ["bold", "underline", "italic", "clear"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["style", ["style"]],
                    ["color", ["color"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "undo", "redo"]],
                ];
        $('.description-text-area').summernote({
            toolbar: buttons,
            height: 200,
            callbacks: {
                onImageUpload: function(data) {
                    data.pop();
                },
                onPaste: function(e) {
                    if (format) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window
                            .clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    }
                }
            }
        });

        var product_repeater = $('.product-repeater').repeater({
                            initEmpty: true,
                            isFirstItemUndeletable: true,
                            show: function() {
                                $(this).slideDown();

                                var repeaterItems = $("div[data-new-item]");
                                var repeatCount = repeaterItems.length;

                                var oldCount = $("div[data-item]").length;
                            //    alert('oldCount == '+oldCount);
                                var newCount = parseInt(repeatCount) + parseInt(oldCount);
                                // alert('repeatCount == '+repeatCount);
                                // alert('newCount == '+newCount);
                                var count = parseInt(repeatCount) - 1;

                                $('[name="products['+count+'][sku]"]').parent().parent().parent().find('#pro_variant_name').attr("id","pro_variant_name"+count);

                                $('#pro_variant_name'+count).html('Product Variant '+newCount);
                                $('.pro_variant_name').show();
                                $('.imageVariant').show();
                                // $('.variant_images').addAttr('required');
                                // $('.variant_images').prop('required', true);
                                $.each($("#main_attributes option:selected"), function() {
                                    var i = $(this).val();
                                    var name = $(this).text();
                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        type:"POST",
                                        url:'{{ route('products.add-attributes') }}',
                                        data:{
                                        attribute_id: i
                                        },
                                        success: function(data) {
                                            var obj = JSON.parse(data);
                                            $('[name="products['+count+'][variant_images]"]').parent().parent().parent().find(".product_attributes").first().append('\
                                                <div class="form-group row">\
                                                    <div class="col-md-3">\
                                                        <input type="text" class="form-control" name="products['+count+'][choice_'+ i +']" value="'+name+'" placeholder="Choice Title" readonly>\
                                                    </div>\
                                                    <div class="col-md-8">\
                                                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="products['+count+'][choice_options_'+ i +']">\
                                                            '+obj+'\
                                                        </select>\
                                                    </div>\
                                                </div>');
                                            AIZ.plugins.bootstrapSelect('refresh');
                                        }
                                    });
                                });

                        
                                
                                $(this).find('.note-editor').remove();
                                note = $(this).find('.description-text-area').summernote({
                                    toolbar: buttons,
                                    height: 200,
                                    callbacks: {
                                        onImageUpload: function(data) {
                                            data.pop();
                                        },
                                        onPaste: function(e) {
                                            if (format) {
                                                var bufferText = ((e.originalEvent || e).clipboardData || window
                                                    .clipboardData).getData('Text');
                                                e.preventDefault();
                                                document.execCommand('insertText', false, bufferText);
                                            }
                                        }
                                    }
                                });
                            },
                            hide: function(deleteElement) {
                                if (confirm('Are you sure you want to delete this element?')) {
                                    $(this).slideUp(deleteElement);
                                }
                            },
                        });

        
        var productType = '{{ $product->product_type }}';
       
        if(productType == '1'){
            $('.add_variant,#attributes,.pro_variant_name,.imageVariant').show();
            $('#main_attributes').prop('required', true);
            // $('.variant_images').prop('required', true);
        }else{
            $('.imageVariant').hide();
        }


        var repeater = $('.repeater').repeater({
            initEmpty: true,
            show: function() {
                note = $(this).find('.text-area').summernote({
                    toolbar: buttons,
                    height: 200,
                    callbacks: {
                        onImageUpload: function(data) {
                            data.pop();
                        },
                        onPaste: function(e) {
                            if (format) {
                                var bufferText = ((e.originalEvent || e).clipboardData || window
                                    .clipboardData).getData('Text');
                                e.preventDefault();
                                document.execCommand('insertText', false, bufferText);
                            }
                        }
                    }
                });

                var nativeHtmlBuilderFunc = note.summernote('module', 'videoDialog').createVideoNode;

                note.summernote('module', 'videoDialog').createVideoNode = function(url) {
                    var wrap = $('<div class="embed-responsive embed-responsive-16by9"></div>');
                    var html = nativeHtmlBuilderFunc(url);
                    html = $(html).addClass('embed-responsive-item');
                    return wrap.append(html)[0];
                };

                $(this).slideDown();

            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
        });

        repeater.setList({!! json_encode($tabs) !!});


        $(document).on('change','#product_type',function(){
            if($(this).val() == 'variant'){
                $('.add_variant,#attributes,.pro_variant_name,.imageVariant').show();
                $('#main_attributes').prop('required', true);
                // $('.variant_images').prop('required', true);
            }else{ 
                $('.add_variant,#attributes,.pro_variant_name,.imageVariant').hide();
                $('div[data-new-item]').remove();
                $('.variant_images').removeAttr('required');
                $('#main_attributes').removeAttr("required");
                $('.product_attributes,.old_product_attribute').html('');
                $('#main_attributes').selectpicker('deselectAll');
            }
            AIZ.plugins.bootstrapSelect('refresh');
        });

        // $('#main_attributes').on('change', function() {
        //     alert($(this).val());
        //     // $('.product_attributes').html(null);
        //     // $.each($("#main_attributes option:selected"), function() {
        //     //     add_more_customer_choice_option($(this).val(), $(this).text());
        //     // });

        // });

        $("#main_attributes").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
            var sel = $(this).find('option').eq(clickedIndex).val();
            console.log(sel+" "+newValue);

            var text = $(this).find('option').eq(clickedIndex).text();
            // console.log(e);
            // console.log('clickedIndex   ========  '+clickedIndex);
            // console.log('Value   ======= '+sel);
            // console.log('Text    ======== '+text);
            if(newValue == true){
                add_more_customer_choice_option(sel, text);
            }else{
                $('.attr'+sel).remove();
            }
        });

        function add_more_customer_choice_option(i, name){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"POST",
                url:'{{ route('products.add-attributes') }}',
                data:{
                attribute_id: i
                },
                success: function(data) {
                    var obj = JSON.parse(data);

                    var productStockCount = {{ $productStockCount }};
                    if(productStockCount != 0){
                        for(j=0; j<productStockCount; j++){
                            $('.old_product_attributes'+j).append('\
                                <div class="form-group row attr'+i+'" >\
                                    <div class="col-md-3">\
                                        <input type="text" class="form-control" name="oldproduct['+j+'][choice_'+i+']" value="'+name+'" placeholder="Choice Title" readonly>\
                                    </div>\
                                    <div class="col-md-8">\
                                        <select required class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="oldproduct['+j+'][choice_options_'+i+']">\
                                            '+obj+'\
                                        </select>\
                                    </div>\
                                </div>');
                        }
                    }
                   
                   // var stockCount = $product->stocks
                    $('.product_attributes').append('\
                        <div class="form-group row attr'+i+'">\
                            <div class="col-md-3">\
                                <input type="text" class="form-control" name="choice_'+ i +'" value="'+name+'" placeholder="Choice Title" readonly>\
                            </div>\
                            <div class="col-md-8">\
                                <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'">\
                                    '+obj+'\
                                </select>\
                            </div>\
                        </div>');
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        }

    </script>

    <script type="text/javascript">
        
      
        function delete_variant(em) {
            $(em).closest('.variant').remove();
        }

     

        AIZ.plugins.tagify();

        $(document).ready(function() {
            

            $('.remove-files').on('click', function() {
                $(this).parents(".col-md-4").remove();
            });
        });

      
    </script>
@endsection
