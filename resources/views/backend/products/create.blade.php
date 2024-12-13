@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ trans('messages.add').' '.trans('messages.new').' '.trans('messages.product') }}</h5>
    </div>
    <div class="">
        <form class="form form-horizontal mar-top" id="addNewProduct" action="{{ route('products.store') }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                
                                <label class="col-md-3 col-from-label">{{ trans('messages.product').' '.trans('messages.name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" placeholder="{{ trans('messages.product').' '.trans('messages.name') }}"
                                    onkeyup="title_update(this)" required>
                                </div>
                            </div>
                            
                            <div class="form-group row" id="type">
                                <label class="col-md-3 col-from-label">{{ trans('messages.type') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="type" id="type-select"
                                        data-live-search="true" required>
                                        <option value="sale">For Sale</option>
                                        <option value="rent">For Rent</option>
                                        <option value="auction">For Auction</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="auction_date" style="display: none;">
                                <label class="col-sm-3 col-from-label" for="auction_date_range">{{ trans('messages.auction').' '.trans('messages.date').' '.trans('messages.range') }} <span
                                    class="text-danger">*</span> </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control aiz-date-range" id="auction_date_range"
                                        name="auction_date_range" placeholder="{{ trans('messages.select').' '.trans('messages.date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>
                            
                            <div class="form-group row" id="deposit-amount" style="display: none;">
                                <label class="col-md-3 col-from-label">{{ trans('messages.refundable_deposit') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="deposit" id="deposit"
                                        placeholder="{{__('messages.enter_deposit_amount')}}">
                                </div>
                            </div>
                            <div class="form-group row" id="category">
                                <label class="col-md-3 col-from-label">{{ trans('messages.category') }} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                        data-live-search="true" required  title="Select an option" >
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
                            <div class="form-group row" id="brand">
                                <label class="col-md-3 col-from-label">{{ trans('messages.brand') }}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    @php   
                                        $brands = \App\Models\Brand::where('is_active',1)->orderBy('name','asc')->get();
                                    @endphp
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true" required  title="Select an option" >
                                        {{-- <option value="">{{ trans('messages.select').' '.trans('messages.brand') }}</option> --}}
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" id="occasion">
                                <label class="col-md-3 col-from-label">{{ trans('messages.occasion') }}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    @php   
                                        $occasions = \App\Models\Occasion::where('is_active',1)->orderBy('name','asc')->get();
                                    @endphp
                                    <select class="form-control aiz-selectpicker" name="occasion_id" id="occasion_id"
                                        data-live-search="true" required  title="Select an option" >
                                        {{-- <option value="">{{ trans('messages.select').' '.trans('messages.occasion') }}</option> --}}
                                        @foreach ($occasions as $occasion)
                                            <option value="{{ $occasion->id }}">{{ $occasion->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.unit') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="unit"
                                        placeholder="{{ trans('messages.unit_details') }}" required>
                                </div>
                            </div>


                            <div class="form-group row" id="min_quantity_div">
                                <label class="col-md-3 col-from-label">{{ trans('messages.minimum_purchase_qty') }} <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" class="form-control" name="min_qty" value="1"
                                        min="1" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.tags') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="{{ trans('messages.type_hit_enter_add_tag') }}">
                                    <small class="text-muted">{{ trans('messages.tag_details') }}</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ trans('messages.slug') }}<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="{{ trans('messages.slug') }}" id="slug" name="slug" required
                                        class="form-control">
                                    @error('slug')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.vat') }} (%) </label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="VAT" name="vat" class="form-control">
                                </div>
                            </div> 

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.images') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.gallery_images') }}<small>({{ trans('messages.1000*1000') }})</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="gallery_images[]" multiple class="form-control"
                                        accept="image/*" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">{{ trans('messages.thumbnail_image') }}
                                    <small>({{ trans('messages.1000*1000') }})</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="thumbnail_image" class="form-control" accept="image/*" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="product_discount_div">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.discounts') }}</h5>
                        </div>
                        <div class="card-body">
                           
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="date_range">{{ trans('messages.discount').' '.trans('messages.date').' '.trans('messages.range') }}  </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control aiz-date-range" id="date_range"
                                        name="date_range" placeholder="{{ trans('messages.select').' '.trans('messages.date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.discount') }}</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="{{ trans('messages.discount') }}" name="discount" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="amount">{{ trans('messages.flat') }}</option>
                                        <option value="percent">{{ trans('messages.percent') }}</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                                            
                    <div class="card product-repeater">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.details') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row" id="product_typeDiv">
                                <label class="col-md-3 col-from-label">{{ trans('messages.product').' '.trans('messages.type') }} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select class="form-control aiz-selectpicker" name="product_type" id="product_type" required>
                                        <option value="single">{{ trans('messages.single') }}</option>
                                        <option value="variant">{{ trans('messages.variants') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="attributes">
                                <input type="hidden" name="selected_attributes" id="selected_attributes">
                                <label class="col-md-3 col-from-label">{{ trans('messages.attributes') }} <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    @php   
                                        $attributes = \App\Models\Attribute::where('is_active',1)->orderBy('name','asc')->get();
                                    @endphp
                                    <select class="form-control aiz-selectpicker" name="main_attributes[]" multiple id="main_attributes"  data-live-search="true">
                                        {{-- <option value="">{{ trans('messages.select').' '.trans('messages.attributes') }}</option> --}}
                                        @foreach ($attributes as $attr)
                                            <option value="{{ $attr->id }}">{{ $attr->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div data-repeater-list="products">
                                <div data-repeater-item>
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

                                    <div class="form-group row" id="current_stockDiv">
                                        <label class="col-md-3 col-from-label">{{ trans('messages.quantity') }} <span  class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ trans('messages.quantity') }}" name="current_stock" id="current_stock" class="form-control" required>
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
                                    <textarea class="aiz-text-editor" name="description"></textarea>
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
                                        <input data-repeater-delete type="button" class="btn btn-danger action-btn"
                                        value="{{ trans('messages.delete') }}" />
                                    </div> 
                                </div>
                            </div>
                            <input data-repeater-create type="button" class="btn btn-success action-btn"
                                value="{{ trans('messages.add') }}" />
                        </div>
                    </div>

                    

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ trans('messages.product').' '.trans('messages.video') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.video').' '.trans('messages.provider') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube">{{ trans('messages.youtube') }}</option>
                                        <option value="vimeo">{{ trans('messages.vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.video').' '.trans('messages.link') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="video_link"
                                        placeholder="{{ trans('messages.video').' '.trans('messages.link') }}">
                                    <small class="text-muted">
                                        {{-- Use proper link without extra parameter. Don't use short share link/embeded iframe code. --}}
                                        </small>
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
                                    <input type="text" class="form-control" name="meta_title"
                                        placeholder="{{ trans('messages.meta_title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.meta_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ trans('messages.meta_keywords') }}</label>
                                <div class="col-md-8">
                                    {{-- data-max-tags="1" --}}
                                    <input type="text" class="form-control aiz-tag-input" name="meta_keywords[]"
                                        placeholder="{{ trans('messages.type_hit_enter_add_keyword') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.og_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="og_title" placeholder="{{ trans('messages.og_title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.og_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="og_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="twitter_title"
                                        placeholder="{{ trans('messages.twitter_title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ trans('messages.twitter_description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="twitter_description" rows="8" class="form-control"></textarea>
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
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="submit" name="button" value="draft"
                                        class="btn btn-warning action-btn">{{ trans('messages.save_draft') }}</button>
                                </div>
                               
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="submit" name="button" value="publish"
                                        class="btn btn-success action-btn">{{ trans('messages.save_publish') }}</button>
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
                                        <input type="checkbox" name="return_refund" value="0">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group mr-2" role="group" aria-label="First group">
                            <button type="submit" name="button" value="draft" class="btn btn-warning action-btn">{{ trans('messages.save_draft') }}</button>
                        </div>
                        <!-- <div class="btn-group mr-2" role="group" aria-label="Third group">
                            <button type="submit" name="button" value="unpublish"
                                class="btn btn-primary action-btn">Save & Unpublish</button>
                        </div> -->
                        <div class="btn-group" role="group" aria-label="Second group">
                            <button type="submit" name="button" value="publish"
                                class="btn btn-success action-btn">{{ trans('messages.save_publish') }}</button>
                        </div>
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
        document.getElementById('type-select').addEventListener('change', function() {
            const depositField = document.getElementById('deposit-amount');
            const auction_date = document.getElementById('auction_date');
            const product_typeDiv = document.getElementById('product_typeDiv');
            const current_stockDiv = document.getElementById('current_stockDiv');
            const product_discount_div = document.getElementById('product_discount_div');
            const min_quantity_div = document.getElementById('min_quantity_div');
            
            auction_date.style.display = 'none'; 
            product_typeDiv.style.display = 'flex'; 
            depositField.style.display = 'none';
            current_stockDiv.style.display = 'flex'; 
            product_discount_div.style.display = 'flex'; 
            min_quantity_div.style.display = 'flex';

            if (this.value === 'rent') {
                depositField.style.display = 'flex'; // Show the deposit field
            }else if(this.value === 'auction'){
                product_typeDiv.style.display = 'none'; 
                auction_date.style.display = 'flex'; 
                $('#current_stock').val(1);
                current_stockDiv.style.display = 'none';
                product_discount_div.style.display = 'none'; 
                min_quantity_div.style.display = 'none'; 
            } else {
                depositField.style.display = 'none'; // Hide the deposit field
                document.getElementById('deposit').value = ''; // Clear the input field
                $('#current_stock').val(0);
            }
            
            $('#product_type').selectpicker('refresh');
        });
    </script>
    <script>
        $('.stone-div,.imageVariant').hide();

        $('.variant_images').removeAttr('required');
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



        $('.repeater').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();

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

            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
        });

        $('.product-repeater').repeater({
            initEmpty: false,
            isFirstItemUndeletable: true,
            show: function() {
                $(this).slideDown();

                var repeaterItems = $("div[data-repeater-item]");
                var repeatCount = repeaterItems.length;
                var count = parseInt(repeatCount) - 1;

                $('[name="products['+count+'][sku]"]').parent().parent().parent().find('#pro_variant_name').attr("id","pro_variant_name"+count);

                $('#pro_variant_name'+count).html('Product Variant '+repeatCount);
                $('.pro_variant_name').show();
                $('.imageVariant').show();
                // $('.variant_images').addAttr('required');
                $('.variant_images').prop('required', true);
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
                                        <select required class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="products['+count+'][choice_options_'+ i +']">\
                                            '+obj+'\
                                        </select>\
                                    </div>\
                                </div>');
                            
                            AIZ.plugins.bootstrapSelect('refresh');
                        }
                    });
                });

           
                $('[name="products['+count+'][stone_availability][]"]').attr("id","stone_availability"+count);
                $("#stone_availability"+count).prop('checked',false);
                
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

       

        $(document).on('change','#product_type',function(){
            if($(this).val() == 'variant'){
                $('.add_variant,#attributes,.pro_variant_name,.imageVariant').show();
                $('.variant_images').prop('required', true);
                $('#main_attributes').prop('required', true);
            }else{ 
                $('.add_variant,#attributes,.pro_variant_name,.imageVariant').hide();
                $('.variant_images').removeAttr('required');
                $('#main_attributes').removeAttr("required");
                $('div[data-repeater-item]').slice(1).remove();
                $('.product_attributes').html('');
                $('#main_attributes').selectpicker('deselectAll');
            }
        });

        let selected_attributes = [];
        $('#main_attributes').on('change', function() {
             $('.product_attributes').html(null);
            $.each($("#main_attributes option:selected"), function() {
                 add_more_customer_choice_option($(this).val(), $(this).text());
                 if( $.inArray($(this).val(), selected_attributes) == -1 ) {
                     selected_attributes.push($(this).val());
                 }
                 $('#selected_attributes').val(selected_attributes);
             });

           // const values = $(this).val();
           // // Remove all non selected from selected array if user has deselected something
          //  selected = selected.filter((value) => values.includes(value));
          //  // get value which is not in selected list
           // const lastSelected = values.filter((value) => !selected.includes(value));

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

                    // if( $.inArray(i, selected_attributes) !== -1 ) {
                        $('.product_attributes').append('\
                            <div class="form-group row">\
                                <div class="col-md-3">\
                                    <input type="text" class="form-control" name="choice_'+ i +'" value="'+name+'" placeholder="Choice Title" readonly>\
                                </div>\
                                <div class="col-md-8">\
                                    <select required class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'">\
                                        '+obj+'\
                                    </select>\
                                </div>\
                            </div>');
                    // }
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        }

        
    </script>

    <script type="text/javascript">
        $('form').bind('submit', function(e) {
            if ($(".action-btn").attr('attempted') == 'true') {
                //stop submitting the form because we have already clicked submit.
                e.preventDefault();
            } else {
                $(".action-btn").attr("attempted", 'true');
            }
            // Disable the submit button while evaluating if the form should be submitted
            // $("button[type='submit']").prop('disabled', true);

            // var valid = true;

            // if (!valid) {
            // e.preventDefault();

            ////Reactivate the button if the form was not submitted
            // $("button[type='submit']").button.prop('disabled', false);
            // }
        });

        function title_update(e) {
            title = e.value;
            title = title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')
            $('#slug').val(title)
        }

      
    </script>
@endsection
