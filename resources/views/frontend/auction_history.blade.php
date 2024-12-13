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
                    {{trans('messages.auction').' '.trans('messages.history')}} </li>
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
                                            <th >{{trans('messages.product')}}</th>
                                            <th class="text-center">{{trans('messages.winning_status')}}</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($products[0]))
                                            @foreach ($products as $key => $prod)
                                                 <!-- Cart product item -->
                                                 <tr>
                                                    <td class="text-center">
                                                        <p class="cart-product__price">{{ $key + 1}}</p>
                                                    </td>
                                                    <td>
                                                        <div class="shopping-cart__product">
                                                            <div class="cart-product__image">
                                                                <a href="{{ route('auction.product-detail',['slug' => $prod->slug, 'sku' => $prod->sku]) }}">
                                                                    <img alt="Image" data-sizes="auto" data-srcset="{{ get_product_image($prod->thumbnail_img, '300') }}"
                                                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                                        class="lazyload" />
                                                                </a>
                                                            </div>
                                                            <div class="cart-product__title-and-variant">
                                                    
                                                                <h3 class="cart-product__title"><a href="{{ route('auction.product-detail',['slug' => $prod->slug, 'sku' => $prod->sku]) }}">{{ $prod->getTranslation('name', $lang) }}</a></h3>
                                                                
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td  class="text-center">
                                                        @php
                                                            $auctionEndDate = Carbon\Carbon::parse($prod->auction_end_date);
                                                            $isAuctionEnded = $auctionEndDate->isPast();

                                                            $winner = winningStatus($prod->id, $user->id);
                                                        @endphp
                                                      
                                                        @if ($isAuctionEnded)
                                                            @if ($winner == 1)
                                                                <span class="text-success">{{ trans('messages.winner') }}</span>
                                                            @else
                                                            <span class="error">{{ trans('messages.lost') }}</span>
                                                            @endif
                                                        @else
                                                            <p>{{ trans('messages.auction_still_active') }}</p>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="cart-product__delete">
                                                            <a class="sixth-button" href="{{ route('product.user-bid-history', ['productId' => $prod->id, 'userId' => $user->id]) }}">{{trans('messages.view')}} {{trans('messages.bid')}}  {{trans('messages.history')}} </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            
                                                <!-- End cart product item -->
                                            @endforeach
                                            
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">{{trans('messages.no_data_found')}}</td>
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
