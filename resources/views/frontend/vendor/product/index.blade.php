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
                    {{ trans('messages.products') }} <small>{{ trans('messages.order_breadcrumb') }}

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
                                            <th class="text-center">{{ trans('messages.sl_no') }}</th>
                                            <th class="text-center">{{ trans('messages.image') }}</th>
                                            <th class="text-center">{{ trans('messages.type') }}</th>
                                            <th class="text-center">{{ trans('messages.category') }}</th>
                                            <th>SKU</th>
                                            <th class="text-center">{{ trans('messages.name') }}</th>
                                            <th class="text-center">{{ trans('messages.price') }}</th>
                                            <th class="text-center">{{ trans('messages.quantity') }}</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($products))
                                            @foreach ($products as $product)
                                                <!-- Cart product item -->
                                                <tr>
                                                    <td class="text-center">
                                                        <p class="cart-product__price">{{ $loop->iteration }}</p>
                                                    </td>
                                                    <td>
                                                        <img src="{{ asset($product->thumbnail_img) }}"
                                                            class="img-fluid img-fit avatar"
                                                            style="max-width: 50px; height: auto;" alt="Business Logo">
                                                        {{-- <p class="text-muted mb-1">{{$product->thumbnail_img}}</p> --}}
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">{{ $product->type }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">{{ $product->category->name }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">{{ $product->sku }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">{{ $product->name }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="cart-product__price">
                                                            {{ env('DEFAULT_CURRENCY') }}
                                                            {{ $product->stocks->first()->offer_price }}

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-1">
                                                            {{ $product->stocks->first()->qty }}
                                                        </p>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="cart-product__delete">
                                                            <a class="sixth-button"
                                                                href="{{ route('vendor.product.view', ['id' => $product->id]) }}">{{ trans('messages.view') . ' ' . trans('messages.product') }}</a>
                                                            <a href="{{ route('vendor.product.edit', ['id' => $product->id]) }}"
                                                                class="sixth-button">
                                                                <i class="fa fa-pencil-alt"></i>
                                                                {{ trans('messages.edit') }}
                                                            </a>

                                                            <!-- Delete Product (Deactivate) -->
                                                            @if ($product->published == 1)
                                                                <form
                                                                    action="{{ route('vendor.product.deactivate', ['id' => $product->id]) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('get')
                                                                    <!-- Assuming GET method for deactivation -->
                                                                    <button type="submit" class="sixth-button"
                                                                        onclick="return confirm('{{ trans('messages.confirm_deactivate') }}');">
                                                                        <i class="fa fa-trash-alt"></i>
                                                                        {{ trans('messages.deactivate') }}
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="badge bg-danger">{{ trans('messages.inactive') }}</span>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    {{ trans('messages.no_products_found') }}</td>
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
