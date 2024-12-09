@extends('frontend.layouts.app')
@section('header')
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
@endsection
@section('content')
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <ol class="breadcrumb ">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">{{ __('messages.home') }} </a></li>

                <li class="breadcrumb__item "><a href="{{ route('vendor.myaccount') }}">{{ __('messages.my_account') }}</a>
                </li>
                <li class="breadcrumb__item active" aria-current="page"> <a
                        href="{{ route('vendor.products.create') }}">{{ __('messages.add_product') }}</a></li>
            </ol>
        </div>
        <!-- End container -->
    </div>

    <div class="product product-layout-3 add-product pb-0">
        <!-- Container -->
        <div class="container container--type-2">

            <div class="add-product-wrapper">
                <h1 class="form-title">Add New Product</h1>
                <form class="form form-horizontal mar-top" id="addNewProduct" action="{{ route('vendor.products.store') }}"
                    method="POST" enctype="multipart/form-data" id="choice_form">
                    @csrf
                    <div class="add-product-form d-flex gap-5">
                        <div class="product-basics">
                            <div class="product-image-wrapper">
                                <div class="product-images">
                                    <h2 class="add-product-section-title">
                                        {{ trans('messages.product') . ' ' . trans('messages.images') }}</h2>
                                    <div class="drop_box">
                                        <header>
                                            <h4>Select File here</h4>
                                        </header>
                                        <p>Files Supported: JPG, JPEG, PNG, GIF, BMP, TIFF</p>

                                        <label class=" col-form-label"
                                            for="signinSrEmail">{{ trans('messages.gallery_images') }}<small>({{ trans('messages.1000*1000') }})</small></label>
                                        <input type="file" name="gallery_images[]" multiple class="form-control"
                                            accept="image/*" id="fileID" required>
                                    </div>
                                    <div class="product-thumbnail mt-5">
                                        <div class="form-group m-0">
                                            <label for="signinSrEmail">{{ trans('messages.thumbnail_image') }}
                                                <small>({{ trans('messages.1000*1000') }})</small></label>
                                            <input type="file" id="thumbnail" name="thumbnail_image" accept="image/*"
                                                class="product-thumbnail-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class=" mb-5 form-section">
                                <h2 class="add-product-section-title">Product Information</h2>
                                <div class="form-group">
                                    <label for="product-name">Product Name <span>*</span></label>
                                    <input type="text" id="product-name" name="name" placeholder="Enter product name"
                                        required oninput="generateSlug()">
                                </div>
                                <div class="form-row d-grid">
                                    <div class="form-group">
                                        <label for="type">Type <span>*</span></label>
                                        <select id="type" name="type" required class="flat-select">
                                            <option value="">Select Type</option>
                                            <option value="sale">For Sale</option>
                                            <option value="rent">For Rent</option>
                                            <option value="auction">For auction</option>
                                        </select>
                                    </div>
                                    <div class="form-group " id="deposit-amount" style="display: none;">
                                        <label for="deposit">{{ trans('messages.refundable_deposit') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="deposit" id="deposit"
                                            placeholder="{{ __('messages.enter_deposit_amount') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="category">{{ trans('messages.category') }} <span
                                                class="text-danger">*</span></label>
                                        <select id="category" name="category_id" id="category_id" data-live-search="true"
                                            required>
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
                                <div class="form-row">
                                    <div class="form-group" id="brand">
                                        <label for="brand">{{ trans('messages.brand') }}</label>
                                        @php
                                            $brands = \App\Models\Brand::where('is_active', 1)
                                                ->orderBy('name', 'asc')
                                                ->get();
                                        @endphp
                                        <select class="form-control" name="brand_id" id="brand_id" data-live-search="true">
                                            <option value="">
                                                {{ trans('messages.select') . ' ' . trans('messages.brand') }}</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" id="occasion">
                                        <label for="occasion">{{ trans('messages.occasion') }}</label>
                                        @php
                                            $occasions = \App\Models\Occasion::where('is_active', 1)
                                                ->orderBy('name', 'asc')
                                                ->get();
                                        @endphp
                                        <select class="form-control aiz-selectpicker" name="occasion_id" id="occasion_id"
                                            data-live-search="true">
                                            <option value="">
                                                {{ trans('messages.select') . ' ' . trans('messages.occasion') }}</option>
                                            @foreach ($occasions as $occasion)
                                                <option value="{{ $occasion->id }}">{{ $occasion->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="unit">{{ trans('messages.unit') }}</label>
                                        <input type="text" id="unit" name="unit"
                                            placeholder="{{ trans('messages.unit_details') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="min-qty">{{ trans('messages.minimum_purchase_qty') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" id="min-qty" name="min_qty" value="1"
                                            lang="en" min="1"required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tags">{{ trans('messages.tags') }}</label>
                                    <input class="form-control" type="text" id="tags"name="tags[]"
                                        placeholder="{{ trans('messages.type_hit_enter_add_tag') }}">
                                    <small class="text-muted">{{ trans('messages.tag_details') }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="slug">{{ trans('messages.slug') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="slug" name="slug"
                                        placeholder="{{ trans('messages.slug') }}" required>
                                    @error('slug')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="vat">{{ trans('messages.vat') }} (%) </label>
                                    <input type="number" ilang="en" min="0" value="0" step="0.01"
                                        placeholder="VAT" name="vat" class="form-control">
                                </div>
                            </div>

                            <div class=" mb-5 form-section">
                                <h2 class="add-product-section-title">
                                    {{ trans('messages.product') . ' ' . trans('messages.discounts') }}</h2>

                                <div class="form-group">
                                    <label
                                        for="date_range">{{ trans('messages.discount') . ' ' . trans('messages.date') . ' ' . trans('messages.range') }}</label>
                                    <input type="text" id="date_range" name="date_range"
                                        placeholder="{{ trans('messages.select') . ' ' . trans('messages.date') }}"
                                        data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to "
                                        autocomplete="off">
                                </div>


                                <div class="form-group product-form-group">
                                    <label for="discount">{{ trans('messages.discount') }}</label>
                                    <div class="discount-field">
                                        <input class="flex-grow-1" type="number" lang="en" min="0"
                                            value="0" step="0.01" placeholder="{{ trans('messages.discount') }}"
                                            name="discount" class="form-control" />
                                        <select class="form-control discount-type" id="discount-type"
                                            name="discount_type">
                                            <option value="amount">{{ trans('messages.flat') }}</option>
                                            <option value="percent">{{ trans('messages.percent') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5 form-section">
                                <h2 class="add-product-section-title">
                                    {{ trans('messages.product') . ' ' . trans('messages.details') }}</h2>
                                <div class="form-group">
                                    <label
                                        for="product-type">{{ trans('messages.product') . ' ' . trans('messages.type') }}
                                        <span class="text-danger">*</span></label>
                                    <select id="product-type" name="product_type" required>
                                        <option value="single">{{ trans('messages.single') }}</option>
                                        <option value="variant">{{ trans('messages.variants') }}</option>
                                    </select>
                                </div>

                                <div class="form-group" id="attributes-section" style="display: none;">
                                    <input type="hidden" name="selected_attributes" id="selected_attributes">
                                    <label for="attributes">{{ trans('messages.attributes') }} <span
                                            class="text-danger">*</span></label>
                                    @php
                                        $attributes = \App\Models\Attribute::where('is_active', 1)
                                            ->orderBy('name', 'asc')
                                            ->get();
                                    @endphp
                                    <select id="attributes" class="form-select" name="main_attributes[]" multiple
                                        data-live-search="true">
                                        @foreach ($attributes as $attr)
                                            <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="product-variant">
                                    <h3 style="font-size: 24px; display: none;"id="pro_variant_name">
                                        {{ trans('messages.product') . ' ' . trans('messages.variant') }} </h3>

                                    <div class="form-group">
                                        <label for="sku">{{ trans('messages.sku') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" placeholder="{{ trans('messages.sku') }}" name="sku"
                                            class="form-control" required>
                                    </div>

                                    <div class="form-group" id="image-variant-section" style="display: none;">
                                        <label
                                            for="product-variant-image">{{ trans('messages.product') . ' ' . trans('messages.variant') . ' ' . trans('messages.image') }}
                                            <small>({{ trans('messages.1000*1000') }})</small></label>
                                        <input class="product-variant-image-input" type="file"
                                            id="product-variant-image" name="product_variant_image" accept="image/*">
                                    </div>
                                    <div class="form-row">

                                        <div class="form-group">
                                            <label for="quantity">{{ trans('messages.quantity') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" id="quantity" lang="en" min="0"
                                                value="0" step="0.01"
                                                placeholder="{{ trans('messages.quantity') }}" name="current_stock"
                                                class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="price">{{ trans('messages.price') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" id="price" lang="en" min="0"
                                                value="0" step="0.01"
                                                placeholder="{{ trans('messages.price') }}" name="price"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div id="product-attributes-container">
                                        <!-- Dynamically generated product attributes will be added here -->
                                    </div>

                                </div>
                                <div class="form-group add_variant">
                                    <input type="button" id="add-variant-btn" style="display:none;"
                                        class="btn product-images-upload-btn mt-4 z-btn-success action-btn"
                                        value="{{ trans('messages.add') . ' ' . trans('messages.product') . ' ' . trans('messages.variant') }}">
                                </div>

                            </div>


                            <div class=" mb-5 form-section">
                                <h2 class="add-product-section-title">
                                    {{ trans('messages.product') . ' ' . trans('messages.description') }}</h2>

                                <div class="form-group">
                                    <label for="rich-text-editor">{{ trans('messages.description') }}</label>
                                    <textarea class="text-editor" id="rich-text-editor" name="description"></textarea>
                                </div>

                            </div>

                            <div class="mb-5 form-section repeater">
                                <h2 class="add-product-section-title">
                                    {{ trans('messages.product') . ' ' . trans('messages.tabs') }}
                                </h2>

                                <div data-repeater-list="tabs">
                                    <div data-repeater-item>
                                        <div class="form-group">
                                            <label for="product-name">{{ trans('messages.heading') }}</label>
                                            <input type="text" class="form-control" name="tab_heading"
                                                placeholder="Enter heading">
                                        </div>
                                        <div class="form-group">
                                            <label for="rich-text-editor">{{ trans('messages.description') }}</label>
                                            <textarea class="text-editor w-100 p-3" id="rich-text-editor" placeholder="Enter description"
                                                name="tab_description"></textarea>
                                        </div>
                                        <div class="form-group row justify-content-end">
                                            <input data-repeater-delete type="button"
                                                style="width:auto;"class="btn btn-danger btn-sm delete-btn action-btn"
                                                value="{{ trans('messages.delete') }}">
                                        </div>
                                    </div>
                                </div>

                                <input data-repeater-create type="button"
                                    style="width:auto;"class="btn btn-success action-btn mt-0"
                                    value="{{ trans('messages.add') }}">
                            </div>


                            <div class=" mb-5 form-section">
                                <h2 class="add-product-section-title">
                                    {{ trans('messages.product') . ' ' . trans('messages.video') }}</h2>

                                <div class="form-group product-form-group">
                                    <label
                                        for="video-link">{{ trans('messages.video') . ' ' . trans('messages.provider') }}</label>
                                    <div class="video-field">
                                        <select class="video-type" name="video_provider" id="video_provider">
                                            <option value="youtube">{{ trans('messages.youtube') }}</option>
                                            <option value="vimeo">{{ trans('messages.vimeo') }}</option>
                                        </select>
                                        <input class="flex-grow-1" type="text" id="video" name="video"
                                            placeholder="Video link here">

                                    </div>
                                </div>
                            </div>
                            <div class="form-actions  d-flex justify-content-between product-actions mb-2">
                                <button type="submit"
                                    class=" btn-publish z-btn-primary text-white">{{ __('messages.add') }}</button>
                                <button type="submit"
                                    class=" btn-publish z-btn-danger text-dark">{{ __('messages.cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End container -->
        </div>
        <!-- End product -->



        <!-- Sticky add to cart -->

        <!-- End sticky add to cart -->
        <meta name="csrf-token" content="lAjxm1kVepQnQsr3myNg38QYkBpdf0KrBJpGF5MJ">

    </div>
@endsection
@section('script')
    {{-- rent deposit --}}
    <script>
        document.getElementById('type').addEventListener('change', function() {
            const depositAmount = document.getElementById('deposit-amount');
            if (this.value === 'rent') {
                depositAmount.style.display = 'block'; // Show the deposit amount field
            } else {
                depositAmount.style.display = 'none'; // Hide the deposit amount field
            }
        });
    </script>
    {{-- slug creation --}}
    <script>
        function generateSlug() {
            const productName = document.getElementById('product-name').value;
            const slug = productName
                .toLowerCase() // Convert to lowercase
                .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-'); // Remove multiple hyphens

            document.getElementById('slug').value = slug;
        }
        // Initialize Tagify
        var input = document.getElementById('tags');
        var tagify = new Tagify(input, {
            enforceWhitelist: false, // Allow custom tags
            delimiters: ",| ", // Specify delimiters for new tags
            originalInputValueFormat: valuesArr => JSON.stringify(valuesArr)
        });

        document.addEventListener("DOMContentLoaded", function() {
            const productTypeSelect = document.getElementById("product-type");
            const attributesSection = document.getElementById("attributes-section");
            const productVariantContainer = document.querySelector(".product-variant");
            const attributesSelect = document.getElementById("attributes");
            const attributesContainer = document.getElementById("product-attributes-container");
            const proVariantName = document.getElementById("pro_variant_name");
            const addVariantBtn = document.getElementById("add-variant-btn");
            const imgVariant = document.getElementById("image-variant-section");
          
            let variantCount = 0;
                      // Show or hide sections based on product type
            productTypeSelect.addEventListener("change", function() {
                if (this.value === "variant") {
                    attributesSection.style.display = "block";
                    productVariantContainer.style.display = "block";
                    proVariantName.style.display = "block";
                    addVariantBtn.style.display = "block";
                    imgVariant.style.display = "block";
                } else {
                    attributesSection.style.display = "none";
                    productVariantContainer.style.display = "";
                    proVariantName.style.display = "none";
                    addVariantBtn.style.display = "none";
                    imgVariant.style.display = "none";
                }
            });

            // Handle attribute selection changes
            attributesSelect.addEventListener("change", function() {
                attributesContainer.innerHTML = ""; // Clear previous attributes

                const selectedAttributes = Array.from(this.selectedOptions).map(option => option.value);
                document.getElementById("selected_attributes").value = JSON.stringify(selectedAttributes);

                // Fetch and display attribute values
                selectedAttributes.forEach(attributeId => {
                    fetch(`/vendor/get-attribute-values/${attributeId}`)
                        .then(response => response.json())
                        .then(data => {
                            const attributeDiv = document.createElement("div");
                            attributeDiv.classList.add("product_attributes");

                            const label = document.createElement("label");
                            label.innerHTML =
                                `${data.attribute_name} <span class="text-danger">*</span>`;
                            attributeDiv.appendChild(label);

                            const select = document.createElement("select");
                            select.name =
                                `variant_attributes[${variantCount}][${attributeId}][]`;
                            select.classList.add("form-select");

                            data.attribute_values.forEach(value => {
                                const option = document.createElement("option");
                                option.value = value.id;
                                option.textContent = value.name;
                                select.appendChild(option);
                            });

                            attributeDiv.appendChild(select);
                            attributesContainer.appendChild(attributeDiv);
                        })
                        .catch(error => console.error("Error fetching attribute values:", error));
                });
            });

            // Add functionality to add multiple variants
            document.getElementById("add-variant-btn").addEventListener("click", function() {
                const variantDiv = document.createElement("div");
                variantDiv.classList.add("variant-item");
                variantDiv.id = `variant_${variantCount}`; // Unique ID for each variant

                // Fetch selected attributes
                const selectedAttributes = Array.from(document.getElementById("attributes").selectedOptions)
                    .map(
                        option => ({
                            id: option.value,
                            name: option.text
                        })
                    );

                // Create the base structure for the variant
                variantDiv.innerHTML = `
        <h4>Product Variant ${variantCount + 1}</h4>
        <div class="form-group">
            <label for="sku_${variantCount}">SKU <span class="text-danger">*</span></label>
            <input type="text" name="variants[${variantCount}][sku]" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity_${variantCount}">Quantity <span class="text-danger">*</span></label>
            <input type="number" name="variants[${variantCount}][quantity]" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price_${variantCount}">Price <span class="text-danger">*</span></label>
            <input type="number" name="variants[${variantCount}][price]" class="form-control" required>
        </div>
        <div class="form-group">
             <label for="variant_image_${variantCount}">Variant Image <span class="text-danger">*</span></label>
             <input type="file" name="variants[${variantCount}][image]" class="form-control" accept="image/*" required>
       </div>
        <div class="variant-attributes">
        </div>
         <div class="form-group row">
                <div class="text-right">
                    <input type="button" class="btn btn-danger action-btn delete-variant-btn" 
                           value="Delete" data-target="variant_${variantCount}" />
                </div>
            </div>
    `;

                // Add attribute dropdowns
                const attributesContainer = variantDiv.querySelector(".variant-attributes");
                selectedAttributes.forEach(attribute => {
                    // Create a container for each attribute
                    const attributeDiv = document.createElement("div");
                    attributeDiv.classList.add("form-group");

                    // Add label
                    const label = document.createElement("label");
                    label.innerHTML = `${attribute.name} <span class="text-danger">*</span>`;
                    attributeDiv.appendChild(label);

                    // Create a dropdown for the attribute values
                    const select = document.createElement("select");
                    select.name = `variants[${variantCount}][attributes][${attribute.id}][]`;
                    select.classList.add("form-select");

                    // Fetch the attribute values dynamically
                    fetch(`/vendor/get-attribute-values/${attribute.id}`)
                        .then(response => response.json())
                        .then(data => {
                            data.attribute_values.forEach(value => {
                                const option = document.createElement("option");
                                option.value = value.id;
                                option.textContent = value.name;
                                select.appendChild(option);
                            });
                        })
                        .catch(error => console.error("Error fetching attribute values:", error));

                    attributeDiv.appendChild(select);
                    attributesContainer.appendChild(attributeDiv);
                });

                // Append the complete variantDiv to the container
                document.getElementById("product-attributes-container").appendChild(variantDiv);
                variantCount++;
            });
            document.getElementById("product-attributes-container").addEventListener("click", function(event) {
                if (event.target.classList.contains("delete-variant-btn")) {
                    const targetVariantId = event.target.getAttribute("data-target");
                    const variantElement = document.getElementById(targetVariantId);
                    if (variantElement) {
                        variantElement.remove(); // Remove the variant container
                    }
                }
            });

        });


        //tab repeater
        $(document).ready(function() {
            $('.repeater').repeater({
                initEmpty: false, // Ensures the first tab is already visible
                defaultValues: {
                    'tab_heading': '',
                    'tab_description': ''
                },
                show: function() {
                    $(this).slideDown(); // Animation when adding a new tab
                },
                hide: function(deleteElement) {
                    if (confirm('{{ trans('messages.are_you_sure') }}')) {
                        $(this).slideUp(deleteElement); // Animation when deleting a tab
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.repeater/jquery.repeater.min.js"></script>
     
    <script>
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d", // Customize date format
            onChange: function(selectedDates, dateStr, instance) {
                console.log("Selected dates:", selectedDates);
            }
        });
    </script>
    <script>
        // Function to initialize CKEditor for elements with a specific class
        function initializeCKEditorByClass(className) {
            const editors = document.getElementsByClassName(className);
            Array.from(editors).forEach((editor, index) => {
                // Ensure the element has a unique ID
                if (!editor.id) {
                    editor.id = `editor-${index}`; // Generate a unique ID if not present
                }
    
                // Initialize CKEditor
                CKEDITOR.replace(editor.id, {
                    height: 300,
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                        {
                            name: 'paragraph',
                            items: [
                                'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent',
                                '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'
                            ]
                        },
                        { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                        { name: 'colors', items: ['TextColor', 'BGColor'] },
                        { name: 'insert', items: ['Link', 'Image', 'Table'] },
                        { name: 'tools', items: ['Maximize'] }
                    ]
                });
            });
        }
    
        // Initialize CKEditor for all elements with the class 'text-editor'
        initializeCKEditorByClass('text-editor');
    </script>
    <script>
        // Intercept console warnings
        const originalConsoleWarn = console.warn;
        console.warn = function(message, ...args) {
            if (typeof message === 'string' && message.includes('This CKEditor 4.21.0 version is not secure')) {
                return; // Suppress the specific warning
            }
            originalConsoleWarn.apply(console, [message, ...args]);
        };
    </script>
    <script>
    console.warn = function() {}; // Suppress all warnings
</script>
    
@endsection
