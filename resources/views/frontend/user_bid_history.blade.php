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
                                            <th class="text-center">{{trans('messages.bid')}} {{trans('messages.amount')}}</th>
                                            <th class="text-center">{{trans('messages.time')}} {{trans('messages.placed')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($bids[0]))
                                            @foreach($bids as $key => $bid)
                                                <tr>
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td class="text-center">{{ env('DEFAULT_CURRENCY') }} {{ $bid->amount }}</td>
                                                    <td class="text-center">{{ $bid->created_at->format('Y-m-d H:i:s') }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-center">{{trans('messages.no_data_found')}}</td>
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