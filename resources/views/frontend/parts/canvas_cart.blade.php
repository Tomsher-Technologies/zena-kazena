<div class="canvas-cart js-canvas-cart">
    <div class="canvas-cart__overlay js-close-canvas-cart"></div>
    <!-- Content -->
    <div class="canvas-cart__content">
        <!-- D-flex -->
        <div class="canvas-cart__d-flex">
            <!-- Top and products -->
            <div class="canvas-cart__top-and-products">
                <!-- Heading -->
                <div class="canvas-cart__heading d-flex align-items-center">
                    <!-- H3 -->
                    <h3 class="canvas-cart__h3">{{ trans('messages.cart') }} ({{ cartCount() }})</h3>
                    <!-- End h3 -->
                    <!-- Close -->
                    <div class="canvas-cart__close"><a href="#" class="js-close-canvas-cart"><i
                                class="lnr lnr-cross"></i></a>
                    </div>
                    <!-- End close -->
                </div>
                <!-- End heading -->
                <!-- Cart items -->
                <ul class="header-cart__items">

                    <!-- Item -->
                   
                    <!-- End item -->
                   
                </ul>
                <!-- End cart items -->
            </div>
            <!-- End top and products -->
            <!-- Bottom -->
            <div class="canvas-cart__bottom">
                <!-- Subtotal -->
                <div class="header-cart__subtotal d-flex">
                    <!-- Title -->
                    <div class="subtotal__title">{{trans('messages.subtotal')}}</div>
                    <!-- End title -->
                    <!-- Value -->
                    <div class="subtotal__value">{{ env('DEFAULT_CURRENCY') }} <span class="cart_sub_total">0</span></div>
                    <!-- End value -->
                </div>
                <!-- End subtotal -->
                <!-- Header cart action -->
                <div class="header-cart__action">
                    <a href="#" class="header-cart__button proceedToCheckout" id="proceedToCheckout">{{trans('messages.checkout')}}</a>
                    <a href="{{ route('cart.items') }}" class="header-cart__button">{{ trans('messages.view_cart') }}</a>
                </div>
                <!-- End Header cart action -->
            </div>
            <!-- End bottom -->
        </div>
        <!-- End d-flex -->
    </div>
    <!-- End content -->
</div>
