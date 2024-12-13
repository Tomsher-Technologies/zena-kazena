@extends('frontend.layouts.app')
@section('content')
    <!-- Shop breadcrumb -->
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">

            {{ Breadcrumbs::render('product', $product_stock->product) }}

        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->

    @php
        $images = $response['photos'];
        if ($response['thumbnail_image'] != null) {
            array_unshift($images, $response['thumbnail_image']);
        }

        if ($response['variant_image'] != null) {
            array_push($images, $response['variant_image']);
        }

        $thumbanailSlider = $productImagesWeb = $productImagesMob = '';
    @endphp

    @if (!empty($images))
        @foreach ($images as $imgkey => $img)
            @php
                $activeStatus = '';
                if ($imgkey == 0) {
                    $activeStatus = 'active';
                }

                $productImagesWeb .=
                    '<li class="' .
                    $activeStatus .
                    ' js-product-main-image" data-id="' .
                    $imgkey .
                    '">
                        <a href="' .
                    $img .
                    '">
                            <img alt="Image" src="' .
                    $img .
                    '"data-zoomed="' .
                    $img .
                    '" class="js-zoomit" />
                        </a>
                    </li>';

                $productImagesMob .=
                    '<div class="mobile-product-image">
                        <p>
                            <a href="' .
                    $img .
                    '" target="_blank">
                                <img alt="Image" src="' .
                    $img .
                    '" />
                            </a>
                        </p>
                    </div>';

                $thumbanailSlider .=
                    '<li class="js-product-thumnail-slider" data-group="1">
                                        <a href="#" class="' .
                    $activeStatus .
                    ' js-product-thumbnail" data-id="' .
                    $imgkey .
                    '">
                                            <img alt="Image" src="' .
                    $img .
                    '" />
                                        </a>
                                    </li>';
            @endphp
        @endforeach
    @endif


    <!-- Product mobile gallery -->
    <div class="product__mobile-gallery js-product-mobile-gallery">

        {!! $productImagesMob !!}

    </div>
    <!-- End product mobile allery -->

    <!-- Product -->
    <div class="product product-layout-3">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Product main -->
            <div class="product__main d-flex">
                <!-- Product image and thumbnails -->
                <div class="product__main-image-and-thumbnails">
                    <!-- Product tag -->
                    @if ($response['offer_tag'] != '')
                        <div class="product-grid-item__tag">{{ $response['offer_tag'] }}</div>
                    @endif
                    <!-- End product tag -->
                    <!-- Product main image -->
                    <ul class="product__main-image js-popup-gallery">

                        {!! $productImagesWeb !!}

                    </ul>
                    <!-- End product main image -->
                    <!-- Product thumbnails -->
                    <ul class="product__thumbnails">
                        {!! $thumbanailSlider !!}

                    </ul>
                    <!-- End product thumbnails -->
                </div>
                <!-- End product thumbnails and image -->
                <!-- Product right -->
                <div class="product__right">
                    <!-- Product title -->
                    <h1 class="product__title">{{ $response['name'] }}</h1>
                    <!-- End product title -->
                    <!-- Product reviews -->
                    <div class="product__reviews">

                    </div>
                    <!-- End product reviews -->
                    <!-- Product price -->

                 
                    <div class="product__price" bis_skin_checked="1">
                        <span
                            class="product-price__new">{{ env('DEFAULT_CURRENCY') . ' ' . $response['main_price'] }}</span>

                        @if ($response['stroked_price'] != $response['main_price'])
                            <span
                                class="product-price__old">{{ env('DEFAULT_CURRENCY') . ' ' . $response['stroked_price'] }}</span>
                        @endif

                    </div>


                    <div class="product-attributes product__options">
                        <div class="product__description product-stock" id="product-stock">
                            <p>SKU : {{ $response['sku'] }}</p>
                        </div>
                    </div>

                    <!-- End options -->
                    <!-- Product action -->
                    <div class="product__action js-product-action">

                    </div>

                    <div class="product__description">
                        {!! $response['description'] !!}
                    </div>

                    <div class="product__promo-bar">
                        <!-- Item -->
                        <div class="promo-bar__item">
                            <!-- Title -->
                            <div class="promo-bar-item__title" id="timerTitle">{{ trans('messages.auction') }}<br>{{ trans('messages.ends_in') }}:</div>
                            <!-- End title -->
                            <!-- Content -->
                            <div class="promo-bar-item__content"  class="auction-timer">
                                <!-- Countdown -->
                                <div class="promo-bar-item__counter " id="timer">
                                    <ul>
                                        <li>
                                            <span class="counter__value " id="days">0</span>
                                            <span class="counter__title">{{ trans('messages.days') }}</span>
                                        </li>
                                        <li>
                                            <span class="counter__value " id="hours">0</span>
                                            <span class="counter__title">{{ trans('messages.hours') }}</span>
                                        </li>
                                        <li>
                                            <span class="counter__value" id="minutes">0</span>
                                            <span class="counter__title">{{ trans('messages.minutes') }}</span>
                                        </li>
                                        <li>
                                            <span class="counter__value" id="seconds">0</span>
                                            <span class="counter__title">{{ trans('messages.seconds') }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="promo-bar-item__counter " id="timerStart" style="display: none;">
                                </div>
                                <!-- End countdown -->
                            </div>
                            <!-- End content -->
                        </div>
                        <!-- End item -->
                        <!-- Item -->
                        <div class="promo-bar__item">
                            <!-- Title -->
                            <div class="promo-bar-item__title">{{trans('messages.highest')}} {{trans('messages.bid')}}</div>
                            <!-- End title -->
                            <!-- Content -->
                            <div class="promo-bar-item__content" id="current-bid">
                                {{ env('DEFAULT_CURRENCY') . ' ' . $response['main_price'] }}
                            </div>
                            <!-- End content -->
                        </div>
                        <!-- End item -->
                    </div>
                    <!-- End product information -->
                    <div class="product__action js-product-action" id="placeBidDiv">
                        <!-- Product quantity and add to cart -->
                        <div class="product__quantity-and-add-to-cart d-flex">
                            <!-- Quantity -->
                            <div class="product__quantity" style="min-width: 50% !important;">
                                <input type="number" value="" placeholder="{{trans('messages.enter').' '.trans('messages.bid').' '.trans('messages.amount')}}" class="product-quantity__input js-quantity-field" style="padding:12px 2px 12px 2px !important;" id="bid-amount" min="{{$response['main_price']}}">
                            </div>
                            <!-- End quantity -->
                            <!-- Add to cart -->
                            <div class="product__add-to-cart">
                                <button id="place-bid" class="eighth-button">{{trans('messages.place').' '.trans('messages.bid')}}</button>
                            </div>
                            
                            <!-- End add to cart -->
                        </div>
                        <!-- End product quantity and add to cart -->
                        <p id="bid-message"></p>
                    </div>
                </div>
                <!-- End product right -->
                <!-- Product sidebar -->
                <div class="product__sidebar">
                    <div class="product-sidebar__features">
                        <!-- Feature -->
                        <div class="home-about-us__feature d-flex">
                            <!-- Icon -->
                            <div class="feature__icon">
                                <i class="lni lni-crown"></i>
                            </div>
                            <!-- End icon -->
                            <!-- Content -->
                            <div class="feature__content">
                                <!-- Title -->
                                <h6 class="feature__h6">1 Year Zena & Kazena Brand Warranty</h6>
                                <!-- End title -->
                                <!-- Description -->
                                <div class="feature__description">Zena & Kazena Promise for Exchange and Upgrades.

                                </div>
                                <!-- End Description -->
                            </div>
                            <!-- End content -->
                        </div>
                        <!-- End feature -->



                        <!-- Feature -->
                        <div class="home-about-us__feature d-flex">
                            <!-- Icon -->
                            <div class="feature__icon">
                                <i class="lni lni-spinner-solid"></i>
                            </div>
                            <!-- End icon -->
                            <!-- Content -->
                            <div class="feature__content">
                                <!-- Title -->
                                <h6 class="feature__h6">30 Day Return Policy</h6>
                                <!-- End title -->
                                <!-- Description -->
                                <div class="feature__description">Zena & Kazena Promise for Exchange and Upgrades.

                                </div>
                                <!-- End Description -->
                            </div>
                            <!-- End content -->
                        </div>
                        <!-- End feature -->
                        <!-- Feature -->
                        <div class="home-about-us__feature d-flex">
                            <!-- Icon -->
                            <div class="feature__icon">
                                <i class="lnil lnil-ship"></i>
                            </div>
                            <!-- End icon -->
                            <!-- Content -->
                            <div class="feature__content">
                                <!-- Title -->
                                <h6 class="feature__h6">Free shipping</h6>
                                <!-- End title -->
                                <!-- Description -->
                                <div class="feature__description">Durotan free shipping for all orders over AED 199
                                </div>
                                <!-- End Description -->
                            </div>
                            <!-- End content -->
                        </div>
                        <!-- End feature -->
                        <!-- Feature -->
                        <div class="home-about-us__feature d-flex">
                            <!-- Icon -->
                            <div class="feature__icon">
                                <i class="lnil lnil-money-protection"></i>
                            </div>
                            <!-- End icon -->
                            <!-- Content -->
                            <div class="feature__content">
                                <!-- Title -->
                                <h6 class="feature__h6">Secure payment</h6>
                                <!-- End title -->
                                <!-- Description -->
                                <div class="feature__description">We guarantee 100% secure with online payment on
                                    our site.</div>
                                <!-- End Description -->
                            </div>
                            <!-- End content -->
                        </div>
                        <!-- End feature -->
                    </div>
                    <!-- Safe checkout -->
                    <div class="product__safe-checkout">
                        <img src="assets/images/safe-checkout.jpg" alt="Safe checkout" />
                    </div>
                    <!-- End safe checkout -->
                </div>
                <!-- End product sidebar -->
            </div>
            <!-- End product main -->



            <!-- Product tabs -->
            <div class="product__tabs-2">
                <!-- Mobile tabs -->
                <div class="product__mobile-tabs">
                    <!-- Accordion -->
                    @if (!empty($response['tabs']))
                        @foreach ($response['tabs'] as $tabkeyMob => $tabMob)
                            <div class="accordion @if ($tabkeyMob == 0) active @endif js-accordion">
                                <!-- Title -->
                                <div class="accordion__title js-accordion-title">
                                    {{ $tabMob->heading }}
                                </div>
                                <!-- End title -->
                                <!-- Content -->
                                <div class="accordion__content js-accordion-content">
                                    <div class="row">
                                        <div class="col-12 col-lg-12">
                                            {!! $tabMob->content !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- End content -->
                            </div>
                        @endforeach
                    @endif

                    <!-- End accordion -->
                </div>
                <!-- End mobile tabs -->


                <!-- Desktop tabs -->
                <div class="product__desktop-tabs">
                    <ul class="tabs__nav">
                        @php
                            $tabkeyOut = 0;
                            $tabContent = '';
                        @endphp
                        @if (!empty($response['tabs']))
                            @foreach ($response['tabs'] as $tabkey => $tab)
                                <li>
                                    <a href="#" class="@if ($tabkey == 0) active @endif js-tab-link"
                                        data-id="{{ $tabkey }}">{{ $tab->heading }}</a>
                                </li>

                                @php
                                    $tabkeyOut++;
                                    $tabactive = '';
                                    if ($tabkey == 0) {
                                        $tabactive = 'tab-content__active tab-content__show';
                                    }

                                    $tabContent .=
                                        '<div class="tab-content ' .
                                        $tabactive .
                                        ' js-tab-content" data-id="' .
                                        $tabkey .
                                        '">
                                            <div class="row">
                                                <div class="col-12 col-lg-12">
                                                    
                                                    ' .
                                        $tab->content .
                                        '
                                                </div>
                                            
                                            </div>
                                        </div>';

                                @endphp
                            @endforeach
                        @endif
                    </ul>
                    <div class="tabs__content">
                        <!-- Description tab -->
                        {!! $tabContent !!}
                    </div>
                </div>
                <!-- End desktop tabs -->
            </div>
            <!-- End product tabs -->
        </div>
        <!-- End container -->
    </div>
    <!-- End product -->


    @if (!empty($relatedProducts[0]))
        <!-- Related Products -->
        <div class="related-products">
            <!-- Container -->
            <div class="container container--type-2">
                <!-- Title -->
                <h3 class="related-products__title">{{ trans('messages.related') . ' ' . trans('messages.products') }}
                </h3>
                <!-- End title -->
                <!-- Results -->
                <div class="js-related-products">

                    @foreach ($relatedProducts as $relProd)
                        @php

                            $imageRel = $relProd->thumbnail_img;
                            if ($imageRel == null) {
                                $imageRel = app('url')->asset('assets/img/placeholder.jpg');
                            }
                        @endphp
                        <!-- Product -->
                        <div class="result-product">
                            <!-- Image -->
                            <div class="result-product__image">
                                <a
                                    href="{{ route('auction.product-detail', ['slug' => $relProd->slug, 'sku' => $relProd->sku]) }}">
                                    <img alt="Image" data-sizes="auto" data-srcset="{{ $imageRel }}"
                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                        class="lazyload" />
                                </a>
                            </div>
                            <!-- End image -->
                            <!-- Product name -->
                            <div class="result-product__name"><a
                                    href="{{ route('auction.product-detail', ['slug' => $relProd->slug, 'sku' => $relProd->sku]) }}">{{ $relProd->getTranslation('name', $lang) }}</a>
                            </div>
                            <!-- End product name -->
                            <!-- Product price -->
                            @php
                                if ($relProd->stocks[0]?->price > $relProd->stocks[0]?->high_bid_amount) {
                                    $price = $relProd->stocks[0]?->price;
                                } else {
                                    $price = $relProd->stocks[0]?->high_bid_amount;
                                }
                            @endphp


                            <span class="product-grid-item__price-new">{{ env('DEFAULT_CURRENCY') . ' ' . $price }}</span>
                            <!-- End price new -->
                            <!-- Price old -->
                            @if ($relProd->stocks[0]?->price != $relProd->stocks[0]?->high_bid_amount && $relProd->stocks[0]?->high_bid_amount != 0)
                                <span
                                    class="product-grid-item__price-old">{{ env('DEFAULT_CURRENCY') . ' ' . $relProd->stocks[0]?->price }}</span>
                            @endif
                            <!-- End product price -->
                        </div>
                        <!-- End product -->
                    @endforeach



                </div>
                <!-- End results -->
            </div>
            <!-- End container -->
        </div>
        <!-- End related products -->
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $start_time = $response['auction_start_date']; // example start time
        $end_time = $response['auction_end_date']; // example end time
    @endphp
@endsection

@section('header')
    <style>
        .attribute-item {
            cursor: pointer;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
            transition: all 0.3s;
        }

        .attribute-item.disabled {
            /* color: #ccc; */
            pointer-events: none !important;
            text-decoration: line-through !important;
            /* background-color: #f9f9f9; */
        }
        .promo-bar-item__title {
            width: 30% !important;
            line-height: 2 !important;
        }
    </style>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

        });
    </script>
    <script>

        // Pass start and end times from Blade to JavaScript
        var startTime = @json($start_time);  // PHP variable passed to JavaScript
        var endTime = @json($end_time);      // PHP variable passed to JavaScript

        // Convert the times to JavaScript Date objects
        var startTimestamp = new Date(startTime).getTime();
        var endTimestamp = new Date(endTime).getTime();

        // Function to update the auction timer
        function updateTimer() {
            var now = new Date().getTime(); // Current time in milliseconds

            // If the auction hasn't started yet, display a message
            if (now < startTimestamp) {
                document.getElementById("timerTitle").innerHTML = "{{ trans('messages.auction') }}<br>{{ trans('messages.starts_in') }}: ";
                document.getElementById("timerStart").innerHTML = formatTime(startTimestamp - now);
                document.getElementById("timerStart").style.display = 'block'; 
                document.getElementById("timer").style.display = 'none'; 
                document.getElementById("placeBidDiv").style.display = 'none'; 
                return; // Stop the timer until start time is reached
            }else{
                // Calculate the remaining time until the auction ends
                var timeLeft = endTimestamp - now;

                // If the auction has ended, show "Auction Ended"
                if (timeLeft <= 0) {
                    document.getElementById("timerStart").innerHTML = "Auction Ended";
                    document.getElementById("timerStart").style.display = 'block'; 
                    document.getElementById("timer").style.display = 'none'; 
                    document.getElementById("placeBidDiv").style.display = 'none'; 
                    return; // Stop the timer
                }

                document.getElementById("timerStart").style.display = 'none'; 
                document.getElementById("timer").style.display = 'block'; 
                document.getElementById("timerTitle").innerHTML = "{{ trans('messages.auction') }} {{ trans('messages.ends_in') }}: ";
                document.getElementById("placeBidDiv").style.display = 'block'; 
                // Calculate days, hours, minutes, and seconds
                var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                // Display the remaining time in the format of days, hours, minutes, and seconds
                document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
                document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
                document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
                document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
            }
        }

        // Function to format the time until auction starts (when start time hasn't arrived)
        function formatTime(milliseconds) {
            var seconds = Math.floor((milliseconds / 1000) % 60);
            var minutes = Math.floor((milliseconds / (1000 * 60)) % 60);
            var hours = Math.floor((milliseconds / (1000 * 60 * 60)) % 24);
            var days = Math.floor(milliseconds / (1000 * 60 * 60 * 24));
            return (days < 10 ? "0" + days : days) + " : " + 
                   (hours < 10 ? "0" + hours : hours) + " : " + 
                   (minutes < 10 ? "0" + minutes : minutes) + " : " + 
                   (seconds < 10 ? "0" + seconds : seconds) ;
        }

        // Update the timer every second
        setInterval(updateTimer, 1000);

        // Start the countdown when the start time arrives
        if (new Date().getTime() >= startTimestamp) {
            updateTimer(); // Start the countdown if the current time is past the start time
        } else {
            document.getElementById("timerStart").style.display = 'block'; 
            document.getElementById("timer").style.display = 'none'; 
            document.getElementById("timerTitle").innerHTML = "{{ trans('messages.auction')}} {{ trans('messages.starts_in') }}: ";
            document.getElementById("timerStart").innerHTML = formatTime(startTimestamp - new Date().getTime());
            document.getElementById("placeBidDiv").style.display = 'none'; 
        }

        var productId = {{ $response['product_id'] }};
        $(document).ready(function () {
            $('#place-bid').on('click', function () {

                $.ajax({
                    url: '/check-login-status',  // Endpoint to check login status
                    type: 'GET',
                    success: function (response) {
                        if (response.is_logged_in) {
                            placeBid();
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

            function placeBid() {
                var bidAmount = $('#bid-amount').val();

                if (!bidAmount || bidAmount <= 0) {
                    $('#bid-message').html('<span class="error">{{trans("messages.enter_valid_bid_msg")}}</span>');
                    return;
                }

                $.ajax({
                    url: '/auction/' + productId + '/place-bid',
                    method: 'POST',
                    data: {
                        bid_amount: bidAmount,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response.message);
                        if (response.status) {
                            $('#current-bid').text('AED ' + response.new_bid);
                            $('#bid-message').html('<span class="text-success">'+response.message+'</span>');
                            toastr.success(response.message, "{{trans('messages.success')}}");
                            setTimeout(function() {
                                window.location.reload();
                            }, 3000);
                        } else {
                            $('#bid-message').html('<span class="error">'+response.message+'</span>');
                            toastr.error(response.message, "{{trans('messages.error')}}");
                        }
                    },
                    error: function() {
                        $('#bid-message').text('{{ trans("messages.bid_error") }}');
                        toastr.error('{{ trans("messages.bid_error") }}', "{{trans('messages.error')}}");
                    }
                });
            }
        });
    </script>
@endsection
