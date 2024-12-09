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
                    {{ trans('messages.checkout') }}</li>
            </ol>
            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->

    <!-- Checkout -->
    <div class="checkout">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Second container -->
            <div class="">

                <!-- Form -->
                <div class="row">
                    <!-- Left column -->
                    <div class="col-lg-7">
                        <!-- Container -->
                        <div class="checkout__container">
                            <!-- Row -->
                            <div class="row">
                                <h3 class="discount__title ml-1">{{ trans('messages.apply').' '.trans('messages.coupon_code')  }}</h3>
                                <div class="col-12 col-md-12 checkout__promo-code mb-1" >
                                    <form id="applyCouponForm" method="POST">
                                        <div class="promo-code__icon"><i class="lnil lnil-coin"></i></div>
                                        <input class="promo-code__input" type="text" id="couponCode" name="couponCode" placeholder="Enter Coupon Code" value="{{ $response['summary']['coupon_code'] }}">
    
                                        <input type="hidden" name="coupon_action" id="coupon_action" value="@if ($response['summary']['coupon_applied'] == 1) remove @else add @endif ">
                                        <button type="submit"  class="promo-code__apply">@if ($response['summary']['coupon_applied'] == 1) {{ trans('messages.remove') }} @else {{ trans('messages.apply') }} @endif</button>
                                    </form>
                                   
                                    <!-- Success/Failure Message -->
                                    
                                </div>
                                <div id="couponMessage" class=" mb-1"></div>
                            </div>
                            @if (!empty($addresses[0]))
                                <div class="address">
                                    <h3 class="discount__title" style="margin-left:10px;">{{ trans('messages.saved') }} {{ trans('messages.address') }}</h3>
                                    <div class="row">
                                        
                                            @foreach ($addresses as $key => $address)
                                                <div class="col-12 col-md-6 addressSelect" style="cursor:pointer;" id="address-{{ $address->id }}"  data-id="{{ $address->id }}" data-name="{{ $address->name }}" data-address="{{ $address->address }}" data-city="{{ $address->city }}" data-zipcode="{{ $address->postal_code }}" data-phone="{{ $address->phone }}" >
                                                    <div class="customer-information__section addressDiv @if(old('address_id') == $address->id) default-address @endif">
                                                        <h4 class="order-information__section-heading">{{ trans('messages.address') }} {{$key+1}}
                                                        </h4>
                                                        <div class="order-information__section-content">
                                                            {{ $address->name }}<br>
                                                            {!! nl2br($address->address) !!}<br>
                                                            {{ $address->city }}<br>
                                                            {{trans('messages.zipcode')}} : {{ $address->postal_code }}<br>
                                                            {{trans('messages.phone')}} : {{ $address->phone }}<br>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        
                                    </div>
                                </div>
                            @endif
                            <!-- End row -->
                            <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST">
                                @csrf
                                <!-- Billing form -->
                                <div class="billing-form">
                                    <!-- Title -->
                                    <h3 class="billing-form__heading">{{ trans('messages.billing_details') }}</h3>
                                    <!-- End title -->
                                    <input type="hidden" id="address_id" name="address_id">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{trans('messages.full_name')}} *"  id="billing_name" name="billing_name" class="form-group__input" value="{{ old('billing_name') }}">
                                        @if ($errors->has('billing_name'))
                                            <div class="alert alert-danger">{{ $errors->first('billing_name') }}</div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <textarea placeholder="{{trans('messages.address')}} *" id="billing_address" name="billing_address" class="form-group__input" >{{ old('billing_address') }}</textarea>
                                        @if ($errors->has('billing_address'))
                                            <div class="alert alert-danger">{{ $errors->first('billing_address') }}</div>
                                        @endif
                                    </div>
                                  
                                    <div class="form-group">
                                        <input type="text"  id="billing_city" name="billing_city" placeholder="{{trans('messages.town_city')}} *" class="form-group__input" value="{{ old('billing_city') }}">
                                        @if ($errors->has('billing_city'))
                                            <div class="alert alert-danger">{{ $errors->first('billing_city') }}</div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="text" placeholder="{{trans('messages.zip')}} *"  id="billing_zipcode" name="billing_zipcode" class="form-group__input" value="{{ old('billing_zipcode') }}">
                                        @if ($errors->has('billing_zipcode'))
                                            <div class="alert alert-danger">{{ $errors->first('billing_zipcode') }}</div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="text" placeholder="{{trans('messages.phone')}} *"  id="billing_phone" name="billing_phone" class="form-group__input" value="{{ old('billing_phone') }}">
                                        @if ($errors->has('billing_phone'))
                                            <div class="alert alert-danger">{{ $errors->first('billing_phone') }}</div>
                                        @endif
                                    </div>
                                   
                                    <div class="form-group">
                                        <input type="text"  id="billing_email" name="billing_email" placeholder="{{trans('messages.email')}} *" class="form-group__input" value="{{ old('billing_email') }}">
                                        @if ($errors->has('billing_email'))
                                            <div class="alert alert-danger">{{ $errors->first('billing_email') }}</div>
                                        @endif
                                    </div>
                                  
                                    <div class="form-checkbox">
                                        <input type="checkbox" id="checkbox2" name="same_as_billing" class="form-checkbox__input" @if(old('same_as_billing') == 'on') checked @endif/>
                                        <label class="form-checkbox__label" for="checkbox2">{{trans('messages.same_shipping')}}</label>
                                    </div>
                                    <!-- End form checkbox -->

                                    <div class="shipping-address">
                                        <h3 class="billing-form__heading">{{ trans('messages.shipping_details') }}</h3>
                                        <div class="form-group">
                                            <input type="text" placeholder="{{trans('messages.full_name')}} *"  id="shipping_name" name="shipping_name" class="form-group__input" value="{{ old('shipping_name') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_name'))
                                                <div class="alert alert-danger">{{ $errors->first('shipping_name') }}</div>
                                            @endif
                                        </div>
                                        
                                        <div class="form-group">
                                            <textarea placeholder="{{trans('messages.address')}} *" id="shipping_address" name="shipping_address" class="form-group__input" @if(old('same_as_billing') == 'on') readonly @endif>{{ old('shipping_address') }}</textarea>
                                            @if ($errors->has('shipping_address'))
                                                <div class="alert alert-danger">{{ $errors->first('shipping_address') }}</div>
                                            @endif
                                        </div>
                                      
                                        <div class="form-group">
                                            <input type="text"  id="shipping_city" name="shipping_city" placeholder="{{trans('messages.town_city')}} *" class="form-group__input" value="{{ old('shipping_city') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_city'))
                                                <div class="alert alert-danger">{{ $errors->first('shipping_city') }}</div>
                                            @endif
                                        </div>
                                        
                                        <div class="form-group">
                                            <input type="text" placeholder="{{trans('messages.zip')}} *"  id="shipping_zipcode" name="shipping_zipcode" class="form-group__input"  value="{{ old('shipping_zipcode') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_zipcode'))
                                                <div class="alert alert-danger">{{ $errors->first('shipping_zipcode') }}</div>
                                            @endif
                                        </div>
                                        
                                        <div class="form-group">
                                            <input type="text" placeholder="{{trans('messages.phone')}} *"  id="shipping_phone" name="shipping_phone" class="form-group__input" value="{{ old('shipping_phone') }}" @if(old('same_as_billing') == 'on') readonly @endif>
                                            @if ($errors->has('shipping_zipcode'))
                                                <div class="alert alert-danger">{{ $errors->first('shipping_zipcode') }}</div>
                                            @endif
                                        </div>
                                       
                                    </div>

                                    <!-- Form group -->
                                    <div class="form-group">
                                        <textarea placeholder="{{trans('messages.order_note')}}" name="order_note" class="form-group__textarea" rows="5"></textarea>
                                        <input type="hidden" name="payment_method" id="payment_method" value="cod">
                                    </div>
                                    <!-- End form group -->

                                   
                                </div>
                                <!-- End billing form -->
                            </form>
                        </div>
                        <!-- End container -->
                    </div>
                    <!-- End left column -->
                    <!-- Right column -->
                    <div class="col-lg-5">
                        <!-- Summary -->
                        <div class="checkout__summary">
                            <!-- Title --->
                            <h3 class="checkout-summary__title">{{ trans('messages.your').' '.trans('messages.order') }}</h3>
                            <!-- End title -->
                            <!-- Products -->
                            <div class="checkout__products">
                                @if(!empty($response['products']))
                                    @foreach ($response['products'] as $prod)
                                    <!-- Product -->
                                    <div class="checkout__product">
                                        <div class="checkout-product__image">
                                            <a href="{{ route('product-detail',['slug' => $prod['product']['slug'], 'sku' => $prod['product']['sku']]) }}">
                                                <img alt="Image" data-sizes="auto"
                                                    data-srcset="{{$prod['product']['image']}}"
                                                    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                    class="lazyload" />
                                            </a>
                                        </div>
                                        <div class="checkout-product__title-and-variant">
                                            <h3 class="checkout-product__title"><a href="{{ route('product-detail',['slug' => $prod['product']['slug'], 'sku' => $prod['product']['sku']]) }}">{{$prod['product']['name']}}</a></h3>

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
                                           
                                        </div>
                                        <div class="checkout-product__price">{{$prod['quantity']}} x {{ env('DEFAULT_CURRENCY') }} {{$prod['total']}}</div>
                                    </div>
                                    <!-- End product -->
                                    @endforeach
                                @endif  
                            </div>
                            <!-- End products -->
                            <!-- Subtotal and shipping -->
                            <div class="checkout__subtotal-and-shipping">
                                <!-- Subtotal -->
                                <div class="checkout__subtotal">
                                    <div class="checkout-subtotal__title">{{ trans('messages.subtotal') }}</div>
                                    <div class="checkout-subtotal__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['sub_total'] }}</div>
                                </div>
                                <!-- End subtotal -->
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
                                <!-- End shipping -->
                            </div>
                            <!-- End subtotal and shipping -->
                            <!-- Total -->
                            <div class="checkout__total">
                                <div class="checkout-total__title">{{ trans('messages.total') }}</div>
                                <div class="checkout-total__price">{{ env('DEFAULT_CURRENCY') }} {{ $response['summary']['total'] }}</div>
                            </div>
                            <!-- End total -->
                            <!-- Payment methods -->
                            <div class="checkout__payment-methods">
                                <!-- Payment method -->
                                <div class="checkout-payment-method">
                                    <input type="radio" name="payment-method" value="card"id="payment1" />
                                    <label for="payment1">
                                        {{ trans('messages.card') }}
                                    </label>
                                </div>
                                <!-- End payment method -->
                               
                                <!-- Payment method -->
                                <div class="checkout-payment-method">
                                    <input type="radio" name="payment-method" value="cod" id="payment3" checked />
                                    <label for="payment3">
                                        {{ trans('messages.cod') }}
                                    </label>
                                </div>
                                <!-- End payment method -->
                                
                                <!-- End payment method -->
                            </div>
                            <!-- End payment methods -->
                            <!-- Privacy policy -->
                            <div class="checkout__privacy-policy">
                                {{ trans('messages.checkout_msg') }} <a href="#">{{ trans('messages.privacy_policy') }}</a>.
                            </div>
                            <!-- End privacy policy -->
                            <!-- Action -->
                            <div class="checkout__action">
                                <button type="button" id="submitCheckout" class="second-button">{{ trans('messages.place_order') }}</button>
                            </div>
                            <!-- End action -->
                        </div>
                        <!-- End summary -->
                    </div>
                    <!-- End right column -->
                </div>
                <!-- End form -->
            </div>
            <!-- End second container -->
        </div>
        <!-- End container -->
    </div>
    <!-- End checkout -->
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

        $('#proceedToCheckout').on('click', function () {
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


        $('#checkbox2').on('change', function () {
            if ($(this).is(':checked')) {
                $('#shipping_name').val($('#billing_name').val());
                $('#shipping_address').val($('#billing_address').val());
                $('#shipping_city').val($('#billing_city').val());
                $('#shipping_zipcode').val($('#billing_zipcode').val());
                $('#shipping_phone').val($('#billing_phone').val());
                
                $('.shipping-address input').prop('readonly', true);
            } else {
                $('.shipping-address textarea').val('');
                $('.shipping-address input').val('').prop('readonly', false);
            }
        });

        $('input[name="payment-method"]').click(function() {
            var selectedPaymentMethod = $(this).val();
            $('#payment_method').val(selectedPaymentMethod);
        });
        
        $('#submitCheckout').on('click', function () {
            $('#checkoutForm').submit();
        });

        
        $(document).on('click', '.addressSelect', function () {
            $('.addressDiv').removeClass('default-address');
            $(this).find('.addressDiv').addClass('default-address');
            let id = $(this).data('id');
            let name = $(this).data('name');
            let address = $(this).data('address');
            let phone = $(this).data('phone');
            let city = $(this).data('city');
            let zipcode = $(this).data('zipcode');

            $('#address_id').val(id);
            $('#billing_address').val(address);
            $('#billing_name').val(name);
            $('#billing_phone').val(phone);
            $('#billing_city').val(city);
            $('#billing_zipcode').val(zipcode);
            if ($('#checkbox2').is(':checked')) {
                $('#shipping_name').val(name);
                $('#shipping_address').val(address);
                $('#shipping_city').val(city);
                $('#shipping_zipcode').val(zipcode);
                $('#shipping_phone').val(phone);
            }
        });

    });

</script>
@endsection
