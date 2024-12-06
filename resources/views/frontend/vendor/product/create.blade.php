@extends('frontend.layouts.app')
@section('content')
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>


                    <li class="breadcrumb-item"><a href="productsab77.html?category=party-wear">Party Wear</a></li>


                    <li class="breadcrumb-item active" aria-current="page">Generic Diamond Pendant Necklace</li>

                </ol>
            </nav>

            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>

    <div class="product product-layout-3 add-product pb-0">
        <!-- Container -->
        <div class="container container--type-2">

            <div class="add-product-wrapper">
                <h1 class="form-title">Add New Product</h1>
                <div class="add-product-form d-flex gap-5">
                    <div class="product-basics">
                        <div class="product-image-wrapper">
                            <div class="product-images">
                                <h2 class="add-product-section-title">Upload product images</h2>
                                <div class="drop_box">
                                    <header>
                                        <h4>Select File here</h4>
                                    </header>
                                    <p>Files Supported: JPG, JPEG, PNG, GIF, BMP, TIFF</p>
                                    <input type="file" hidden accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" id="fileID">
                                    <button class="btn product-images-upload-btn z-btn-primary"
                                        onclick="document.getElementById('fileID').click()">Choose File</button>
                                </div>
                                <div class="product-thumbnail mt-5">
                                    <div class="form-group m-0">
                                        <label for="thumbnail">Thumbnail Image (1000x1000)</label>
                                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                                            class="product-thumbnail-input">
                                    </div>
                                </div>
                            </div>

                            <div class="product-status mt-3">
                                <h2 class="add-product-section-title">Return & Refund Status</h2>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="me-2" for="flexSwitchCheckDefault"
                                        onclick="document.getElementById('flexSwitchCheckDefault').click()">Status</span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input product-check-input" type="checkbox" role="switch"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <form class=" mb-5 form-section">
                            <h2 class="add-product-section-title">Product Information</h2>
                            <div class="form-group">
                                <label for="product-name">Product Name <span>*</span></label>
                                <input type="text" id="product-name" name="product-name" placeholder="Enter product name"
                                    required>
                            </div>
                            <div class="form-row d-grid">
                                <div class="form-group">
                                    <label for="type">Type <span>*</span></label>
                                    <select id="type" name="type" required class="flat-select">
                                        <option value="">Select Type</option>
                                        <option value="for-sale">For Sale</option>
                                        <option value="for-rent">For Rent</option>
                                    </select>
                                </div>
                                <div class="form-group hidden">
                                    <label for="category">Refundable Deposit <span>*</span></label>
                                    <select id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="party-wear">Party Wear</option>
                                        <option value="casual-wear">Casual Wear</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category <span>*</span></label>
                                    <select id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="party-wear">Party Wear</option>
                                        <option value="casual-wear">Casual Wear</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <select id="brand" name="brand">
                                        <option value="">Select Brand</option>
                                        <option value="brand-a">Brand A</option>
                                        <option value="brand-b">Brand B</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="occasion">Occasion</label>
                                    <select id="occasion" name="occasion">
                                        <option value="">Select Occasion</option>
                                        <option value="wedding">Wedding</option>
                                        <option value="party">Party</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="unit">Unit</label>
                                    <input type="text" id="unit" name="unit" placeholder="e.g., KG, Pc">
                                </div>
                                <div class="form-group">
                                    <label for="min-qty">Minimum Purchase Qty <span>*</span></label>
                                    <input type="number" id="min-qty" name="min-qty" value="1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <input type="text" id="tags" name="tags"
                                    placeholder="Add tags and press Enter">
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug <span>*</span></label>
                                <input type="text" id="slug" name="slug" placeholder="Enter unique slug"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="vat">VAT (%)</label>
                                <input type="number" id="vat" name="vat" placeholder="e.g., 5"
                                    min="0">
                            </div>
                        </form>

                        <form class=" mb-5 form-section">
                            <h2 class="add-product-section-title">Product Discounts</h2>

                            <div class="form-group">
                                <label for="date-range">Date Range <span>*</span></label>
                                <input type="text" id="date-range" name="date-range" placeholder="Select Date Range"
                                    required>
                            </div>


                            <div class="form-group product-form-group">
                                <label for="discount">Discount <span>*</span></label>
                                <div class="discount-field">
                                    <input class="flex-grow-1" type="number" id="discount" name="discount"
                                        placeholder="Enter discount value" required min="0" />
                                    <select class="discount-type" id="discount-type" name="discount-type" required>
                                        <option value="flat">Flat</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                            </div>

                        </form>



                        <form class="mb-5 form-section">
                            <h2 class="add-product-section-title">Product Details</h2>


                            <div class="form-row">
                                <div class="form-group">
                                    <label for="product-type">Product Type *</label>
                                    <select id="product-type" name="product-type" required>
                                        <option value="single">Single</option>
                                        <option value="variants">Variants</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="attributes">Attributes *</label>
                                    <select id="attributes" name="attributes" required>
                                        <option value="">Nothing Selected</option>
                                        <option value="color">Color</option>
                                        <option value="size">Size</option>
                                        <option value="material">Material</option>
                                    </select>
                                </div>
                            </div>

                            <div class="product-variant">
                                <h3 style="font-size: 24px;">Product Variant 1</h3>

                                <div class="form-group">
                                    <label for="sku">SKU *</label>
                                    <input type="text" id="sku" name="sku" required>
                                </div>

                                <div class="form-group">
                                    <label for="product-variant-image">Product Variant Image (1000x1000)</label>
                                    <input class="product-variant-image-input" type="file" id="product-variant-image"
                                        name="product-variant-image" accept="image/*">
                                </div>
                                <div class="form-row">

                                    <div class="form-group">
                                        <label for="quantity">Quantity *</label>
                                        <input type="number" id="quantity" name="quantity" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="price">Price *</label>
                                        <input type="number" id="price" name="price" required>
                                    </div>

                                </div>

                            </div>

                            <button class="btn product-images-upload-btn mt-4 z-btn-primary">Add Product
                                Variant</button>

                        </form>


                        <form class=" mb-5 form-section">
                            <h2 class="add-product-section-title">Product Description</h2>

                            <div class="form-group">
                                <label for="rich-text-editor">Description <span>*</span></label>
                                <textarea class="text-editor" id="rich-text-editor" name="rich-text-editor" required></textarea>
                            </div>

                        </form>

                        <form class=" mb-5 form-section">
                            <h2 class="add-product-section-title">Product Tabs</h2>

                            <div class="form-group">
                                <label for="product-name">Heading <span>*</span></label>
                                <input type="text" id="product-name" name="product-name"
                                    placeholder="Enter product name" required>
                            </div>
                            <div class="form-group">
                                <label for="rich-text-editor">Description <span>*</span></label>
                                <textarea class="text-editor w-100 p-3" id="rich-text-editor" placeholder="Enter here.." name="rich-text-editor"
                                    required></textarea>
                            </div>
                            <div class="action-buttons d-flex justify-content-between">
                                <button class="btn btn-delete z-btn-secondary">Delete</button>
                                <button class="btn btn-add z-btn-primary">Add</button>
                            </div>

                        </form>

                        <form class=" mb-5 form-section">
                            <h2 class="add-product-section-title">Product Video</h2>

                            <div class="form-group product-form-group">
                                <label for="video-link">Video Link<span>*</span></label>
                                <div class="video-field">
                                    <select class="video-type" id="video-type" name="video-type" required>
                                        <option value="vimeo">Vimeo</option>
                                        <option value="youtube">Youtube</option>
                                    </select>
                                    <input class="flex-grow-1" type="text" id="video" name="video"
                                        placeholder="Video link here" required min="0" />

                                </div>
                            </div>

                        </form>


                        <form class="mb-5">
                            <h2 class="add-product-section-title">SEO Section</h2>

                            <div class="form-group">
                                <label for="product-name">Meta Title<span>*</span></label>
                                <input type="text" id="product-name" name="product-name"
                                    placeholder="Enter product name" required>
                            </div>

                            <div class="form-group">
                                <label for="rich-text-editor">Meta Description <span>*</span></label>
                                <textarea class="text-editor w-100 p-3" id="rich-text-editor" placeholder="Enter here.." name="rich-text-editor"
                                    required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="keywords">Keywords</label>
                                <div class="keywords-tags-field">
                                    <div class="keywords-tags">
                                        <!-- Keywords tags will be added here dynamically -->
                                    </div>
                                    <input type="text" id="keywords" name="keywords" class="keywords-input"
                                        placeholder="Type a keyword and press Enter" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="product-name">OG Title<span>*</span></label>
                                <input type="text" id="product-name" name="product-name"
                                    placeholder="Enter product name" required>
                            </div>

                            <div class="form-group">
                                <label for="rich-text-editor">OG Description <span>*</span></label>
                                <textarea class="text-editor w-100 p-3" id="rich-text-editor" placeholder="Enter here.." name="rich-text-editor"
                                    required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="product-name">Twitter<span>*</span></label>
                                <input type="text" id="product-name" name="product-name"
                                    placeholder="Enter product name" required>
                            </div>

                            <div class="form-group">
                                <label for="rich-text-editor">Twitter Description <span>*</span></label>
                                <textarea class="text-editor w-100 p-3" id="rich-text-editor" placeholder="Enter here.." name="rich-text-editor"
                                    required></textarea>
                            </div>


                        </form>


                        <div class="form-actions  d-flex justify-content-between product-actions">
                            <button type="submit" class="btn btn-draft z-btn-secondary">Save As Draft</button>
                            <button type="submit" class="btn btn-publish z-btn-primary">Save & Publish</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End container -->
        </div>
        <!-- End product -->



        <!-- Sticky add to cart -->

        <!-- End sticky add to cart -->
        <meta name="csrf-token" content="lAjxm1kVepQnQsr3myNg38QYkBpdf0KrBJpGF5MJ">

        <!-- Footer collection -->
        <footer class="modern-footer collection-footer">

            <!-- Newsletter -->
            <div class="blog-with-sidebar__newsletter">
                <!-- Container -->
                <div class="container">
                    <!-- Row -->
                    <div class="row blog-newsletter">
                        <div class="col-lg-12">
                            <!-- Newsletter Title -->
                            <h3 class="blog-newsletter__title font-family-jost text-center">NEWSLETTER</h3>
                            <!-- End newsletter title -->
                        </div>

                        <div class="col-lg-6">
                            <p class="newsletter-text-area">Sign up to be aware of our new products and all
                                developments!</p>
                        </div>
                        <div class="col-lg-6">
                            <!-- Newsletter form -->
                            <form class="blog-newsletter__form" id="newsletterForm">
                                <input type="email" placeholder="email" name="email"
                                    class="blog-newsletter__input" />
                                <button type="submit" class="blog-newsletter__submit">Subscribe</button>
                            </form>
                            <div id="newsletterMessage"></div>
                            <!-- End newsletter form -->
                        </div>
                    </div>
                    <!-- End row -->
                </div>
                <!-- End container -->
            </div>
            <!-- End newsletter -->

            <!-- Container -->
            <div class="container">

                <!-- Menu -->
                <ul class="modern-footer__menu">
                    <li><a href="index.html">Home </a></li>
                    <li><a href="about.html">About Us </a></li>
                    <li><a href="products.html">Shop </a></li>
                    <li><a href="terms.html">Terms &amp; Conditions </a></li>
                    <li><a href="privacy.html">Privacy Policy</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
                <!-- End menu -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-4 mobile-order-2">
                        <!-- Social -->
                        <div class="modern-footer__social">
                            <p>Follow Us</p>
                            <ul>
                                <li><a href="https://www.instagram.com/"><img src="assets/images/instagram.svg"
                                            alt=""></a></li>
                                <li><a href="https://www.facebook.com/"><img src="assets/images/facebook.svg"></a>
                                </li>
                                <li><a href="https://www.youtube.com/"><img src="assets/images/youtube.svg"></a>
                                </li>
                                <li><a href="https://www.linkedin.com/"><img src="assets/images/LinkedIn.svg"></a>
                                </li>
                            </ul>
                        </div>
                        <!-- End social -->
                    </div>
                    <div class="col-lg-4">
                        <!-- Logo -->
                        <div class="modern-footer__logo">
                            <img width="250" src="assets/img/logow.png" alt="">
                        </div>
                        <!-- End logo -->
                        <!-- Address -->
                        <div class="modern-footer__address">

                            <ul>
                                <li><a href="#"><img width="40" src="assets/images/email.svg"
                                            alt=""></a></li>
                                <li><a href="#"><img width="40" src="assets/images/chat.svg"></a></li>
                                <li><a href="#"><img width="40" src="assets/images/phone.svg"></a></li>
                                <li><a href="#"><img width="40" src="assets/images/visit.svg"></a></li>
                            </ul>
                        </div>
                        <!-- End address -->
                        <!-- Payment -->
                        <div class="modern-footer__payment d-none d-lg-block">
                            <img src="storage/uploads/all/WRYRNuOPrWCltCwo0gpOmHDPrcFPCqnS2ncekqMQ.svg" alt="Payment" />
                        </div>
                        <!-- End payment -->
                    </div>
                    <div class="col-lg-4 mobile-order-3">
                        <!-- Currency -->
                        <div class="modern-footer__currency">
                            <p>JOIN WITH US</p>

                            <div class="become_promotor" bis_skin_checked="1"><a href="#"><i
                                        class="lnr lnr-bullhorn"></i>Become Promotor
                                </a></div>
                            <div class="become_partner" bis_skin_checked="1"><a href="#" data-bs-toggle="modal"
                                    data-bs-target="#becomePartnerModal"><i class="lnr lnr-thumbs-up"></i>Become
                                    Partner
                                </a></div>


                        </div>
                        <!-- End currency -->
                    </div>
                </div>
                <!-- End row -->
                <!-- Payment -->
                <div class="modern-footer__payment d-block d-lg-none">
                    <img src="assets/images/payment.svg" alt="Payment" />
                </div>
                <!-- End payment -->


                <!-- Copyright -->
                <div class="modern-footer__copyright"> © 2024 ZK. All rights reverved. Design By TOMSHER</div>
                <!-- End copyright -->

            </div>

            <!-- End container -->
        </footer>
        <div class="modal fade" id="becomePartnerModal" tabindex="-1" aria-labelledby="authModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="authModalLabel">Become Partner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body text-center">
                        <p>Choose Option</p>
                        <div class="d-grid gap-2">
                            <ul class="canvas-menu__nav horizontal-menu">
                                <li><a href="vendor-login.html" class="canvas-nav__item"><i class="lnil lnil-user"></i>
                                        Login</a></li>
                                <li><a href="vendor-registration.html" class="canvas-nav__item"><i
                                            class="lnil lnil-user"></i> Register</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End footer collection -->

        <div class="search-popup js-search-popup">
            <!-- Search close -->
            <div class="search-popup__close">
                <a href="#" class="js-close-search-popup"><i class="lnr lnr-cross"></i></a>
            </div>
            <!-- End search close -->
            <!-- Container -->
            <div class="container container--type-2">
                <!-- Search title -->

                <!-- End search categories -->
                <!-- Search form -->
                <form class="search-popup__form" action="https://zena.tomsher.net/products" method="get">
                    <!-- Search input -->
                    <input type="text" class="search-popup__input" name="search" placeholder="Search here..." />
                    <!-- End search input -->
                </form>
                <!-- End search form -->
                <!-- Search results -->

                <!-- End search results -->
            </div>
            <!-- End container -->
        </div>
    </div>
@endsection
@section('script')
    <script>
        if ($('#lang-change').length > 0) {
            $('#lang-change').on('change', function(e) {
                e.preventDefault();
                var $this = $(this);
                var locale = $this.val();
                $.post('language_change.html', {
                    _token: 'lAjxm1kVepQnQsr3myNg38QYkBpdf0KrBJpGF5MJ',
                    locale: locale
                }, function(data) {
                    location.reload();
                });
            });
        }

        var productDetailRoute =
            "product-detaila022.html?slug=__slug__&amp;sku=__sku__"; // this will be a placeholder
        $(".js-open-canvas-cart").on("click", function() {

            $.get('cart.json', {
                _token: 'lAjxm1kVepQnQsr3myNg38QYkBpdf0KrBJpGF5MJ'
            }, function(data) {
                console.log();
                // location.reload();
                var productRow = '';
                $.each(data.products, function(index, product) {

                    var productLink = productDetailRoute.replace('__slug__', product.product
                        .slug).replace('__sku__', product.product.sku);

                    var attributeHTML = '';

                    $.each(product.product.attributes, function(attrIndex, attribute) {
                        attributeHTML +=
                            `<span class="cart-item__variant">${attribute.name}, ${attribute.value || "Not specified"}</span>`;
                    });

                    productRow += `
                    <li class="cart-item d-flex">
                            
                            <p class="cart-item__image">
                                <a href="${productLink}">
                                    <img alt="Image" data-sizes="auto"
                                        data-srcset="${product.product.image}"
                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                        class="lazyload" />
                                </a>
                            </p>
                        
                            <p class="cart-item__details">
                                <a href="" class="cart-item__title">${product.product.name}</a>
                                ${attributeHTML}
                                <span class="cart-ietm__price">${product.quantity} <i>x</i> AED ${product.main_price}</span>
                            </p>
                            <div class="cart-item__quantity">
                               
                            </div>
                           
                        
                            <p class="cart-item__delete">
                                <a href="#" class="remove-cart-item" data-id="${product.id}" ><i class="lnr lnr-cross"></i></a>
                            </p>
                        
                        </li>`;

                });

                $('.header-cart__items').html(productRow);

                $('.cart_sub_total').html(data.summary.after_discount);
            });



            $(".js-canvas-cart").addClass("active");
            $("body").css("overflow", "hidden");
            return false;
        });


        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).on('click', '.add-to-cart-btn', function() {
                const productSlug = $(this).data('product-slug');
                const productSku = $(this).data('product-sku');
                var quantity = $('#product_quantity').val() ? ? 1;

                $.ajax({
                    url: '/cart/add', // Laravel route
                    type: 'POST',
                    data: {
                        product_slug: productSlug,
                        sku: productSku,
                        quantity: quantity, // Default quantity
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        $('.cart_count').text(response.cart_count);
                        if (response.status == true) {
                            toastr.success(response.message, "Success");
                        } else {
                            toastr.error(response.message, "Error");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error("Failed to add item to the cart", 'Error');
                    },
                });
            });

            // Event listener for the "Remove" button
            $(document).on('click', '.remove-cart-item', function() {
                var cartItemId = $(this).data('id'); // Get the cart item ID
                var row = $(this).closest('li'); // Get the closest row to remove

                // Send an Ajax request to remove the item from the cart
                $.ajax({
                    url: '/cart/' + cartItemId,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === true) {
                            // Remove the row from the table
                            row.remove();
                            $('.row_' + cartItemId).remove();
                            $('.cart_sub_total').html(response.updatedCartSummary
                                .sub_total)
                            // Optionally, you can update the cart summary here
                            toastr.success(response.message, "Success");
                        } else {
                            toastr.error(response.message, "Error");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(
                            "An error occurred while removing the item from the cart.",
                            "Error");
                    }
                });
            });

            $(document).on('click', '.change_quantity', function() {
                var cartItemId = $(this).data('id'); // Get the cart item ID
                var action = $(this).data('action'); // Get the cart item ID
                var quantity = $('#quantity-field_' + cartItemId).val();
                // Send an Ajax request to remove the item from the cart
                if (action == 'plus') {
                    quantity = parseInt(quantity, 10) + 1;
                    $('#quantity-field_' + cartItemId).val(quantity);

                } else {
                    quantity = parseInt(quantity, 10) - 1;;
                    $('#quantity-field_' + cartItemId).val(quantity);
                }

                $.ajax({
                    url: '/cart/change_quantity',
                    type: 'POST',
                    data: {
                        cart_id: cartItemId,
                        action: action,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.status === true) {
                            // Optionally, you can update the cart summary here
                            toastr.success(response.message, "Success");
                            window.location.reload();
                        } else {
                            toastr.error(response.message, "Error");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(
                            "An error occurred while removing the item from the cart.",
                            "Error");
                    }
                });
            });

            $(document).on('click', '.wishlist-btn', function() {
                const heartIcon = $(this).find('.lnr-heart');
                const productSlug = $(this).data('product-slug');
                const productSku = $(this).data('product-sku');
                const url = '/wishlist/store';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        productSlug: productSlug,
                        productSku: productSku,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == true) {
                            $('.wishlist_count').text(response.wishlist_count);
                            toastr.success(response.message, "Success");
                            heartIcon.toggleClass('active');

                            if (response.wishlist_status == 1) {
                                $('.wishlist_msg').html("Remove from wishlist");
                            } else {
                                $('.wishlist_msg').html("Add to wishlist");
                            }
                        } else {
                            toastr.error(response.message, "Error");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(
                            "An error occurred while removing the product from the wishlist.",
                            "Error");
                    }
                });
            });

            $('#newsletterForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "https://zena.tomsher.net/subscribe",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#newsletterMessage').html('<p style="color: green;">' +
                            response
                            .success + '</p>');
                        $('#newsletterForm')[0].reset();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors && errors.email) {
                            $('#newsletterMessage').html('<p style="color: red;">' +
                                errors
                                .email[0] + '</p>');
                        }
                    }
                });
            });

        });

        $('.proceedToCheckout').on('click', function() {
            $.ajax({
                url: '/check-login-status', // Endpoint to check login status
                type: 'GET',
                success: function(response) {
                    if (response.is_logged_in) {
                        // Redirect to checkout page
                        window.location.href = 'login.html';
                    } else {
                        // Show alert if not logged in
                        toastr.error("You need to log in to proceed.", "Error");
                    }
                },
                error: function() {
                    toastr.error("An error occurred. Please try again.", "Error");
                }
            });
        });

        $(document).ajaxComplete(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

    <script>
        const currentAttribute = {
            "6": 15
        };
        const productAttributes = [{
            "id": "6",
            "name": "Color",
            "values": [{
                "id": 15,
                "name": "Gold"
            }, {
                "id": 17,
                "name": "Rose Gold"
            }]
        }];
        const variantProducts = [{
            "SKU00010": ["15"]
        }, {
            "SKU00020": ["17"]
        }];


        var slug = 'generic-diamond-pendant-necklace';
        $(document).ready(function() {

            let selectedAttributes = {}; // Tracks selected attributes.
            const firstAttributeId = productAttributes[0].id; // The ID of the first attribute.

            // Event handler for selecting an attribute value
            $('.attribute-item').click(function() {
                const attributeId = $(this).closest('.attribute-list').data('attribute-id');
                const valueId = $(this).data('value-id');

                // If the first attribute is selected, no need to filter it
                if (attributeId === firstAttributeId) {
                    // Always update the first attribute selection
                    selectedAttributes[attributeId] = valueId;
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    // Filter the remaining attributes based on this selection
                    updateSubsequentAttributes(selectedAttributes);
                    return;
                }

                // For other attributes, update selected value and filter
                selectedAttributes[attributeId] = valueId;

                // Mark the clicked value as selected
                $(this).siblings().removeClass('active');
                $(this).addClass('active');

                // Clear selections for attributes that come after the current one
                clearSubsequentAttributes(attributeId);

                // Filter valid combinations based on the selected attributes
                filterAttributes(selectedAttributes);

                // Check if all attributes are selected to display SKU
                if (Object.keys(selectedAttributes).length === productAttributes.length) {
                    displaySKU(selectedAttributes);
                } else {
                    $('#product-stock').html(
                        '<p>Select all attributes to see product details.</p>');
                }
            });

            // Filter the valid values for each attribute based on the selected attributes
            function filterAttributes(selectedAttributes) {
                const validCombinations = getValidCombinations(selectedAttributes);
                let validValues = {};

                validCombinations.forEach(variant => {
                    const values = Object.values(variant)[0].map(value => parseInt(
                        value)); // Convert to integers
                    values.forEach(value => {
                        const attrId = getAttributeId(value);
                        if (!validValues[attrId]) validValues[attrId] = [];
                        if (!validValues[attrId].includes(value)) validValues[attrId].push(
                            value);
                    });
                });

                // Update UI for each attribute list
                $('.attribute-list').each(function() {
                    const attrId = $(this).data('attribute-id');

                    // Skip the first attribute from filtering (it stays always visible)
                    if (attrId === firstAttributeId) {
                        $(this).find('.attribute-item').show().removeClass('disabled');
                        return;
                    }

                    $(this).find('.attribute-item').each(function() {
                        // Skip the item with class "firstItem_0" from getting disabled
                        if ($(this).hasClass('firstItem_0')) {
                            return; // Don't apply disabling logic to this item
                        }

                        const valueId = parseInt($(this).data(
                            'value-id')); // Convert to integer
                        if (validValues[attrId] ? .includes(valueId)) {
                            $(this).removeClass('disabled').show();
                        } else {
                            $(this).addClass('disabled').hide();
                        }
                    });
                });
            }

            // Automatically select valid combinations for subsequent attributes when the first attribute is changed
            function updateSubsequentAttributes(selectedAttributes) {
                const validCombinations = getValidCombinations(selectedAttributes);

                let validValues = {};
                validCombinations.forEach(variant => {
                    const values = Object.values(variant)[0].map(value => parseInt(
                        value)); // Convert to integers
                    values.forEach(value => {
                        const attrId = getAttributeId(value);
                        if (!validValues[attrId]) validValues[attrId] = [];
                        if (!validValues[attrId].includes(value)) validValues[attrId].push(
                            value);
                    });
                });

                // Update the UI for each attribute list
                $('.attribute-list').each(function() {
                    const attrId = $(this).data('attribute-id');

                    // Skip the first attribute from filtering (it stays always visible)
                    if (attrId === firstAttributeId) {
                        $(this).find('.attribute-item').show().removeClass('disabled');
                        return;
                    }

                    $(this).find('.attribute-item').each(function() {
                        const valueId = parseInt($(this).data(
                            'value-id')); // Convert to integer
                        if (validValues[attrId] ? .includes(valueId)) {
                            $(this).removeClass('disabled').show();
                            // Auto-select the valid values
                            if (!selectedAttributes[attrId]) {
                                selectedAttributes[attrId] = valueId;
                                $(this).addClass('active');
                            }
                        } else {
                            $(this).addClass('disabled').hide();
                        }
                    });
                });

                // Check if all attributes are selected to display SKU
                if (Object.keys(selectedAttributes).length === productAttributes.length) {
                    displaySKU(selectedAttributes);
                }
            }

            // Get valid combinations based on the selected attributes
            function getValidCombinations(selectedAttributes) {
                console.log("Selected Attributes:", selectedAttributes);
                console.log("Variant Products:", variantProducts);

                return variantProducts.filter(variant => {
                    const values = Object.values(variant)[0].map(value => parseInt(
                        value)); // Convert to integers
                    return Object.entries(selectedAttributes).every(([attrId, valueId]) => {
                        return values.includes(parseInt(
                            valueId)); // Ensure both are integers
                    });
                });
            }

            // Function to clear selections for subsequent attributes
            function clearSubsequentAttributes(attributeId) {
                // Clear selections for attributes following the current attribute
                const attributeIndex = productAttributes.findIndex(attr => attr.id == attributeId);
                productAttributes.slice(attributeIndex + 1).forEach(attr => {
                    delete selectedAttributes[attr.id];
                    $(`[data-attribute-id="${attr.id}"] .attribute-item`).removeClass('active');
                });
            }

            // Display the correct SKU once all attributes are selected
            function displaySKU(selectedAttributes) {
                const matchingVariant = variantProducts.find(variant => {
                    const values = Object.values(variant)[0].map(value => parseInt(
                        value)); // Convert to integers
                    return Object.values(selectedAttributes).every(value => values.includes(value));
                });

                if (matchingVariant) {
                    const sku = Object.keys(matchingVariant)[0];
                    $('#product-stock').html(`<p>SKU: ${sku}</p>`);
                    var url = '/product-detail?slug=' + encodeURIComponent(slug) + '&sku=' +
                        encodeURIComponent(
                            sku);

                    // Redirect to the URL
                    window.location.href = url;
                } else {
                    $('#product-stock').html('<p>No matching product found.</p>');
                }
            }

            // Helper function to get the attribute ID based on value ID
            function getAttributeId(valueId) {
                for (const attribute of productAttributes) {
                    if (attribute.values.some(value => value.id === valueId)) {
                        return attribute.id;
                    }
                }
                return null;
            }
        });
    </script>
    <link href="../cdn.jsdelivr.net/npm/bootstrap%405.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="../cdn.jsdelivr.net/npm/bootstrap%405.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
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
