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
                    {{trans('messages.wishlist')}} <small>({{ wishlistCount() }} {{trans('messages.items')}})</small></li>
            </ol>
            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->
    <!-- Shopping cart -->
    <div class="wishlist">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Second container -->
            <div class="">
                <!--- Table responsive -->
                <div class="table-responsive">
                    <!-- Table -->
                    <table class="wishlist__table">
                        <thead>
                            <tr>
                                <th>{{trans('messages.product')}}</th>
                                <th>{{trans('messages.price')}}</th>
                                <th>{{trans('messages.status')}}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($result[0]))
                                @foreach ($result as $res)
                                    <!-- Wishlist product item -->
                                    <tr class="wishlist-item">
                                        <td>
                                            <div class="wishlist__product">
                                                <div class="wishlist-product__image">
                                                    <a href="{{ route('product-detail',['slug' => $res['product']['slug'], 'sku' => $res['product']['sku']]) }}">
                                                        <img alt="Image" data-sizes="auto"
                                                            data-srcset="{{ $res['product']['thumbnail_image']}}"
                                                            src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                            class="lazyload" />
                                                    </a>
                                                </div>
                                                <h3 class="wishlist-product__title"><a href="{{ route('product-detail',['slug' => $res['product']['slug'], 'sku' => $res['product']['sku']]) }}">{{$res['product']['name']}}</a></h3>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="wishlist-product__price">
                                                {{ env('DEFAULT_CURRENCY').' '.$res['product']['stroked_price'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="wishlist-product__status wishlist-product__status--in-stock">
                                                @if ($res['product']['quantity'] > 0)
                                                    <span class="status__value status__value--in-stock">{{trans('messages.in_stock')}}</span>
                                                @else
                                                    <span class="status__value ">{{ trans('messages.out_of_stock') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="sixth-button add-to-cart-btn" data-product-slug="{{$res['product']['slug']}}" data-product-sku="{{ $res['product']['sku']}}">{{ trans('messages.add_to_cart') }}</a>
                                        </td>
                                        <td>
                                            <div class="wishlist-product__delete" data-id="{{ $res['id'] }}">
                                                <a href="#"><i class="lnil lnil-close"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- End wishlist product item -->
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">{{trans('messages.no_product_in_wishlist')}}</td>
                                </tr>
                            @endif
                            
                           
                        </tbody>
                    </table>
                    <!-- End table -->
                </div>
                <!-- End table responsive -->
            </div>
            <!-- End second container -->
        </div>
        <!-- End container -->
    </div>
    <!-- End wishlist -->
@endsection

@section('script')
<script>
    $(document).on('click', '.wishlist-product__delete', function (e) {
        e.preventDefault();

        const button = $(this);
        const id = button.data('id');

        $.ajax({
            url: '/wishlist/delete',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
            },
            success: function (response) {
                if (response.success === true) {
                    // Optionally, you can update the cart summary here
                    button.closest('.wishlist-item').remove(); 
                    toastr.success(response.message, "{{trans('messages.success')}}");
                    window.location.reload();
                } else {
                    toastr.error(response.message, "{{trans('messages.error')}}");
                }
            },
            error: function () {
                toastr.error("{{trans('messages.something_went_wrong')}}", "{{trans('messages.error')}}");
            },
        });
    });

</script>
@endsection