<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                <img class="mw-100" height="100" src="{{ asset('assets/img/logow.png') }}" 
                        alt="{{ get_setting('site_name') }}">
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm text-white" type="text"
                    name="" placeholder="{{  trans('messages.search_in_menu') }}" id="menu-search"
                    onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="aiz-side-nav-link">
                            <i class="las la-home aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{  trans('messages.dashboard') }}</span>
                        </a>
                    </li>
                    <!-- @if (Auth::user()->user_type == 'admin') -->
                <!-- @endif -->
               
                <!-- Product -->
                @canany(['manage_products', 'manage_categories', 'manage_brands', 'manage_occasions', 'manage_attributes', 'product_reviews'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{  trans('messages.products') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @canany(['manage_products'])
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link" href="{{ route('products.create') }}">
                                        <span class="aiz-side-nav-text">{{ trans('messages.add_new_product')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('products.all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.all','products.admin.edit','product.bid-history']) }}">
                                        <span class="aiz-side-nav-text">{{  trans('messages.all_Products') }}</span>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['manage_categories'])
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('categories.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit']) }}">
                                        <span class="aiz-side-nav-text">{{  trans('messages.category') }}</span>
                                    </a>
                                </li>
                            @endcanany    

                            @canany(['manage_brands'])
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('brands.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit']) }}">
                                        <span class="aiz-side-nav-text">{{  trans('messages.brand') }}</span>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['manage_occasions'])
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('occasions.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['occasions.index', 'occasions.create', 'occasions.edit']) }}">
                                    <span class="aiz-side-nav-text">{{  trans('messages.occasion') }}</span>
                                </a>
                            </li>
                        @endcanany

                            @canany(['manage_attributes'])
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('attributes.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['attributes.index', 'attributes.create', 'attributes.edit','edit-attribute-value', 'attributes.show']) }}">
                                        <span class="aiz-side-nav-text">{{  trans('messages.attribute') }}</span>
                                    </a>
                                </li>
                            @endcanany
                           
                            @canany(['product_reviews'])
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('reviews.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['reviews.index']) }}">
                                        <span class="aiz-side-nav-text">{{  trans('messages.product_reviews') }}</span>
                                    </a>
                                </li>
                            @endcanany
                          
                        </ul>
                    </li>
                @endcanany

                @canany(['manage_orders', 'manage_cancel_requests'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-money-bill aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">Sales</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('all_orders.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show']) }}">
                                <span class="aiz-side-nav-text">All Orders</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('cancel_requests.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['cancel_requests.index', 'cancel_orders.show']) }}">
                                <span class="aiz-side-nav-text">Cancel Requests</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{ route('return_requests.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['return_requests.index', 'return_orders.show']) }}">
                                <span class="aiz-side-nav-text">Return Requests</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-money-bill aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">Rent</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('rent.all_orders.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['rent.all_orders.index', 'rentall_orders.show']) }}">
                                <span class="aiz-side-nav-text">All Rent Orders</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('rent.cancel_requests.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['rent.cancel_requests.index', 'rent.cancel_orders.show']) }}">
                                <span class="aiz-side-nav-text">Cancel Rent Requests</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany


                @canany(['upload_files'])
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('uploaded-files.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create']) }}">
                            <i class="las la-folder-open aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ trans('messages.uploaded_files') }}</span>
                        </a>
                    </li>
                @endcanany

                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-user-friends aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">Customers</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('customers.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">Customer list</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <i class="las la-user-friends aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">Vendors</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('vendors.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">Vendor list</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{ route('enquiries.contact') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['enquiries.contact']) }}">
                        <i class="las la-mail-bulk aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">Contact Enquiries</span>
                    </a>
                </li>

                @canany(['manage_marketing'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-bullhorn aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ trans('messages.marketing') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('coupon.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index', 'coupon.create', 'coupon.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ trans('messages.coupon') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @canany(['website_setup'])
                <li class="aiz-side-nav-item">
                    <a href="#"
                        class="aiz-side-nav-link {{ areActiveRoutes(['website.footer', 'website.header', 'banners.*']) }}">
                        <i class="las la-desktop aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">Website Setup</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('website.header') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">Header</span>
                            </a>
                        </li>
                        {{-- <li class="aiz-side-nav-item">
                            <a href="{{ route('website.menu') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">Menus</span>
                            </a>
                        </li> --}}
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('website.footer', ['lang' => App::getLocale()]) }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['website.footer']) }}">
                                <span class="aiz-side-nav-text">Footer</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('website.pages') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['website.pages', 'custom-pages.create', 'custom-pages.edit']) }}">
                                <span class="aiz-side-nav-text">Pages</span>
                            </a>
                        </li>
                        {{-- <li class="aiz-side-nav-item">
                            <a href="{{ route('website.appearance') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">Appearance</span>
                            </a>
                        </li> --}}
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('home-slider.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['home-slider.index', 'home-slider.create', 'home-slider.edit']) }}">
                                <span class="aiz-side-nav-text">Home Page Sliders</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('banners.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['banners.index', 'banners.create', 'banners.edit']) }}">
                                <span class="aiz-side-nav-text">Banners</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{ route('partners.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['partners.index', 'partners.create', 'partners.edit']) }}">
                                <span class="aiz-side-nav-text">Partners</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                @endcanany
                <!-- Staffs -->
                @canany(['manage_staffs'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-tie aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{  trans('messages.staffs') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('staffs.create') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['staffs.create']) }}">
                                    <span class="aiz-side-nav-text">{{ trans('messages.add_new_staffs') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('staffs.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.edit']) }}">
                                    <span class="aiz-side-nav-text">{{  trans('messages.all_staffs') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                @canany(['manage_roles'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-tie aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ trans('messages.roles_permissions')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('roles.create') }}" class="aiz-side-nav-link {{ areActiveRoutes(['roles.create']) }}">
                                    <span class="aiz-side-nav-text">{{ trans('messages.add_new_role')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('roles.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['roles.index','roles.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ trans('messages.all_roles')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany
              
               
            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
