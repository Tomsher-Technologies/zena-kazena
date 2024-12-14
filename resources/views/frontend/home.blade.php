@extends('frontend.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Responsive Video -->
        <div class="video-container">
            <video class="video" loop autoplay muted>
                <source src="{{ asset($page->video) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="caption">
                <h1>Create your rings with award-winning jewellers</h1>
            </div>
        </div>       
        <!-- End Responsive Video -->
    </div>
    <!-- New in -->
    <div class="shoppable-new-in">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Title and action -->
            <div class="shoppable-new-in__title-and-action">
                <!-- Title -->
                <h4 class="shoppable-new-in__title font-family-jost">{{ $page->getTranslation('heading1', $lang) }}</h4>
                <!-- End title -->
            </div>
            <!-- End title and action -->
            <!-- Row -->
            <div class="row">

                @if(!empty($data['discover_categories']))
                    @foreach($data['discover_categories'] as $discover_categories)
                        <div class="col-12 col-md-6 col-lg-3">
                            <!-- Category -->
                            <a href="{{ route('products.index',['category' => $discover_categories->getTranslation('slug', $lang)]) }}" class="shoppable-new-in__category">
                                <img alt="Image" data-sizes="auto"
                                    data-srcset="{{ uploaded_asset($discover_categories->getTranslation('icon', $lang)) }}"
                                    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                    class="lazyload" />
                                <p>{{ $discover_categories->getTranslation('name', $lang) }}</p>
                            </a>
                            <!-- End category -->
                        </div>
                    @endforeach
                @endif
                
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </div>
    <!-- End new in -->
    <!-- Shop categories -->
    <div class="shop-categories">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Row -->
            <div class="row">

                @if(!empty($data['banners']['home_mid_banner']))
                    @foreach ($data['banners']['home_mid_banner'] as $midKey => $home_mid_banner)
                        <!-- Category -->
                        <div class="col-12   @if($midKey == 0) col-xl-7 @else col-xl-5  @endif">
                            <div class="shop-category shop-category--medium">
                                <!-- Image -->
                                <div class="shop-category__image">
                                    @php
                                        $linktype = $home_mid_banner['type'];
                                        $link = '#';
                                        if($linktype == 'external'){
                                            $link = $home_mid_banner['link'];
                                        }
                                        if($linktype == 'product'){
                                            $prod_sku = getProductSkuFromSlug($home_mid_banner['link']);
                                            if($prod_sku != null){
                                                $link = route('product-detail',['slug' => $home_mid_banner['link'], 'sku' => $prod_sku]);
                                            }else {
                                                $link = '#';
                                            }
                                        }
                                        if($linktype == 'category'){
                                            $link = route('products.index',['category' => $home_mid_banner['link']]);
                                        }
                                    @endphp
                                    <a href="{{$link}}">
                                        <img alt="Image" data-sizes="auto"
                                            data-srcset="{{ $home_mid_banner['image'] }}"
                                            src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                            class="lazyload" />
                                    </a>
                                </div>
                                <!-- End image -->
                                <!-- Small heading -->
                                {{-- <h4 class="shop-category__small-heading"><a href="shop.html">SALE UP TO 40%</a></h4> --}}
                                <!-- End small heading -->
                                <!-- Heading and description -->
                                <div class="shop-category__heading-and-description">
                                    <!-- Heading -->
                                    <h4 class="shop-category__heading"><a href="#" style="color: #000"> {{ $home_mid_banner['title'] }}</a>
                                    </h4>
                                    <!-- End heading -->
                                    <!-- Description -->
                                    <div class="shop-category__description">
                                        <a href="#">{{ $home_mid_banner['sub_title'] }} </a>
                                    </div>
                                    <!-- End description -->
                                </div>
                                <!-- End heading and description -->
                            </div>
                        </div>
                        <!-- End category -->
                    @endforeach
                @endif
                
               
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop categories -->
    <!-- Our products -->
    <div class="our-products minimal-our-products">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Title -->
            <div class="our-products__title-and-action d-flex justify-content-center">
                <h4 class="our-products__title">{{ $page->getTranslation('heading2', $lang) }}</h4>
            </div>
            <!-- End title -->
            <!-- Products -->
            <div class="row">

                @if (!empty($data['new_arrival_products']))
                    @foreach ($data['new_arrival_products'] as $new_arrival_products)
                        @php
                            $priceData = getProductOfferPrice($new_arrival_products);
                        @endphp
                        <!-- Product -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="product-grid-item product-grid-item--type-2 product-grid-item--type-5">
                                <!-- Product tag -->
                                @if ($priceData['offer_tag'] != '')
                                    <div class="product-grid-item__tag">{{ $priceData['offer_tag'] }}</div>
                                @endif
                                @php
                                    $wishlistedNew = productWishlisted($new_arrival_products->sku, $new_arrival_products->slug);
                                @endphp
                                <div class="product-grid-item__wishlist-right-0  wishlist-btn" data-product-slug="{{ $new_arrival_products->slug }}" data-product-sku="{{ $new_arrival_products->sku }}" ><i class="lnr lnr-heart {{($wishlistedNew != 0) ? 'active' : '' }}"></i></div>
                                <!-- End product tag -->
                                <!-- Product images -->
                                <div class="product-grid-item__images product-grid-item__images--ratio-100-122 js-product-grid-images"
                                    data-current-image="0">
                                    <!-- Product images arrows -->
                                    <div class="product-grid-item__images-arrows">
                                        <!-- Previous -->
                                        <div class="product-grid-item__previous-image js-product-grid-previous-image"><i
                                                class="lnr lnr-chevron-left"></i></div>
                                        <!-- End previous -->
                                        <!-- Previous -->
                                        <div class="product-grid-item__next-image js-product-grid-next-image"><i
                                                class="lnr lnr-chevron-right"></i></div>
                                        <!-- End previous -->
                                    </div>
                                    <!-- End product images arrows -->

                                    @php
                                        $images = explode(',',$new_arrival_products->photos);
                                        if($new_arrival_products->thumbnail_img != null){
                                            array_unshift($images, $new_arrival_products->thumbnail_img);
                                        }
                                    @endphp

                                    @if (!empty($images))
                                        @foreach ($images as $imgkey => $img)
                                             <!-- Product image -->
                                            <div class="product-grid-item__image js-product-grid-image @if($imgkey == 0) active @endif">
                                                <a href="{{ route('product-detail',['slug' => $new_arrival_products->slug, 'sku' => $new_arrival_products->sku]) }}">
                                                    <img alt="Image" data-sizes="auto"
                                                        data-srcset="{{get_product_image($img,'300')}}"
                                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                        class="lazyload" />
                                                </a>
                                            </div>
                                            <!-- End product image -->
                                        @endforeach
                                    @endif
                                    
                                    <!-- Quick shop -->
                                    <div class="product-grid-item__quick-shop">
                                        <a href="#" class="add-to-cart-btn" data-product-slug="{{$new_arrival_products->slug}}" data-product-sku="{{ $new_arrival_products->sku}}">{{ trans('messages.add_to_cart') }}</a>
                                    </div>
                                    <!-- End quick shop -->
                                </div>
                                <!-- End product images -->
                                <!-- Product feature -->
                                <div class="product-grid-item__feature">{{ $new_arrival_products->brand->getTranslation('name', $lang) }}</div>
                                <!-- End product feature -->
                                <!-- Product name -->
                                <div class="product-grid-item__name">
                                    <a href="{{ route('product-detail',['slug' => $new_arrival_products->slug, 'sku' => $new_arrival_products->sku]) }}">{{ $new_arrival_products->getTranslation('name', $lang) }}</a>
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
                                    <span class="product-grid-item__price-new">{{ env('DEFAULT_CURRENCY').' '.$priceData['discounted_price'] }}</span>
                                    <!-- End price new -->
                                    <!-- Price old -->
                                    @if ($priceData['discounted_price'] != $priceData['original_price'])
                                        <span class="product-grid-item__price-old">{{ env('DEFAULT_CURRENCY').' '.$priceData['original_price'] }}</span>
                                    @endif
                                    <!-- End price old -->
                                </div>
                                <!-- End product price -->
                            </div>
                        </div>
                        <!-- End Product -->
                    @endforeach
                @endif
            
            </div>
            <!-- End products -->
        </div>
        <!-- End container -->
    </div>
    <!-- End our products -->
    <!-- New in -->
    {{-- <div class="shoppable-new-coll">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Title and action -->
            <div class="shoppable-new-coll__title-and-action">
                <!-- Title -->
                <h4 class="shoppable-new-coll__title font-family-jost">{{ $page->getTranslation('heading3', $lang) }}</h4>
                <!-- End title -->
            </div>
            <!-- End title and action -->
            <!-- Row -->
            <div class="row">
               
                @if (!empty($data['home_occasions']))
                    @foreach ($data['home_occasions'] as $home_occasions)
                      
                        <div class="col-12 col-md-6 col-lg-2">
                            <!-- Category -->
                            <a href="{{ route('products.index',['occasion' => [$home_occasions->getTranslation('slug', $lang)]]) }}" class="shoppable-new-coll__category">
                                <img alt="Image" data-sizes="auto"
                                    data-srcset="{{ uploaded_asset($home_occasions->getTranslation('logo', $lang)) }}"
                                    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                    class="lazyload" />
                                <!-- <p>Rings</p> -->
                            </a>
                            <!-- End category -->
                        </div>
                    @endforeach
                @endif
                
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </div> --}}
    <!-- End new in -->
    <!-- Deal week -->
   
    @if(!empty($data['banners']['home_center_banner']) && isset($data['banners']['home_center_banner'][0]))
        <div class="explore-banner mt-3">
            @php
                $linktype = $data['banners']['home_center_banner'][0]['type'];
                $link = '#';
                if($linktype == 'external'){
                    $link = $data['banners']['home_center_banner'][0]['link'];
                }
                if($linktype == 'product'){
                    $prod_sku = getProductSkuFromSlug($data['banners']['home_center_banner'][0]['link']);
                    if($prod_sku != null){
                        $link = route('product-detail',['slug' => $data['banners']['home_center_banner'][0]['link'], 'sku' => $prod_sku]);
                    }else {
                        $link = '#';
                    }
                }
                if($linktype == 'category'){
                    $link = route('products.index',['category' => $data['banners']['home_center_banner'][0]['link']]);
                }
            @endphp

            <!-- Container -->
            <div class="container-fluid p-0">
                <a href="{{$link}}"><img src="{{$data['banners']['home_center_banner'][0]['image']}}" alt=""></a>
            </div>
            <!-- End container -->
        </div>
    @endif
     {{-- @php 
        echo '<pre>';
        print_r($data['banners']);
        die;

    @endphp --}}
    <!-- End deal week -->
    <!-- Our products -->
    <div class="our-products minimal-our-products">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Title -->
            <div class="our-products__title-and-action d-flex justify-content-center">
                <h4 class="our-products__title">{{ $page->getTranslation('heading4', $lang) }}</h4>
            </div>
            <!-- End title -->
            <!-- Products -->
            <div class="row">
                @if (!empty($data['special_products']))
                    @foreach ($data['special_products'] as $special_products)
                        @php
                            $priceData = getProductOfferPrice($special_products);
                        @endphp
                        <!-- Product -->
                        <div class="col-6 col-md-4 col-xl-3">
                            <div class="product-grid-item product-grid-item--type-2 product-grid-item--type-5">
                                <!-- Product tag -->
                                @if ($priceData['offer_tag'] != '')
                                    <div class="product-grid-item__tag">{{ $priceData['offer_tag'] }}</div>
                                @endif
                                @php
                                    $wishlistedSpecial = productWishlisted($special_products->sku, $special_products->slug);
                                @endphp

                                <div class="product-grid-item__wishlist-right-0  wishlist-btn" data-product-slug="{{ $special_products->slug }}" data-product-sku="{{ $special_products->sku }}"><i class="lnr lnr-heart {{($wishlistedSpecial != 0) ? 'active' : '' }}"></i></div>
                                <!-- End product tag -->
                                <!-- Product images -->
                                <div class="product-grid-item__images product-grid-item__images--ratio-100-122 js-product-grid-images"
                                    data-current-image="0">
                                    <!-- Product images arrows -->
                                    <div class="product-grid-item__images-arrows">
                                        <!-- Previous -->
                                        <div class="product-grid-item__previous-image js-product-grid-previous-image"><i
                                                class="lnr lnr-chevron-left"></i></div>
                                        <!-- End previous -->
                                        <!-- Previous -->
                                        <div class="product-grid-item__next-image js-product-grid-next-image"><i
                                                class="lnr lnr-chevron-right"></i></div>
                                        <!-- End previous -->
                                    </div>
                                    <!-- End product images arrows -->

                                    @php
                                        $images = explode(',',$special_products->photos);
                                        if($special_products->thumbnail_img != null){
                                            array_unshift($images, $special_products->thumbnail_img);
                                        }
                                    @endphp

                                    @if (!empty($images))
                                        @foreach ($images as $imgkey => $img)
                                            <!-- Product image -->
                                            <div class="product-grid-item__image js-product-grid-image @if($imgkey == 0) active @endif">
                                                <a href="{{ route('product-detail',['slug' => $special_products->slug, 'sku' => $special_products->sku]) }}">
                                                    <img alt="Image" data-sizes="auto"
                                                        data-srcset="{{get_product_image($img,'300')}}"
                                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                        class="lazyload" />
                                                </a>
                                            </div>
                                            <!-- End product image -->
                                        @endforeach
                                    @endif
                                    

                                    <!-- Quick shop -->
                                    <div class="product-grid-item__quick-shop">
                                        <a href="#" class="add-to-cart-btn" data-product-slug="{{$special_products->slug}}" data-product-sku="{{ $special_products->sku}}">{{ trans('messages.add_to_cart') }}</a>
                                    </div>
                                    <!-- End quick shop -->
                                </div>
                                <!-- End product images -->
                                <!-- Product feature -->
                                <div class="product-grid-item__feature">{{ $special_products->brand->getTranslation('name', $lang) }}</div>
                                <!-- End product feature -->
                                <!-- Product name -->
                                <div class="product-grid-item__name">
                                    <a href="{{ route('product-detail',['slug' => $special_products->slug, 'sku' => $special_products->sku]) }}">{{ $special_products->getTranslation('name', $lang) }}</a>
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
                                    <span class="product-grid-item__price-new">{{ env('DEFAULT_CURRENCY').' '.$priceData['discounted_price'] }}</span>
                                    <!-- End price new -->
                                    <!-- Price old -->
                                    @if ($priceData['discounted_price'] != $priceData['original_price'])
                                        <span class="product-grid-item__price-old">{{ env('DEFAULT_CURRENCY').' '.$priceData['original_price'] }}</span>
                                    @endif
                                    <!-- End price new -->
                                    
                                </div>
                                <!-- End product price -->
                            </div>
                        </div>
                        <!-- End Product -->
                    @endforeach
                @endif
               
               
            </div>
            <!-- End products -->
        </div>
        <!-- End container -->
    </div>
    <!-- End our products -->
    <!-- Our journal -->
    <div class="full-width-our-journal">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Row -->
            <div class="row">
                @if(!empty($data['banners']['home_mid_section_banner']))
                    @foreach ($data['banners']['home_mid_section_banner'] as $midKey => $home_mid_section_banner)
                        <!-- Post -->
                        @php
                            $linktype = $home_mid_section_banner['type'];
                            $link = '#';
                            if($linktype == 'external'){
                                $link = $home_mid_section_banner['link'];
                            }
                            if($linktype == 'product'){
                                $prod_sku = getProductSkuFromSlug($home_mid_section_banner['link']);
                                if($prod_sku != null){
                                    $link = route('product-detail',['slug' => $home_mid_section_banner['link'], 'sku' => $prod_sku]);
                                }else {
                                    $link = '#';
                                }
                            }
                            if($linktype == 'category'){
                                $link = route('products.index',['category' => $home_mid_section_banner['link']]);
                            }
                        @endphp

                        <div class="col-lg-6 col-xl-4">
                            <div class="full-width-post">
                                <div class="full-width-post__image">
                                    <a href="{{$link}}">
                                        <img alt="Image" data-sizes="auto"
                                        data-srcset="{{$home_mid_section_banner['image']}}"
                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                        class="lazyload" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End post -->
                    @endforeach
                @endif
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </div>
    <!-- End our journal -->
    <!-- Dark brands -->
    <div class="dark-brands">
        <h4 class="dark-intro-text__title d-flex justify-content-center">{{ $page->getTranslation('heading5', $lang) }}</h4>
        <!-- Container -->
        <div class="container-fluid">
            <!-- Home brands items -->
            <div class="home-brands__items">
                @if (!empty($data['shop_by_brands']))
                    @foreach ($data['shop_by_brands'] as $shop_by_brands)
                        <!-- Item -->
                        <div class="home-brand-item">
                            <a href="{{ route('products.index',['brand' => [$shop_by_brands->getTranslation('slug', $lang)]]) }}">
                                <img alt="Image" src="{{ uploaded_asset($shop_by_brands->getTranslation('logo',$lang)) }}" class="lazyload" />
                            </a>
                            
                        </div>
                        <!-- End item -->
                    @endforeach
                @endif
               
            </div>
            <!-- End home brands items -->
        </div>
        <!-- End container -->
    </div>
    <!-- End dark brands -->
    <!-- video -->
    <div class="shoppable-video">
        <!-- Image -->
        <div class="shoppable-video__background-image"
            style="background-image: url(assets/images/grey-background.jpg); background-size: cover;"></div>
        <!-- End image -->
        <!-- Content -->
        <div class="shoppable-video__content">
            <!-- Container -->
            <div class="container container--type-4">
                <!-- Title -->
                <h5 class="shoppable-video__title font-family-jost">{{ $page->getTranslation('heading6', $lang) }}</h5>
                <!-- End title -->
                <!-- Action -->
                <div class="shoppable-video__action">
                    <div class="row justify-content-center align-items-center">
                        @if ($page->image != null)
                            @php
                                $pageImgaes = explode(',', $page->image);
                            @endphp
                            @foreach ($pageImgaes as $pImg)
                                <div class="col-md-2 col-6">
                                    <img width="100" src="{{ asset($pImg) }}" alt="">
                                </div>
                            @endforeach
                            
                        @endif
                    </div>
                </div>
                <!-- End action -->
            </div>
            <!-- End container -->
        </div>
        <!-- End content -->
    </div>
    <!-- End video -->
    <!-- Shop by the look -->
    <div class="minimal-shop-by-the-look">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Container -->
            <div class="minimal-shop-by-the-look__container">
                <!-- Title and action -->
                <div class="minimal-shop-by-the-look__title-and-action d-flex justify-content-center">
                    <!-- Title -->
                    <h4 class="minimal-shop-by-the-look__title">{{ $page->getTranslation('heading7', $lang) }}</h4>
                    <!-- End title -->
                </div>
                <!-- End title and action -->
                <!-- Items -->
                <div class="minimal-shop-by-the-look__items js-home-minimal-instagram">

                    @if (!empty($data['partners']))
                        @foreach ($data['partners'] as $partners)
                            <!-- Item -->
                            <div class="minimal-instagram-item">
                                <a href="{{ $partners->link }}" target="_blank">
                                    <img alt="Image" data-sizes="auto"
                                        data-srcset="{{ uploaded_asset($partners->image) }}"
                                        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                        class="lazyload" />
                                </a>
                            </div>
                            <!-- End item -->
                        @endforeach
                    @endif

                </div>
                <!-- End items -->
            </div>
            <!-- End container -->
            <!-- Line 1px -->
            <hr />
            <!-- End line 1px -->
        </div>
        <!-- End container -->
    </div>
    <!-- End shop by the look -->
@endsection

@section('header')
<style>
    .video-container {
        position: relative;
        border-radius: 20px;
        width: 100%;
        margin: 0 auto;
        overflow: hidden;
    }

    .video-container h1 {
        color: white;
        text-transform: uppercase;
        font-size: 56px !important;
        font-weight: 100 !important;
    }

    @media screen and (max-width: 1400px) {
        .video-container h1 {
            font-size: 40px !important;
        }
    }

    @media screen and (max-width: 991px) {
        .video-container h1 {
            font-size: 36px !important;
        }
    }

    @media screen and (max-width: 600px) {
        .video-container h1 {
            font-size: 30px !important;
        }
    }

    .video-container .video {
        width: 100%;
        height: 660px;
        height: auto;
        display: block;
        object-fit: cover;
    }

    .video-container .caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #fff !important;
        font-family: 'Arial', sans-serif;
    }

    .video-container .caption h1 {
        margin-bottom: 0.5rem;
    }

    .video-container .caption p {
        font-size: 1rem;
        line-height: 1.5;
    }

    @media screen and (max-width:991px) {
        .video-container {
            min-height: 450px;

        }

        .video-container .video {
            width: 100%;
            height: 450px;
            display: block;
        }
    }
</style>
@endsection