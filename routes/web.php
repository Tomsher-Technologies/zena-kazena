<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ForgotPasswordController;
use App\Http\Controllers\Frontend\VendorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about_us');
Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/privacy', [FrontendController::class, 'privacy'])->name('privacy');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact-us', [FrontendController::class, 'submitContactForm'])->name('contact.submit');
Route::get('/mortgage', [FrontendController::class, 'mortgage'])->name('mortgage');
Route::post('/submit-mortgage-form', [FrontendController::class, 'submitMortgageForm'])->name('submit-mortgage');

Route::get('/sales', [FrontendController::class, 'sales'])->name('sales');
Route::post('/submit-sales-form', [FrontendController::class, 'submitSalesForm'])->name('submit-sales');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product-detail', [ProductController::class, 'productDetails'])->name('product-detail');
Route::post('/recently-viewed', [CommonController::class, 'addRecentlyViewed']);
Route::get('/recently-viewed', [CommonController::class, 'getRecentlyViewed']);
Route::get('related-products', [ProductController::class, 'relatedProducts'])->name('related.products');

Route::get('/rent/products', [ProductController::class, 'rentProducts'])->name('rent.products');
Route::get('/rent/product-details', [ProductController::class, 'rentProductDetails'])->name('rent.product-detail');

Route::post('/language_change', [FrontendController::class, 'changeLanguage'])->name('language.change');

Route::get('/category/{category_slug}', [SearchController::class, 'listingByCategory'])->name('products.category');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('cart/count', [CartController::class, 'getCount']);
Route::post('cart/change_quantity', [CartController::class, 'changeQuantity']);
Route::delete('/cart/{id}', [CartController::class, 'removeCartItem'])->name('cart.remove');
Route::apiResource('cart', CartController::class)->only('index', 'store', 'destroy');

Route::get('cart/items', [CartController::class, 'getCartDetails'])->name('cart.items');
Route::post('coupon-apply', [CheckoutController::class, 'apply_coupon_code'])->name('coupon-apply');
Route::post('coupon-remove', [CheckoutController::class, 'remove_coupon_code'])->name('coupon-remove');

Route::get('/check-login-status', [UserController::class, 'checkLoginStatus'])->name('check.login.status');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->middleware('auth')->name('checkout');
    Route::post('checkout.process', [CheckoutController::class, 'placeOrder'])->name('checkout.process');

    Route::get('/order/success/{order_id}', [CheckoutController::class, 'success'])->name('order.success');
    Route::get('/order/failed', [CheckoutController::class, 'failed'])->name('order.failed');
    Route::get('/rent/order/success/{order_id}', [CheckoutController::class, 'successRentOrder'])->name('rent.order.success');
    Route::get('/rent/order/failed', [CheckoutController::class, 'failedRentOrder'])->name('rent.order.failed');
    
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('wishlists', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/store', [WishlistController::class, 'store'])->name('wishlist/store');
    Route::get('wishlists/count', [WishlistController::class, 'getCount']);
    Route::post('/wishlist/delete', [WishlistController::class, 'delete'])->name('wishlist.delete');
    Route::post('wishlist/remove', [WishlistController::class, 'removeWishlistItem']);

    Route::get('orders', [ProfileController::class, 'orderList'])->name('orders.index');
    Route::get('order-details', [ProfileController::class, 'orderDetails'])->name('order-details');
    Route::get('rent/order-details', [ProfileController::class, 'rentOrderDetails'])->name('rent.order-details');
    Route::get('order/returns', [ProfileController::class, 'orderReturnList'])->name('orders.returns');
    
    Route::post('cancel-order', [CheckoutController::class, 'cancelOrderRequest'])->name('cancel-order');
    Route::post('return-order', [CheckoutController::class, 'returnOrderRequest'])->name('return-order');

    Route::get('account', [ProfileController::class, 'getUserAccountInfo'])->name('account');
    Route::post('/account/update', [ProfileController::class, 'update'])->name('account.update'); 
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('account.changePassword');
    
    Route::post('/rent/product-order', [CheckoutController::class, 'rentProductOrder'])->name('rent.product-order');
    Route::post('/rent/product-order/process', [CheckoutController::class, 'placeRentOrder'])->name('rent.checkout.process');
});

Route::post('/subscribe', [FrontendController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.sendResetLink');
Route::get('/password/reset/{email}/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

Route::group(['middleware' => ['vendor.guest']], function () {
    Route::controller(VendorController::class)->group(function () {
        Route::get('/vendor-registration', 'vendorRegister')->name('vendor.register');
        Route::post('/save-vendor', 'saveRegistration')->name('vendor.save');
        Route::get('/vendor-login', 'vendorLogin')->name('vendor.login');
        Route::post('/vendor-do-login', 'vendorDoLogin')->name('vendor.dologin');
    });
});
Route::group(['middleware' => ['vendor.auth']], function () {
    Route::controller(VendorController::class)->group(function () {
        Route::get('/vendor/myaccount', 'vendorAccount')->name('vendor.myaccount');
        Route::get('/vendor/profile', 'vendorProfile')->name('vendor.myprofile');
        Route::post('/vendor/logout', 'vendorLogout')->name('vendor.logout');
        Route::get('vendor/product/add', 'addProduct')->name('vendor.product.add');
        Route::post('vendor/product/save', 'storeProduct')->name('vendor.product.store');
        Route::get('vendor/product/edit/{id}', 'editProduct')->name('vendor.product.edit');
        Route::post('vendor/product/update/{id}', 'updateProduct')->name('vendor.product.update');
        Route::get('vendor/product/destroy/{id}', 'destroyProduct')->name('vendor.product.destroy');
        Route::get('vendor/products', 'products')->name('vendor.product.index');
        Route::get('vendor/product/view/{id}', 'viewProduct')->name('vendor.product.view');
        Route::post('vendor/update', 'vendorUpdate')->name('vendor.update.info');
    });

});






