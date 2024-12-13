@extends('frontend.layouts.app')
@section('content')
    <div class="shop-breadcrumb">
        <div class="container container--type-2">
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">{{ __('messages.home')}} </a></li>

                <li class="breadcrumb__item active" aria-current="page">
                    {{ __('messages.my_account')}} <small>{{__('messages.my_account_desc')}}</small></li>
            </ol>
        </div>
    </div>

    <div class="shopping-cart">
        <div class="container container--type-2">
            <div class="">
                <div class="row">
                    <div class="col-lg-12 col-xl-12">
                        <div class="shopping-cart__container">
                            <div class="table-responsive">
                                <table class="shopping-cart__table">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Shipping Address</th>
                                            <th>Price</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    {{-- <div class="shopping-cart__product"> --}}
                                                        {{-- <div class="cart-product__image">
                                                            <a
                                                                href="{{ route('vendor.product.view', $order->product_id) }}">
                                                                <img alt="{{ $order->product->name ?? 'Product Image' }}"
                                                                    data-sizes="auto"
                                                                    data-srcset="{{ asset($order->product->thumbnail_img) }} 400w, {{ asset($order->product->thumbnail_img) }} 800w"
                                                                    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                                    class="lazyload" />
                                                            </a>
                                                        </div> --}}
                                                        <div class="cart-product__title-and-variant">
                                                            <p class="text-muted mb-1">ORDER ID: {{ $order->order->code }}
                                                            </p>
                                                            <p class="text-success mb-1">Estimated Delivery:
                                                                {{ \Carbon\Carbon::parse($order->order->estimated_delivery)->format('F j, Y') }}
                                                            </p>
                                                            {{-- <h3 class="cart-product__title"><a
                                                                    href="#">{{ $order->product->name }}</a></h3> --}}
                                                            {{-- @if (!empty($order->variation))
                                                                @php
                                                                    $variations = json_decode($order->variation, true);
                                                                @endphp
                                                                <div class="d-flex flex-wrap justify-content-between w-100">
                                                                    @foreach ($variations as $variation)
                                                                        <div class="cart-product__variant">
                                                                            {{ strtoupper($variation['name']) }}:
                                                                            {{ $variation['value'] }}
                                                                        </div>
                                                                    @endforeach
                                                                    <div class="cart-product__variant"> QTY:
                                                                        {{ $order->quantity }} </div>
                                                                </div>
                                                            @endif --}}

                                                        </div>
                                                    {{-- </div> --}}
                                                </td>
                                                <td>
                                                    <div class="cart-product__price">
                                                        @php
                                                            // Decode the JSON string into an associative array
                                                            $shippingAddress = json_decode(
                                                                $order->order->shipping_address,
                                                                true,
                                                            );
                                                        @endphp
                                                            @if ($shippingAddress)
                                                                Name: {{ $shippingAddress['name'] ?? 'N/A' }}<br>
                                                                Email: {{ $shippingAddress['email'] ?? 'N/A' }}<br>
                                                                Address: {{ $shippingAddress['address'] ?? 'N/A' }}<br>
                                                                City: {{ $shippingAddress['city'] ?? 'N/A' }}<br>
                                                                Zipcode:{{ $shippingAddress['zipcode'] ?? 'N/A' }}<br>
                                                                Phone: {{ $shippingAddress['phone'] ?? 'N/A' }}
                                                            @else
                                                                <p>Shipping address not available.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="cart-product__price">
                                                        AED {{ $order->price }}
                                                    </div>
                                                </td>
                                                <td>
                                                    {{-- <div class="cart-product__delete">
                                                        <a class="sixth-button"
                                                            href="{{ route('vendor.order.view', $order->id) }}">View
                                                            Order</a>
                                                    </div> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- End table -->
                            </div>
                            <!-- End table responsive -->

                        </div>
                        <!-- End cart container -->
                    </div>
                    <!-- End left -->

                </div>
                <!-- End row -->
            </div>
            <!-- End second container -->
        </div>
        <!-- End container -->
    </div>
@endsection
