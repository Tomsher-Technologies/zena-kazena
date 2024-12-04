@extends('frontend.layouts.app')
@section('content')
    <!-- Shop breadcrumb -->
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Breadcrumb -->
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">{{ trans('messages.home') }} </a></li>

                <li class="breadcrumb__item active" aria-current="page">
                    {{ trans('messages.cart') }} <small>({{cartCount()}} {{ trans('messages.items') }})</small></li>
            </ol>
            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->
    <!-- Shopping cart -->
    <div class="shopping-cart">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Second container -->
            <div class="">
                <!-- Title -->

                <!-- End title -->
                <!-- Row -->
                <div class="row">
                    <!-- Left -->
                    <div class="col-lg-7 col-xl-8">
                        <!-- Cart container -->
                        <div class="shopping-cart__container">
                            <!--- Table responsive -->
                            <div class="table-responsive">
                                <!-- Table -->
                                <table class="shopping-cart__table">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('messages.products') }}</th>
                                            <th>{{ trans('messages.price') }}</th>
                                            <th>{{ trans('messages.quantity') }}</th>
                                            <th>{{ trans('messages.subtotal') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($response['products']))
                                            @foreach ($response['products'] as $prod)
                                                <tr class="row_{{ $prod['id'] }}">
                                                    <td>
                                                        <div class="shopping-cart__product">
                                                            <div class="cart-product__image">
                                                                <a href="{{ route('product-detail',['slug' => $prod['product']['slug'], 'sku' => $prod['product']['sku']]) }}">
                                                                    <img alt="Image" data-sizes="auto"
                                                                        data-srcset="{{$prod['product']['image']}}"
                                                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                                        class="lazyload" />
                                                                </a>
                                                            </div>
                                                            <div class="cart-product__title-and-variant">
                                                                <h3 class="cart-product__title"><a href="{{ route('product-detail',['slug' => $prod['product']['slug'], 'sku' => $prod['product']['sku']]) }}">{{$prod['product']['name']}}</a></h3>
                                                                <div class="d-flex flex-wrap justify-content-between">

                                                                    @php
                                                                        $attributeValue = '';
                                                                    @endphp
                                                                    @foreach ($prod['product']['attributes'] as $attribute)
                                                                        @php
                                                                            $attributeValue .= " <strong>".$attribute['name'] .":</strong>". $attribute['value'] ."</p>";
                                                                        @endphp
                                                                    @endforeach
                                                                    @if ($attributeValue != '')
                                                                        <div class="cart-product__variant">{!! $attributeValue !!}</div> &nbsp;&nbsp;&nbsp;
                                                                    @endif
                                                                    
                                                                    <div class="cart-product__variant">{{trans('messages.brand')}}: {{$prod['product']['brand']}}</div>
        
                                                                </div>
        
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="cart-product__price">
                                                            {{ env('DEFAULT_CURRENCY') }} {{$prod['main_price']}}
        
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="cart-product__quantity-field">
                                                            <div class="quantity-field__minus change_quantity" data-action="minus" data-id="{{$prod['id']}}">-</div>
                                                            <input type="text" value="{{$prod['quantity']}}" class="quantity-field__input" id="quantity-field_{{$prod['id']}}"/>
                                                            <div class="quantity-field__plus change_quantity" data-action="plus" data-id="{{$prod['id']}}">+</div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="cart-product__price">
                                                            {{ env('DEFAULT_CURRENCY') }} {{$prod['total']}}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="cart-product__delete">
                                                            <a href="#" class="remove-cart-item" data-id="{{ $prod['id'] }}"><i class="lnil lnil-close"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">{{ trans('messages.no_products_cart') }}</td>
                                            </tr>
                                        @endif
                                        <!-- Cart product item -->
                                        
                                        <!-- End cart product item -->
                                       
                                    </tbody>
                                </table>
                                <!-- End table -->


                            </div>
                            <!-- End table responsive -->
                            <!-- Cart discount -->
                            <div class="shopping-cart__discount">
                                <!-- Title -->
                                <h3 class="discount__title">{{ trans('messages.apply').' '.trans('messages.coupon_code')  }}</h3>
                                <!-- End title -->
                                <!-- Form -->

                                <form id="applyCouponForm" method="POST">
                                    <div class="discount__icon"><i class="lnil lnil-coin"></i></div>
                                    <input class="discount__input" type="text" id="couponCode" name="couponCode" placeholder="Enter Coupon Code" value="{{ $response['summary']['coupon_code'] }}">

                                    <input type="hidden" name="coupon_action" id="coupon_action" value="@if ($response['summary']['coupon_applied'] == 1) remove @else add @endif ">
                                    <button type="submit"  class="discount__submit">@if ($response['summary']['coupon_applied'] == 1) {{ trans('messages.remove') }} @else {{ trans('messages.apply') }} @endif</button>
                                </form>
                                
                                <!-- Success/Failure Message -->
                                <div id="couponMessage"></div>

                               
                                <!-- End form -->
                            </div>
                            <!-- End cart discount -->
                        </div>
                        <!-- End cart container -->
                    </div>
                    <!-- End left -->
                    <!-- Right -->
                    <div class="col-lg-5 col-xl-4">
                        <!-- Order summary -->
                        <div class="shopping-cart__order-summary">
                            <!-- Background -->
                            <div class="order-summary__background">
                                <!-- Title -->
                                <h3 class="order-summary__title">{{ trans('messages.order_summary') }}</h3>
                                <!-- End title -->
                                <!-- Subtotal -->
                                <div class="order-summary__subtotal">
                                    <div class="summary-subtotal__title">{{ trans('messages.subtotal') }}</div>
                                    <div class="summary-subtotal__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['sub_total'] }}</div>
                                </div>

                                <div class="order-summary__subtotal">
                                    <div class="summary-subtotal__title">{{ trans('messages.tax') }}</div>
                                    <div class="summary-subtotal__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['vat_amount'] }}</div>
                                </div>

                                <div class="order-summary__subtotal">
                                    <div class="summary-subtotal__title">{{ trans('messages.discount') }}</div>
                                    <div class="summary-subtotal__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['discount'] }}</div>
                                </div>

                                @if ($response['summary']['coupon_applied'] == 1) 
                                    <div class="order-summary__subtotal">
                                        <div class="summary-subtotal__title">{{ trans('messages.coupon').' '.trans('messages.discount') }}</div>
                                        <div class="summary-subtotal__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['coupon_discount'] }}</div>
                                    </div>
                                @endif
                                

                                <div class="order-summary__subtotal">
                                    <div class="summary-subtotal__title">{{ trans('messages.shipping_charge') }}</div>
                                    <div class="summary-subtotal__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['shipping'] }}</div>
                                </div>
                                <!-- End subtotal -->
                                <!-- Delivery method -->
                                {{-- <div class="order-summary__delivery-method">
                                    <select>
                                        <option>Standard Shipping ($20)</option>
                                        <option>Fast Shipping ($40)</option>
                                    </select>
                                </div> --}}
                                <!-- End delivery method -->
                                <!-- Total -->
                                <div class="order-summary__total">
                                    <div class="summary-total__title">{{ trans('messages.total') }}</div>
                                    <div class="summary-total__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['total'] }}</div>
                                </div>
                                <!-- End total -->
                                <!-- Proceed to checkout -->
                                <div class="order-summary__proceed-to-checkout">
                                    <button class="second-button proceedToCheckout" id="">{{ trans('messages.proceed_to_checkout') }}</button>
                                </div>
                                <!-- End proceed to checkout -->
                                <!-- Accept payment methods -->
                                <div class="order-summary__accept-payment-methods">
                                    <h4 class="accept-payment-methods__title">{{ trans('messages.accept_payment_methods') }}</h4>
                                    <img src="{{ asset('assets/images/default/payment.png')}}" alt="Payment" />
                                </div>
                                <!-- Accept payment methods -->
                            </div>
                            <!-- End background -->
                            <!-- Action -->
                            <div class="order-summary__action">
                                <a href="{{ route('products.index') }}">{{ trans('messages.continue_shopping') }}</a>
                            </div>
                            <!-- End action -->
                        </div>
                        <!-- End order summary -->
                    </div>
                    <!-- End right -->
                </div>
                <!-- End row -->
            </div>
            <!-- End second container -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shopping cart -->
@endsection


@section('script')
<script>
    $(document).ready(function () {
        $('#applyCouponForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            var couponCode = $('#couponCode').val(); // Get the coupon code from the input
            var coupon_action = $('#coupon_action').val();
            coupon_action = coupon_action.trim(); 

            var url = '';
            if(String(coupon_action) === "add"){
                url = "{{ route('coupon-apply')}}";
            }else{
                url = "{{ route('coupon-remove')}}";
            }

            $.ajax({
                url: url,  // URL to your backend endpoint
                type: 'POST',
                data: {
                    coupon: couponCode,
                    _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success: function (response) {
                    if (response.success) {
                        $('#couponMessage').html('<span style="color: green;">' + response.message + '</span>');
                        window.location.reload();
                    } else {
                        $('#couponMessage').html('<span style="color: red;">' + response.message + '</span>');
                    }
                },
                error: function (xhr, status, error) {
                    $('#couponMessage').html('<span style="color: red;">An error occurred. Please try again.</span>');
                }
            });
        });

        
    });

</script>
@endsection