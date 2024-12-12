<!DOCTYPE html>
<html dir="{{ getDirection() }}" lang="{{ getActiveLanguage() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    {!! SEO::generate() !!}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/theme.css?v=4') }}" rel="stylesheet" type="text/css" />
    <!-- For Js Library -->
    <script src="{{ asset('assets/js/vendor.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/lineicons-regular.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icon-font.min.css') }}">
    <link href="{{ asset('assets/css/custom-style.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('header')
    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
</head>

<body>
    <div id="main">
        <!-- Header -->
        @include('frontend.parts.header')
        <!-- End header -->

        <!-- Canvas menu -->
        @include('frontend.parts.canvas')
        <!-- End canvas menu -->

        <!-- Canvas cart -->
        @include('frontend.parts.canvas_cart')
        <!-- End canvas cart -->

        @yield('content')

        <!-- Footer collection -->
        @include('frontend.parts.footer')
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
                {{-- <h5 class="search-popup__title">Search</h5>
                <!-- End search title -->
                <!-- Search categories -->
                <ul class="search-popup__categories">
                    <li><a href="#" class="active">All</a></li>
                    <li><a href="#">Rings</a></li>
                    <li><a href="#">Bracelets</a></li>
                    <li><a href="#">Bangles</a></li>
                    <li><a href="#">Necklaces</a></li>
                </ul> --}}
                <!-- End search categories -->
                <!-- Search form -->
                <form class="search-popup__form" action="{{ route('products.index') }}" method="get">
                    <!-- Search input -->
                    <input type="text" class="search-popup__input" name="search" placeholder="Search here..." />
                    <!-- End search input -->
                </form>
                <!-- End search form -->
                <!-- Search results -->
                {{-- <div class="search-popups__results">
                    <!-- Results heading -->
                    <h6 class="search-popup__results-heading">Search results</h6>
                    <!-- End results heading -->
                    <!-- Results -->
                    <div class="search-popups__results-products d-flex">
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image2.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Red & Black Gold Diamond
                                    Mismatched Earrings</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 2500.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image1.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Yellow & White Gold Diamond
                                    Ring</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 6500.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image3.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">Black Diamond Necklace</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 3500.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image1.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Yellow & White Gold Diamond
                                    Ring</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">AED 1999.00</div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a href="product.html">
                                    <img src="assets/images/products/image4.jpg" alt="Product image" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a href="product.html">18K Red & Black Gold Diamond
                                    Mismatched Earrings</a></div>
                            <!-- End product name -->
                            <!-- Product price -->
                            <div class="result-product__price">
                                <!-- Price new -->
                                <span class="result-product__price-new">AED 2500.00</span>
                                <!-- End price new -->
                                <!-- Price old -->
                                <span class="result-product__price-old">AED 5000.00</span>
                                <!-- End price old -->
                            </div>
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                    </div>
                    <!-- End results -->
                    <!-- Results action -->
                    <div class="search-popup__results-action">
                        <a href="#" class="fifth-button">All results (12)</a>
                    </div>
                    <!-- End results actions -->
                </div> --}}
                <!-- End search results -->
            </div>
            <!-- End container -->
        </div>
    </div>

    
    <script>
        toastr.options = {
            closeButton: true,        // Show close button
            debug: false,             // Debugging (off by default)
            progressBar: true,        // Show progress bar
            preventDuplicates: true, // Prevent duplicate toasts
            positionClass: "toast-top-right", // Toast position
            showDuration: "300", // Duration of show animation (ms)
            hideDuration: "1000", // Duration of hide animation (ms)
            showEasing: "swing",      // Show easing effect
            hideEasing: "linear",     // Hide easing effect
            showMethod: "fadeIn",     // Show animation
            hideMethod: "fadeOut"     // Hide animation
        };
        @if (session('message'))
            
            toastr["{{ session('alert-type', 'info') }}"]("{{ session('message') }}");
        @endif

        if ($('#lang-change').length > 0) {
            $('#lang-change').on('change', function(e) {
                e.preventDefault();
                var $this = $(this);
                var locale = $this.val();
                $.post('{{ route('language.change') }}', {
                    _token: '{{ csrf_token() }}',
                    locale: locale
                }, function(data) {
                    location.reload();
                });
            });
        }

        var productDetailRoute = "{{ route('product-detail', ['slug' => '__slug__', 'sku' => '__sku__']) }}"; // this will be a placeholder
        $(".js-open-canvas-cart").on("click", function() {

            $.get('{{ route('cart.index') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                console.log();
                // location.reload();
                var productRow = '';
                $.each(data.products, function(index, product) {
                    
                    var productLink = productDetailRoute.replace('__slug__', product.product.slug).replace('__sku__', product.product.sku);
        
                    var attributeHTML = '';

                    $.each(product.product.attributes, function(attrIndex, attribute) {
                        attributeHTML += `<span class="cart-item__variant">${attribute.name}, ${attribute.value || "Not specified"}</span>`;
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

            
            $(document).on('click', '.add-to-cart-btn', function () {
                const productSlug = $(this).data('product-slug');
                const productSku = $(this).data('product-sku');
                var quantity = $('#product_quantity').val() ?? 1;
            
                $.ajax({
                    url: '/cart/add', // Laravel route
                    type: 'POST',
                    data: {
                        product_slug: productSlug,
                        sku : productSku,
                        quantity: quantity, // Default quantity
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        $('.cart_count').text(response.cart_count);
                        $('.canvasCartcount').text(response.cart_count);
                        if (response.status == true) {
                            toastr.success(response.message, "{{trans('messages.success')}}");
                        } else {
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                    },
                    error: function (xhr, status, error) {
                        toastr.error("{{trans('messages.product_add_cart_failed')}}", 'Error');
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
                            $('.cart_count').text(response.cart_count);
                            $('.canvasCartcount').text(response.cart_count);
                            $('.row_'+cartItemId).remove();
                            $('.cart_sub_total').html(response.updatedCartSummary.sub_total)
                            // Optionally, you can update the cart summary here
                            toastr.success(response.message, "{{trans('messages.success')}}");
                        } else {
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error("{{trans('messages.cart_remove_error')}}", "{{trans('messages.error')}}");
                    }
                });
            });

            $(document).on('click', '.change_quantity', function() {
                var cartItemId = $(this).data('id'); // Get the cart item ID
                var action = $(this).data('action'); // Get the cart item ID
                var quantity = $('#quantity-field_'+cartItemId).val();
                // Send an Ajax request to remove the item from the cart
                if(action == 'plus'){
                    quantity = parseInt(quantity, 10) + 1;
                    $('#quantity-field_'+cartItemId).val(quantity);
                    
                }else{
                    quantity =  parseInt(quantity, 10) - 1;;
                    $('#quantity-field_'+cartItemId).val(quantity);
                }
                
                $.ajax({
                    url: '/cart/change_quantity',
                    type: 'POST',
                    data: {
                        cart_id: cartItemId,
                        action : action,
                        quantity : quantity
                    },
                    success: function(response) {
                        if (response.status === true) {
                            // Optionally, you can update the cart summary here
                            toastr.success(response.message, "{{trans('messages.success')}}");
                            window.location.reload();
                        } else {
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error("{{trans('messages.cart_remove_error')}}", "{{trans('messages.error')}}");
                    }
                });
            });

            $(document).on('click', '.wishlist-btn', function () {
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
                        if(response.status == true){
                            $('.wishlist_count').text(response.wishlist_count);
                            toastr.success(response.message, "{{trans('messages.success')}}");
                            heartIcon.toggleClass('active');

                            if(response.wishlist_status == 1){
                                $('.wishlist_msg').html("{{trans('messages.remove_wishlist')}}");
                            }else{
                                $('.wishlist_msg').html("{{trans('messages.add_wishlist')}}");
                            }
                        }else{
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error("{{trans('messages.wishlist_remove_error')}}", "{{trans('messages.error')}}");
                    }
                });
            });

            $('#newsletterForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('newsletter.subscribe') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#newsletterMessage').html('<p style="color: green;">' + response.success + '</p>');
                        $('#newsletterForm')[0].reset();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors && errors.email) {
                            $('#newsletterMessage').html('<p style="color: red;">' + errors.email[0] + '</p>');
                        }
                    }
                });
            });

        });

        $('.proceedToCheckout').on('click', function () {
            $.ajax({
                url: '/check-login-status',  // Endpoint to check login status
                type: 'GET',
                success: function (response) {
                    if (response.is_logged_in) {
                        // Redirect to checkout page
                        window.location.href = '/checkout';
                    } else {
                        // Show alert if not logged in
                        toastr.error("{{trans('messages.login_msg')}}", "{{trans('messages.error')}}");
                    }
                },
                error: function () {
                    toastr.error("{{trans('messages.error_try_again')}}", "{{trans('messages.error')}}");
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @yield('script')
    
    
</body>

</html>
