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
                    {{trans('messages.orders')}} <small>{{trans('messages.order_breadcrumb')}}

                    </small></li>
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
                    <div class="col-lg-12 col-xl-12">
                        <!-- Cart container -->
                        <div class="shopping-cart__container">
                            <!--- Table responsive -->
                            <div class="table-responsive">
                                <!-- Table -->
                                <table class="shopping-cart__table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{trans('messages.sl_no')}}</th>
                                            <th class="text-center">{{trans('messages.type')}}</th>
                                            <th>{{trans('messages.order_code')}}</th>
                                            <th class="text-center">{{trans('messages.total')}}</th>
                                            <th class="text-center">{{trans('messages.date')}}</th>
                                            <th class="text-center">{{trans('messages.payment_method')}}</th>
                                            <th class="text-center">{{trans('messages.delivery_status')}}</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($orderList[0]))
                                            @foreach ($orderList as $key => $order)
                                                 <!-- Cart product item -->
                                                <tr>
                                                    <td class="text-center">
                                                        <p class="cart-product__price">{{ $key + 1}}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">{{__('messages.saleq')}}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">{{$order->code}}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="cart-product__price">
                                                            {{ env('DEFAULT_CURRENCY') }} {{$order->grand_total}}

                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="cart-product__price">
                                                             {{ date('d-m-Y H:i A',strtotime($order->created_at))}}

                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="cart-product__price">
                                                             {{ strtoupper($order->payment_type) }}

                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="cart-product__price">
                                                             {{ strtoupper($order->delivery_status) }}

                                                        </div>
                                                    </td>


                                                    <td class="text-center">
                                                        <div class="cart-product__delete">
                                                            <a class="sixth-button" href="{{route('order-details',['code'=> $order->code])}}">{{ trans('messages.view').' '.trans('messages.order') }}</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- End cart product item -->
                                            @endforeach
                                            
                                            @if (!empty($rentorders[0]))
                                                @foreach ($rentorders as $key => $order)
                                                    <!-- Cart product item -->
                                                    <tr>
                                                        <td class="text-center">
                                                            <p class="cart-product__price">{{ $key + 1 }}</p>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1">{{ __('messages.rent') }}</p>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1">{{ $order->order_code }}</p>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="cart-product__price">
                                                                {{ env('DEFAULT_CURRENCY') }} {{ $order->grand_total }}

                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="cart-product__price">
                                                                {{ date('d-m-Y H:i A', strtotime($order->created_at)) }}

                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="cart-product__price">
                                                                {{ strtoupper($order->payment_type) }}

                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="cart-product__price">
                                                                {{ strtoupper($order->delivery_status) }}

                                                            </div>
                                                        </td>


                                                        <td class="text-center">
                                                            <div class="cart-product__delete">
                                                                <a class="sixth-button"
                                                                    href="{{ route('rent.order-details', ['code' => $order->order_code]) }}">{{ trans('messages.view') . ' ' . trans('messages.order') }}</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- End cart product item -->
                                                @endforeach
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">{{trans('messages.no_orders_found')}}</td>
                                            </tr>
                                        @endif
                                       

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
    <!-- End shopping cart -->
@endsection
