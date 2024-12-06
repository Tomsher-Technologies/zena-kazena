<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AizUploadController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\Bannercontroller;
use App\Http\Controllers\Admin\OccasionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\HomeSliderController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Frontend\VendorController;

Route::group(['middleware' => ['guest']], function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login']);
    
});

Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');
    Route::get('/cache-cache', [AdminController::class, 'clearCache'])->name('cache.clear');

    Route::resource('roles', RoleController::class);
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');

    Route::post('/banners/get_form', [Bannercontroller::class, 'get_form'])->name('banners.get_form');
    Route::get('/banners/destroy/{banner}', [Bannercontroller::class, 'destroy'])->name('banners.destroy');
    Route::resource('banners', Bannercontroller::class)->except(['show', 'destroy']);
    Route::get('/banners/edit/{id}', [Bannercontroller::class, 'edit'])->name('banners.edit');

    Route::get('/enquiries-contact', [PageController::class, 'enquiries'])->name('enquiries.contact');
    // website setting
    Route::group(['prefix' => 'website'], function () {
        Route::get('/footer', [WebsiteController::class, 'footer'])->name('website.footer');

        Route::get('/menu', [WebsiteController::class, 'menu'])->name('website.menu');
        Route::post('/menu', [WebsiteController::class, 'menuUpdate']);

        Route::get('/header', [WebsiteController::class, 'header'])->name('website.header');
        Route::get('/appearance', [WebsiteController::class, 'appearance'])->name('website.appearance');
        
        Route::post('/home-slider/update-status', [HomeSliderController::class, 'updateStatus'])->name('home-slider.update-status');
        Route::get('/home-slider/delete/{id}', [HomeSliderController::class, 'destroy'])->name('home-slider.delete');
        Route::resource('home-slider', HomeSliderController::class);

        Route::resource('custom-pages', PageController::class);
        Route::get('/pages', [PageController::class, 'index'])->name('website.pages');
        Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');
        Route::get('/custom-pages/destroy/{id}', [PageController::class, 'destroy'])->name('custom-pages.destroy');
        Route::post('/page/delete_image', [PageController::class, 'delete_image'])->name('page.delete_image');

        // Partners
        Route::resource('partners', PartnersController::class)->except('show');
    });

    // Brands
    Route::resource('brands', BrandController::class);
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('/brands/status', [BrandController::class, 'updateStatus'])->name('brands.status');

    //Occasions
    Route::resource('occasions', OccasionController::class);
    Route::get('/occasions/edit/{id}', [OccasionController::class, 'edit'])->name('occasions.edit');
    Route::post('/occasions/status', [OccasionController::class, 'updateStatus'])->name('occasions.status');

    //Coupons
    Route::resource('coupon', CouponController::class);
    Route::get('/coupon/destroy/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy');
    Route::post('/coupon/get_form', [CouponController::class, 'get_coupon_form'])->name('coupon.get_coupon_form');
    Route::post('/coupon/get_form_edit', [CouponController::class, 'get_coupon_form_edit'])->name('coupon.get_coupon_form_edit');

    // uploaded files
    Route::any('/uploaded-files/file-info', [AizUploadController::class, 'file_info'])->name('uploaded-files.info');
    Route::resource('/uploaded-files', AizUploadController::class);
    Route::get('/uploaded-files/destroy/{id}', [AizUploadController::class, 'destroy'])->name('uploaded-files.destroy');
    Route::post('/aiz-uploader', [AizUploadController::class, 'show_uploader']);
    Route::post('/aiz-uploader/upload', [AizUploadController::class, 'upload']);
    Route::get('/aiz-uploader/get_uploaded_files', [AizUploadController::class, 'get_uploaded_files']);
    Route::post('/aiz-uploader/get_file_by_ids', [AizUploadController::class, 'get_preview_files']);
    Route::get('/aiz-uploader/download/{id}', [AizUploadController::class, 'attachment_download'])->name('download_attachment');

    // Categories
    Route::get('/generate-slug', [CategoryController::class, 'generateSlug'])->name('generate-slug');
    Route::resource('categories', CategoryController::class)->except(['destroy']);
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('/categories/status', [CategoryController::class, 'updateStatus'])->name('categories.status');

    // Attributes
    Route::resource('attributes', AttributeController::class);
    Route::get('/attributes/edit/{id}', [AttributeController::class, 'edit'])->name('attributes.edit');
    Route::get('/attributes/destroy/{id}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
    Route::post('/attributes/status', [AttributeController::class, 'updateAttributeStatus'])->name('attributes.status');
    Route::post('/attribute_value/status', [AttributeController::class, 'updateAttributeValueStatus'])->name('attribute_value.status');

    //Attribute Value
    Route::post('/store-attribute-value', [AttributeController::class, 'store_attribute_value'])->name('store-attribute-value');
    Route::get('/edit-attribute-value/{id}', [AttributeController::class, 'edit_attribute_value'])->name('edit-attribute-value');
    Route::post('/update-attribute-value/{id}', [AttributeController::class, 'update_attribute_value'])->name('update-attribute-value');
    Route::get('/destroy-attribute-value/{id}', [AttributeController::class, 'destroy_attribute_value'])->name('destroy-attribute-value');

    Route::get('/products/all', [ProductController::class, 'all_products'])->name('products.all');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store/', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/add-attributes', [ProductController::class, 'get_attribute_values'])->name('products.add-attributes');
    Route::get('/products/admin/{id}/edit', [ProductController::class, 'admin_product_edit'])->name('products.edit');
    Route::post('/products/todays_deal', [ProductController::class, 'updateTodaysDeal'])->name('products.todays_deal');
    Route::post('/products/published', [ProductController::class, 'updatePublished'])->name('products.published');
    Route::post('/products/approved', [ProductController::class, 'updateProductApproval'])->name('products.approved');
    Route::post('/products/featured', [ProductController::class, 'updateFeatured'])->name('products.featured');
    Route::post('/bulk-product-delete', [ProductController::class, 'bulk_product_delete'])->name('bulk-product-delete');
    Route::post('/products/delete-variant-image', [ProductController::class, 'delete_variant_image'])->name('products.delete_varient_image');
    Route::post('/products/delete-thumbnail', [ProductController::class, 'delete_thumbnail'])->name('products.delete_thumbnail');
    Route::post('/products/delete_gallery', [ProductController::class, 'delete_gallery'])->name('products.delete_gallery');

    //Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/published', [ReviewController::class, 'updatePublished'])->name('reviews.published');

    Route::get('/all_orders', [OrderController::class, 'all_orders'])->name('all_orders.index');
    Route::get('/all_orders/{id}/show', [OrderController::class, 'all_orders_show'])->name('all_orders.show');
    Route::get('/cancel_requests', [OrderController::class, 'allCancelRequests'])->name('cancel_requests.index');
    Route::post('/cancel-request-status', [OrderController::class, 'cancelRequestStatus'])->name('cancel-request-status');
    Route::get('/cancel_orders/{id}/show', [OrderController::class, 'cancel_orders_show'])->name('cancel_orders.show');
    Route::post('/orders/update_delivery_status', [OrderController::class, 'update_delivery_status'])->name('orders.update_delivery_status');
    Route::post('/orders/update_payment_status', [OrderController::class, 'update_payment_status'])->name('orders.update_payment_status'); 
    Route::post('/orders/update_tracking_code', [OrderController::class, 'update_tracking_code'])->name('orders.update_tracking_code');

    Route::get('/rent/all_orders', [OrderController::class, 'rentAll_orders'])->name('rent.all_orders.index');
    Route::get('/rent/all_orders/{id}/show', [OrderController::class, 'rentAll_orders_show'])->name('rent.all_orders.show');
    Route::get('/rent/cancel_requests', [OrderController::class, 'rentAllCancelRequests'])->name('rent.cancel_requests.index');
    Route::post('/rent/cancel-request-status', [OrderController::class, 'rentCancelRequestStatus'])->name('rent.cancel-request-status');
    Route::get('/rent/cancel_orders/{id}/show', [OrderController::class, 'rentCancel_orders_show'])->name('rent.cancel_orders.show');
    Route::post('/rent/orders/update_delivery_status', [OrderController::class, 'rentUpdate_delivery_status'])->name('rent.orders.update_delivery_status');
    Route::post('/rent/orders/update_payment_status', [OrderController::class, 'rentUpdate_payment_status'])->name('rent.orders.update_payment_status');
    Route::post('/rent/orders/update_tracking_code', [OrderController::class, 'rentUpdate_tracking_code'])->name('rent.orders.update_tracking_code');


    Route::get('/return_requests', [OrderController::class, 'allReturnRequests'])->name('return_requests.index');
    Route::post('/return-request-status', [OrderController::class, 'returnRequestStatus'])->name('return-request-status');
    Route::get('/return_orders/{id}/show', [OrderController::class, 'return_orders_show'])->name('return_orders.show');

    Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');
    Route::get('rent/invoice/{order_id}', [InvoiceController::class, 'rentInvoice_download'])->name('rent.invoice.download');

    Route::resource('customers', CustomerController::class);
    Route::get('customers_ban/{customer}', [CustomerController::class, 'ban'])->name('customers.ban');
    Route::get('/customers/login/{id}', [CustomerController::class, 'login'])->name('customers.login');
    Route::get('/customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/bulk-customer-delete', [CustomerController::class, 'bulk_customer_delete'])->name('bulk-customer-delete');

    // Route::resource('custom-pages', PageController::class);
    // Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');

    // Route::get('/pages', [WebsiteController::class, 'index'])->name('website.pages');

    Route::post('/business-settings/update', [BusinessSettingsController::class, 'update'])->name('business_settings.update');
    Route::resource('vendors', VendorController::class);
    Route::get('vendors_ban/{vendor}', [VendorController::class, 'ban'])->name('vendors.ban');
    Route::get('/vendors/login/{id}', [VendorController::class, 'login'])->name('vendors.login');
    Route::get('/vendors/destroy/{id}', [VendorController::class, 'destroy'])->name('vendors.destroy');
    Route::get('/vendors/edit/{id}', [VendorController::class, 'edit'])->name('vendors.edit');
    Route::post('/vendors/update/{id}', [VendorController::class, 'update'])->name('vendors.update');
    Route::post('/bulk-vendor-delete', [VendorController::class, 'bulk_vendor_delete'])->name('bulk-vendor-delete');
    Route::get('/download/{id}', [VendorController::class, 'downloadTradeLicense'])->name('vendor.download');
});