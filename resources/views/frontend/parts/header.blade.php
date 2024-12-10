<header class="header header--type-6">
    <!-- Promo bar -->
    @php
        $lang = getActiveLanguage();
    @endphp
    <div class="header__promo-bar">
        <div class="container container--type-2">
            <div class="d-flex justify-content-between">
                <div class="d-none 	d-md-none d-lg-block d-xl-block">
                    <a href="#"><i class="lnr lnr-tag"></i> {{ get_setting('helpline_title') }}</a>
                </div>
                <div class="header__contact d-flex align-items-center">
                    <div class="header-contact__icon">
                        <svg viewBox="0 0 60 60">
                            <g id="Layer_1"></g>
                            <g id="Layer_2">
                                <path
                                    d="M62.1,45.5l-9.6-6.1c-1.8-1.2-4.2-0.7-5.5,1l-2.7,3.7c-0.3,0.4-1,0.5-1.5,0.2c-12.9-7.7-21-18.5-23.9-22.8
                                c-0.2-0.2-0.2-0.5-0.1-0.6c0-0.2,0.2-0.4,0.4-0.5l3.9-2.6c0.9-0.6,1.5-1.5,1.7-2.5c0.2-1,0-2.1-0.6-2.9l-6.4-9.2
                                C16.5,1.5,14.1,1,12.3,2L3.4,7C3,7.2,2.7,7.5,2.5,7.7C-4,14.6,2.7,31.2,17.8,45.6C29,56.2,41.5,62.5,50.1,62.5c3,0,5.4-0.7,7.3-2.3
                                c0.3-0.2,0.6-0.6,0.8-0.9c0,0,0,0,0,0l5.2-8.4C64.5,49,64,46.7,62.1,45.5z M60.9,49.2l-5.2,8.4c-0.1,0.1-0.1,0.2-0.2,0.2
                                c-5.8,4.9-22.1-1.7-35.5-14.5C6.5,30.6-0.5,15.3,4.6,9.8c0,0,0.1-0.1,0.2-0.2l8.9-5c0.2-0.1,0.4-0.1,0.5-0.1c0.4,0,0.7,0.2,0.9,0.5
                                l6.4,9.2c0.2,0.2,0.2,0.5,0.1,0.6c0,0.2-0.2,0.4-0.4,0.5l-3.9,2.6c-0.9,0.6-1.5,1.5-1.7,2.5c-0.2,1,0,2,0.6,2.9
                                c3,4.4,11.4,15.6,24.9,23.7c1.8,1.1,4.2,0.6,5.4-1l2.7-3.7c0.3-0.4,1-0.5,1.5-0.2l9.6,6.1C61,48.3,61.1,48.8,60.9,49.2z">
                                </path>
                            </g>
                        </svg>
                    </div>
                    <div class="header-contact__content">
                        <p>{{ get_setting('helpline_number') }}</p>
                    </div>
                </div>
                <div class="">
                    <div class="header__switcher">
                        <p>@if (getActiveLanguage() == 'en') English @else Arabic @endif <span></span></p>
                        <select id="lang-change">
                            <option value="en" @if (getActiveLanguage() == 'en') selected @endif>English</option>
                            <option value="ae" @if (getActiveLanguage() == 'ae') selected @endif>Arabic</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $details = getCategoryHeader();
    @endphp
    <!-- End promo bar -->
    <!-- Container -->
    <div class="container container--type-2">
        <!-- Header container -->
        <div class="header__container d-flex align-items-center">
            <!-- Mobile menu -->
            <div class="header__mobile-menu">
                <!-- Open mobile menu -->
                <div class="mobile-menu__open">
                    <a href="#" class="js-open-mobile-menu"><i class="lnr lnr-menu"></i></a>
                </div>
                <!-- End open mobile menu -->
                <!-- Mobile menu -->
                <div class="mobile-menu js-mobile-menu">
                    <!-- Overlay -->
                    <div class="mobile-menu__overlay js-close-mobile-menu"></div>
                    <!-- End overlay -->
                    <!-- Content -->
                    <div class="mobile-menu__content">
                        <!-- Close mobile menu -->
                        <div class="mobile-menu__close">
                            <a href="#" class="js-close-mobile-menu"><i class="lnil lnil-close"></i></a>
                        </div>
                        <!-- End close mobile menu -->
                        <!-- Mobile logo -->
                        <h3 class="mobile-menu__logo"><img width="200" src="assets/images/logow.png" alt="">
                        </h3>
                        <!-- End mobile logo -->
                        <!-- Mobile Nav -->
                        <ul class="mobile-menu__nav">
                            <li class="mobile-menu__dropdown">
                                <a href="{{ route('home') }}">{{ get_setting('header_category_title_1') }}</a>
                                <ul class="mobile-menu__dropdown-menu js-mobile-menu-dropdown-menu">
                                     @if(!empty($details['header_categories']))
                                @foreach($details['header_categories'] as $header_categories)
                                    <li><a href="{{ route('products.index',['category' => $header_categories->getTranslation('slug', $lang)]) }}">{{ $header_categories->getTranslation('name', $lang) }}</a></li>
                                @endforeach
                            @endif  
                                    
                                </ul>
                                <div class="mobile-menu__dropdown-btn js-mobile-menu-dropdown-btn"><span
                                        class="lnil lnil-chevron-down"></span></div>
                            </li>
                            <li class="mobile-menu__dropdown">
                                <a href="{{ route('home') }}">{{ get_setting('header_category_title_2') }}</a>
                                <ul class="mobile-menu__dropdown-menu js-mobile-menu-dropdown-menu">
                                     @if (!empty($details['header_brands']))
                                @foreach ($details['header_brands'] as $header_brands)
                                    <li><a href="{{ route('products.index',['brand' => [$header_brands->getTranslation('slug', $lang)]]) }}"> {{$header_brands->getTranslation('name', $lang)}}</a></li>
                                @endforeach
                            @endif
                                </ul>
                                <div class="mobile-menu__dropdown-btn js-mobile-menu-dropdown-btn"><span
                                        class="lnil lnil-chevron-down"></span></div>
                            </li>
                            <li class="mobile-menu__dropdown">
                                <a href="{{ route('home') }}">{{ get_setting('header_category_title_3') }}</a>
                                <ul class="mobile-menu__dropdown-menu js-mobile-menu-dropdown-menu">
                                    @if (!empty($details['header_occasions']))
                                @foreach ($details['header_occasions'] as $header_occasions)
                                    <li><a href="{{ route('products.index',['occasion' => [$header_occasions->getTranslation('slug', $lang)]]) }}"> {{$header_occasions->getTranslation('name', $lang)}}</a></li>
                                @endforeach
                            @endif 
                                </ul>
                                <div class="mobile-menu__dropdown-btn js-mobile-menu-dropdown-btn"><span
                                        class="lnil lnil-chevron-down"></span></div>
                            </li>
                        </ul>
                        <!-- End Mobile nav -->
                    </div>
                    <!-- End content -->
                </div>
                <!-- End mobile menu -->
            </div>
            <!-- End mobile menu -->
            <ul class="header_page_nav">
                <li><a href="{{ route('home') }}" class="nav__item active">Sale</a></li>
                 <li><a href="{{route('rent.products')}}" class="nav__item">Rent</a></li>
                <li><a href="#" class="nav__item">Auction</a></li>
                <li><a href="https://tomsher.co/MHS/ZK/HTML/mortgage.html" target="blank" class="nav__item">mortgage</a></li> 
            </ul>
            <!-- Logo -->
            <h1 class="header__logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo-1.png') }}" alt="">
                </a>
            </h1>
            <!-- End logo -->
            <!-- Header right -->
            <ul class="header__right">
                <li><a href="#" class="js-open-popup-search"><i class="lnr lnr-magnifier"></i></a></li>
                <li class="header__cart d-none d-lg-block"><a href="#"><i
                            class="lnr lnr-heart"></i><span class="wishlist_count">{{ wishlistCount() }}</span></a></li>
                <li class="header__cart"><a href="#" class="js-open-canvas-cart"><i
                            class="lnr lnr-cart"></i><span class="cart_count">{{ cartCount() }}</span></a></li>
               @if (!auth()->guard('vendor')->check())
                    <li class="header__canvas-menu">
                        <a href="#" class="js-open-canvas-menu">
                            <i class="lnr lnr-user"></i>
                        </a>
                    </li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="lnr lnr-user"></i>
                        </a>
                        <ul class="dropdown-menu">
                            {{-- <li>
                                <a class="dropdown-item" href="{{ route('vendor.product.index') }}">
                                    {{ trans('messages.my_products') }}
                                </a>
                            </li> --}}
                            <li>
                                <a class="dropdown-item" href="{{ route('vendor.myaccount') }}">
                                    {{ trans('messages.my_account') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('vendor.myprofile') }}">
                                    {{ trans('messages.my_profile') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('vendor.products.all') }}">
                                    {{ trans('messages.my_products') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('vendor.products.create') }}">
                                    {{ trans('messages.add_product') }}
                                </a>
                            </li>
                            <li>
                                <form id="logout-form" action="{{ route('vendor.logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ trans('messages.logout') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
            <!-- End header right -->
        </div>
        <!-- End header container -->
        <!-- Navigation -->
        <ul class="header__nav">
            <li>
                <a href="#" class="nav__item">{{ trans('messages.categories') }}</a>
                <!-- MegaMenu -->
                <div class="nav__mega-menu">
                    <!-- Column -->
                    <div class="mega-menu__standard-column">
                        <!-- Column title -->
                        <div class="standard-column__title">{{ get_setting('header_category_title_1') }}</div>
                        <!-- End column title -->
                        <!-- Column nav -->
                        <ul class="standard-column__nav">
                            @if(!empty($details['header_categories']))
                                @foreach($details['header_categories'] as $header_categories)
                                    <li><a href="{{ route('products.index',['category' => $header_categories->getTranslation('slug', $lang)]) }}">{{ $header_categories->getTranslation('name', $lang) }}</a></li>
                                @endforeach
                            @endif  
                        </ul>
                        <!-- End column nav -->
                    </div>
                    <!-- End column -->
                    <!-- Column -->
                    <div class="mega-menu__standard-column">
                        <!-- Column title -->
                        <div class="standard-column__title">{{ get_setting('header_category_title_2') }} </div>
                        <!-- End column title -->
                        <!-- Column nav -->
                        <ul class="standard-column__nav">
                            @if (!empty($details['header_brands']))
                                @foreach ($details['header_brands'] as $header_brands)
                                    <li><a href="{{ route('products.index',['brand' => [$header_brands->getTranslation('slug', $lang)]]) }}"> {{$header_brands->getTranslation('name', $lang)}}</a></li>
                                @endforeach
                            @endif 
                            
                        </ul>
                        <!-- End column nav -->
                    </div>
                    <!-- End column -->
                    
                    <!-- Column -->
                    <div class="mega-menu__standard-column">
                        <!-- Column title -->
                        <div class="standard-column__title">{{ get_setting('header_category_title_3') }}</div>
                        <!-- End column title -->
                        <!-- Column nav -->
                        <ul class="standard-column__nav">
                            @if (!empty($details['header_occasions']))
                                @foreach ($details['header_occasions'] as $header_occasions)
                                    <li><a href="{{ route('products.index',['occasion' => [$header_occasions->getTranslation('slug', $lang)]]) }}"> {{$header_occasions->getTranslation('name', $lang)}}</a></li>
                                @endforeach
                            @endif 
                        </ul>
                        <!-- End column nav -->
                    </div>
                    <!-- End column -->
                    <!-- Banner -->
                    <div class="mega-menu__banner">
                        <img alt="Image" data-sizes="auto"
                            data-srcset="{{ uploaded_asset(get_setting('header_category_logo')) }}"
                            src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                            class="lazyload" />
                    </div>
                    <!-- End banner -->
                </div>
                <!-- End MegaMenu -->
            </li>
            <li><a href="{{ route('products.index') }}" class="nav__item">{{ trans('messages.collections') }}</a></li>
            <li><a href="{{ route('products.index',['offers']) }}" class="nav__item">{{ trans('messages.offers') }}</a></li>
        </ul>
        <!-- End navigation -->
    </div>
    <!-- End container -->
    <ul class="header_page_nav page-im-mobile">
        <li><a href="{{ route('home') }}" class="nav__item active">Sale</a></li>
         <li><a href="#" class="nav__item">Rent</a></li>
        <li><a href="#" class="nav__item">Auction</a></li>
        <li><a href="https://tomsher.co/MHS/ZK/HTML/mortgage.html" target="blank" class="nav__item">mortgage</a></li>
    </ul>
</header>
