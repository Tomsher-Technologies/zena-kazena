@extends('frontend.layouts.app')
@section('content')
    <!-- Shop breadcrumb -->
    <div class="shop-breadcrumb">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Breadcrumb -->
            {{-- <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb__item"><a href="{{ route('home') }}">Home </a></li>
                <li class="breadcrumb__item"><a href="shop.html">Earrings</a></li>
                <li class="breadcrumb__item active" aria-current="page">18K Yellow & White Gold Diamond Earrings
                </li>
            </ol> --}}
            @if ($categoryData != null)
                {{ Breadcrumbs::render('category', $categoryData) }}
            @else
                {{ Breadcrumbs::render('home') }}
            @endif

            <!-- End breadcrumb -->
            <!-- Title -->
            <!-- End Title -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop breadcrumb -->
    <div class="shop-list-area">
        <div class="container container--type-2">
            <!-- Top filter -->
            <div class="collection__top-filter clearfix">
                <!-- Founded products -->
                <div class="collection__founded-products collection__founded-products--type-2">
                    <!-- <span>24</span> Products found -->
                    @if ($categoryData != null)
                        <h1 class="shop-list-name">{{ $categoryData->getTranslation('name', $lang) }}</h1>
                    @else
                        <h1 class="shop-list-name">{{ trans('messages.items_for_rent') }}</h1>
                    @endif

                </div>
                <!-- End founded products -->
                <!-- Sort and view -->
                <div class="collection__sort-and-view d-flex align-items-center">
                    <!-- Sort by -->
                    {{-- <form action="" id="productSort" method="GET"> --}}
                    <div class="products-sort-by d-flex align-items-center">
                        <label for="sort-by">{{ trans('messages.sort_by') }}</label>
                        <div class="products-sort-by__select">
                            <i class="lnil lnil-chevron-down"></i>
                            <select id="sort-by">
                                <option value="name_asc" @if ($sort_by == 'name_asc') selected @endif>
                                    {{ trans('messages.a_z') }}</option>
                                <option value="name_desc" @if ($sort_by == 'name_desc') selected @endif>
                                    {{ trans('messages.z_a') }}</option>
                                <option value="latest" @if ($sort_by == 'latest') selected @endif>
                                    {{ trans('messages.latest') }}</option>
                                <option value="oldest" @if ($sort_by == 'oldest') selected @endif>
                                    {{ trans('messages.oldest') }}</option>
                                <option value="price_low" @if ($sort_by == 'price_low') selected @endif>
                                    {{ trans('messages.price_low_high') }}</option>
                                <option value="price_high" @if ($sort_by == 'price_high') selected @endif>
                                    {{ trans('messages.price_high_low') }}</option>
                            </select>
                        </div>
                    </div>
                    {{-- </form> --}}
                    <!-- End sort by -->
                </div>
                <!-- End sort and view -->
            </div>
            <!-- End top filter -->
        </div>
    </div>
    <!-- Collection -->
    <div class="collection our-products minimal-our-products">
        <!-- Container -->
        <div class="container container--type-2">
            <!-- Open mobile filter -->
            <div class="open-mobile-top-filter js-open-mobile-filter">
                <i class="lnil lnil-control-panel"></i>
                <span>{{ trans('messages.show_filters') }}</span>
            </div>
            <!-- End open mobile filter -->
            <!-- Row -->
            <div class="row">
                <!-- Left column -->
                <div class="col-12 col-lg-3">
                    <!-- Filter -->
                    <div class="top-filter top-filter--left-column js-top-filter">
                        <!-- Close background -->
                        <div class="top-filter__close-background js-close-filter"></div>
                        <!-- End close background -->
                        <!-- Background -->
                        <div class="top-filter__background">
                            <!-- Close -->
                            <div class="top-filter__clo">
                                <a href="{{ route('rent.products') }}"><i class="lnil lnil-close"></i>
                                    {{ trans('messages.clear_filter') }}</a>
                            </div>
                            <!-- End close --->
                            <!-- Row -->
                            <form action="" id="productfilters" method="GET">
                                <div class="row">
                                    <input type="hidden" name="sort_by" id="sort_by" value="{{ $sort_by }}">
                                    <input type="hidden" name="category" value="{{ $category }}">
                                    <!-- Widget -->
                                    <div class="col-12 col-md-6 col-lg-25">
                                        <div class="top-filter__widget">
                                            <!-- Title -->
                                            <h2 class="widget__title">{{ trans('messages.category') }}</h2>
                                            <!-- End title -->
                                            @if (!empty($categories))
                                                <ul class="widget__collections">
                                                    @foreach ($categories as $cat)
                                                        <!-- Collections -->

                                                        <li><a class="@if ($category == $cat->getTranslation('slug', $lang)) active-category @endif"
                                                                href="{{ route('rent.products', ['category' => $cat->getTranslation('slug', $lang)]) }}">{{ $cat->getTranslation('name', $lang) }}</a>
                                                        </li>

                                                        <!-- End collections -->
                                                    @endforeach

                                                </ul>
                                            @endif

                                        </div>
                                    </div>
                                    <!-- End widget -->
                                    <!-- Widget -->
                                    <div class="col-12 col-md-6 col-lg-25">
                                        <div class="top-filter__widget">
                                            <!-- Title -->
                                            <h2 class="widget__title">{{ trans('messages.brands') }}</h2>
                                            <!-- End title -->
                                            <!-- Options -->
                                            <ul class="widget__checkbox-options">
                                                @if (!empty($brands))
                                                    @foreach ($brands as $brnd)
                                                        <!-- Material option -->
                                                        <li>
                                                            <label>
                                                                <input type="checkbox" name="brand[]"
                                                                    @if (!empty($brand)) {{ in_array($brnd->getTranslation('slug', $lang), $brand) ? 'checked' : '' }} @endif
                                                                    value="{{ $brnd->getTranslation('slug', $lang) }}" />
                                                                <span>{{ $brnd->getTranslation('name', $lang) }}</span>
                                                            </label>
                                                        </li>
                                                        <!-- End material option -->
                                                    @endforeach
                                                @endif
                                            </ul>
                                            <!-- End options -->
                                        </div>
                                    </div>
                                    <!-- End Widget -->
                                    <!-- Widget -->
                                    <div class="col-12 col-md-6 col-lg-25">
                                        <div class="top-filter__widget">
                                            <!-- Title -->
                                            <h2 class="widget__title">{{ trans('messages.occasions') }}</h2>
                                            <!-- End title -->
                                            <!-- Options -->
                                            <ul class="widget__checkbox-options">
                                                @if (!empty($occasions))
                                                    @foreach ($occasions as $occ)
                                                        <!-- Color option -->
                                                        <li>
                                                            <label>
                                                                <input type="checkbox" name="occasion[]"
                                                                    value="{{ $occ->getTranslation('slug', $lang) }}"
                                                                    @if (!empty($occasion)) {{ in_array($occ->getTranslation('slug', $lang), $occasion) ? 'checked' : '' }} @endif>
                                                                <span>{{ $occ->getTranslation('name', $lang) }}</span>
                                                            </label>
                                                        </li>
                                                        <!-- End color option -->
                                                    @endforeach
                                                @endif
                                            </ul>
                                            <!-- End options -->
                                        </div>
                                    </div>
                                    <!-- End widget -->

                                    <!-- Price -->
                                    <div class="col-12 col-md-6 col-lg-25">
                                        <!-- Widget -->
                                        <div class="top-filter__widget">
                                            <!-- Widget title -->
                                            <h2 class="widget__title">{{ trans('messages.price') }}</h2>
                                            <!-- End widget title -->
                                            <!-- Price slider -->
                                            <div class="widget__price-slider">
                                                <div class="js-price-slider"></div>
                                                <div class="price-slider__value">
                                                    <span>{{ trans('messages.from') }}</span>
                                                    <input type="text" class="js-price-slider-value" name="price"
                                                        value="{{ $price }}" />
                                                </div>
                                            </div>
                                            <!-- End price slider -->
                                        </div>
                                        <!-- End widget -->
                                    </div>
                                    <!-- End price -->
                                </div>
                            </form>
                            <!-- End row -->
                        </div>
                        <!-- End background -->
                    </div>
                    <!-- End filter -->
                </div>
                <!-- End left column -->
                <!-- Right column -->
                <div class="col-12 col-lg-9">
                    <!-- Products -->
                    <div class="row">

                        @if (!empty($products))
                            @foreach ($products as $prod)
                                @php
                                    $priceData = getProductOfferPrice($prod);
                                @endphp

                                <!-- Product -->
                                <div class="col-6 col-md-4 col-xl-3">
                                    <div class="product-grid-item product-grid-item--type-2 product-grid-item--type-5">
                                        <!-- Product tag -->
                                        @if ($priceData['offer_tag'] != '')
                                            <div class="product-grid-item__tag">{{ $priceData['offer_tag'] }}</div>
                                        @endif

                                        @php
                                            $wishlisted = productWishlisted($prod->sku, $prod->slug);
                                        @endphp

                                        <div class="product-grid-item__wishlist-right-0 wishlist-btn"
                                            data-product-slug="{{ $prod->slug }}"
                                            data-product-sku="{{ $prod->sku }}"><i
                                                class="lnr lnr-heart {{ $wishlisted != 0 ? 'active' : '' }}"></i></div>
                                        <!-- End product tag -->
                                        <!-- Product images -->
                                        <div class="product-grid-item__images product-grid-item__images--ratio-100-122 js-product-grid-images"
                                            data-current-image="0">
                                            <!-- Product images arrows -->
                                            <div class="product-grid-item__images-arrows">
                                                <!-- Previous -->
                                                <div
                                                    class="product-grid-item__previous-image js-product-grid-previous-image">
                                                    <i class="lnr lnr-chevron-left"></i></div>
                                                <!-- End previous -->
                                                <!-- Previous -->
                                                <div class="product-grid-item__next-image js-product-grid-next-image"><i
                                                        class="lnr lnr-chevron-right"></i></div>
                                                <!-- End previous -->
                                            </div>
                                            <!-- End product images arrows -->

                                            @php
                                                $images = explode(',', $prod->photos);
                                                if ($prod->thumbnail_img != null) {
                                                    array_unshift($images, $prod->thumbnail_img);
                                                }
                                            @endphp

                                            @if (!empty($images))
                                                @foreach ($images as $imgkey => $img)
                                                    <!-- Product image -->
                                                    <div
                                                        class="product-grid-item__image js-product-grid-image @if ($imgkey == 0) active @endif">
                                                        <a
                                                            href="{{ route('rent.product-detail', ['slug' => $prod->slug, 'sku' => $prod->sku]) }}">
                                                            @php
                                                                $imagePath = get_product_image($img, '300');
                                                                $imageExists = \Illuminate\Support\Facades\Storage::disk(
                                                                    'public',
                                                                )->exists($img); // Check storage
                                                            @endphp

                                                            <img alt="Image" data-sizes="auto"
                                                                data-srcset="{{ $imageExists && $imagePath ? $imagePath : $img }}"
                                                                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                                class="lazyload" />
                                                        </a>
                                                    </div>
                                                    <!-- End product image -->
                                                @endforeach
                                            @endif
                                            <!-- Quick shop -->
                                            {{-- <div class="product-grid-item__quick-shop">
                                                <a href="#" class="add-to-cart-btn" data-product-slug="{{$prod->slug}}" data-product-sku="{{ $prod->sku}}">{{ trans('messages.add_to_cart') }}</a>
                                            </div> --}}
                                            <!-- End quick shop -->
                                        </div>
                                        <!-- End product images -->
                                        <!-- Product feature -->
                                        <div class="product-grid-item__feature">
                                            {{ $prod->brand ? $prod->brand->getTranslation('name', $lang) : '' }}</div>
                                        <!-- End product feature -->
                                        <!-- Product name -->
                                        <div class="product-grid-item__name">
                                            <a
                                                href="{{ route('rent.product-detail', ['slug' => $prod->slug, 'sku' => $prod->sku]) }}">{{ $prod->getTranslation('name', $lang) }}</a>
                                        </div>
                                        <!-- End product name -->
                                        <div class="product__reviews" bis_skin_checked="1">
                                            <i class="lnr lnr-star lnir active"></i>
                                            <i class="lnr lnr-star lnir active"></i>
                                            <i class="lnr lnr-star lnir active"></i>
                                            <i class="lnr lnr-star lnir active"></i>
                                            <i class="lnr lnr-star lnir"></i>
                                            <span>3 reviews</span>
                                        </div>
                                        <!-- Product price -->
                                        <div class="product-grid-item__price">
                                            <!-- Price new -->
                                            <span class="product-grid-item__price-new "><span
                                                    class="text-black">{{ __('messages.daily_rent') }}:</span>
                                                {{ env('DEFAULT_CURRENCY') . ' ' . $priceData['discounted_price'] }}</span></br>
                                            <span class="product-grid-item__price-new"><span
                                                    class="text-black">{{ __('messages.deposit') }}:</span>
                                                {{ env('DEFAULT_CURRENCY') . ' ' . $prod['deposit'] }}</span>
                                            <!-- End price new -->
                                            <!-- Price old -->
                                            @if ($priceData['discounted_price'] != $priceData['original_price'])
                                                <span
                                                    class="product-grid-item__price-old">{{ env('DEFAULT_CURRENCY') . ' ' . $priceData['original_price'] }}</span>
                                            @endif

                                            <!-- End price old -->
                                        </div>
                                        <!-- End product price -->
                                    </div>
                                </div>
                                <!-- End Product -->
                            @endforeach
                        @endif

                        <!-- Loading spin -->
                        <div class="loading-spin text-center">

                            {{-- <ul class="standard-pagination">
                            <li><a href="#" class="active">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">Next</a></li>
                        </ul> --}}
                            <div class="standard-pagination">
                                {{ $products->appends(request()->input())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                        <!-- End loading spin -->


                    </div>
                    <!-- End right column -->
                </div>
                <!-- End row -->
            </div>
            <!-- End container -->
        </div>
        <!-- End collection -->
    @endsection

    @section('script')
        <script>
            const filterForm = document.getElementById('productfilters');
            document.addEventListener('DOMContentLoaded', function() {

                // Listen for change events on all checkboxes
                filterForm.addEventListener('change', function() {
                    filterForm.submit();
                });


                $(".js-price-slider-value").val('{{ $price }}');
            });

            $(".js-price-slider").on("slidechange", function(event, ui) {
                filterForm.submit();
            });


            $('#sort-by').on('change', function() {
                var sort = $(this).val();
                $('#sort_by').val(sort);
                $('#productfilters').submit();
            });
        </script>
    @endsection
