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
                <h1 class="form-title">Edit Product</h1>
                <form class="form form-horizontal mar-top" id="addNewProduct"
                    action="{{ route('vendor.products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data" id="choice_form">
                    @csrf
                    <div class="add-product-form d-flex gap-5">
                        <div class="product-basics">
                            <div class="mb-5 form-section">
                                <h2 class="add-product-section-title">Product Information</h2>
                                <!-- Product Name (English) -->
                                <div class="form-group">
                                    <label for="product-name">Product Name <span>*</span></label>
                                    <input type="text" id="product-name" name="name"value="{{$product->name}}" placeholder="Enter product name"
                                        required oninput="generateSlug()">
                                </div>

                                <!-- Product Name (Arabic) -->
                                <div class="form-group">
                                    <label for="product-name-ar">اسم المنتج (Arabic) <span>*</span></label>
                                    <input type="text" id="product-name-ar" name="name_ar" placeholder="أدخل اسم المنتج"
                                        required>
                                </div>

                                <!-- Slug -->
                                <div class="form-group">
                                    <label for="slug">{{ trans('messages.slug') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="slug" name="slug"
                                        placeholder="{{ trans('messages.slug') }}" required>
                                    @error('slug')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="mb-5 form-section">
                                <h2 class="add-product-section-title">
                                    {{ trans('messages.product') . ' ' . trans('messages.description') }}</h2>

                                <!-- Product Description (English) -->
                                <div class="form-group">
                                    <label for="rich-text-editor">{{ trans('messages.description') }}</label>
                                    <textarea class="text-editor" id="rich-text-editor" name="description">{{$product->name}}</textarea>
                                </div>

                                <!-- Product Description (Arabic) -->
                                <div class="form-group">
                                    <label for="rich-text-editor-ar">{{ trans('messages.description') }} (Arabic)</label>
                                    <textarea class="text-editor" id="rich-text-editor-ar" name="description_ar"></textarea>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions d-flex justify-content-between product-actions mb-2">
                                <button type="submit"
                                    class="btn-publish z-btn-primary text-white">{{ __('messages.update') }}</button>
                                <button type="button" class="btn-publish z-btn-danger text-dark"
                                    onclick="window.history.back();">{{ __('messages.cancel') }}</button>
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.repeater/jquery.repeater.min.js"></script>

    <script>
        flatpickr("#date-range", {
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
            Array.from(editors).forEach(editor => {
                CKEDITOR.replace(editor.id, {
                    height: 300,
                    toolbar: [{
                            name: 'basicstyles',
                            items: ['Bold', 'Italic', 'Underline', 'Strike']
                        },
                        {
                            name: 'paragraph',
                            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent',
                                '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight',
                                'JustifyBlock'
                            ]
                        },
                        {
                            name: 'styles',
                            items: ['Format', 'Font', 'FontSize']
                        },
                        {
                            name: 'colors',
                            items: ['TextColor', 'BGColor']
                        },
                        {
                            name: 'insert',
                            items: ['Link', 'Image', 'Table']
                        },
                        {
                            name: 'tools',
                            items: ['Maximize']
                        }
                    ]
                });
            });
        }

        // Initialize CKEditor for all elements with the class 'text-editor'
        initializeCKEditorByClass('text-editor');
    </script>
@endsection
