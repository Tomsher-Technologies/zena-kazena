<?php

use App\Models\BusinessSetting;
use App\Utility\CategoryUtility;
use App\Models\ProductAttributes;
use App\Models\Product;
use App\Models\AttributeValue;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Occasion;
use App\Models\Page;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\RecentlyViewedProduct;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
// use DB;

function trackRecentlyViewed($productId)
{
    // Check if the user is authenticated
    if (auth()->check()) {
        $userId = auth()->id();

        // Save for authenticated user
        RecentlyViewedProduct::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $productId],
            ['updated_at' => now()]
        );

        // Limit to last 10 products
        // RecentlyViewedProduct::where('user_id', $userId)
        //         ->orderBy('updated_at', 'desc')
        //         ->skip(10)
        //         ->get()
        //         ->each(function ($row) {
        //             $row->delete();
        //         });
    } else {
        // Get or create a guest token
        $guestToken = Cookie::get('guest_token', Str::uuid());
        Cookie::queue('guest_token', $guestToken, 60 * 24 * 7); // 7 days
        
        // Save for guest user
        RecentlyViewedProduct::updateOrCreate(
            ['guest_token' => $guestToken, 'product_id' => $productId],
            ['updated_at' => now()]
        );
    
        // Limit to last 10 products
        // RecentlyViewedProduct::where('guest_token', $guestToken)
        //         ->orderBy('updated_at', 'desc')
        //         ->skip(10)
        //         ->take(PHP_INT_MAX)
        //         ->get()
        //         ->each(function ($row) {
        //             $row->delete();
        //         });
    }
}

function getRecentlyViewedProducts()
{
    if (auth()->check()) {
        $recentlyViewedIds = RecentlyViewedProduct::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->pluck('product_id')
            ->toArray();
    } else {
        $guestToken = Cookie::get('guest_token', null);
        if ($guestToken) {
            $recentlyViewedIds = RecentlyViewedProduct::where('guest_token', $guestToken)
                ->orderBy('updated_at', 'desc')
                ->pluck('product_id')
                ->toArray();
        } else {
            $recentlyViewedIds = [];
        }
    }

    return Product::whereIn('id', $recentlyViewedIds)->get();
}

//$this->mergeGuestToUser(auth()->id(), Cookie::get('guest_token')); 

function mergeGuestToUser($userId, $guestToken)
{
    if (!$guestToken) {
        return;
    }

    $guestProducts = RecentlyViewedProduct::where('guest_token', $guestToken)->get();

    foreach ($guestProducts as $guestProduct) {
        RecentlyViewedProduct::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $guestProduct->product_id],
            ['updated_at' => $guestProduct->updated_at]
        );
    }

    // Delete guest data
    RecentlyViewedProduct::where('guest_token', $guestToken)->delete();

    // Remove guest token cookie
    Cookie::queue(Cookie::forget('guest_token'));
}

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = '//' . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}


if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } else {
            return app('url')->asset('storage') . '/';
            // return getBaseURL();
        }
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            return $products->where('approved', '1')->where('published', '1')->where('auction_product', 0)->orderBy('created_at', 'desc')->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('published', '1')->where('auction_product', 0)->where('added_by', 'admin');
        }
    }
}

function getProductAttributes($attributes){
    $attributeArr = [];
    $lang = getActiveLanguage();
    if($attributes){
        foreach($attributes as $attr){
            $attributeArr[] = [
                                "name" => $attr->attributes?->getTranslation('name', $lang),
                                "value" => $attr->attribute_value?->getTranslatedName('value'),
                            ];
        }
    }
    return  $attributeArr;
}

if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return Cache::rememberForever('verified_sellers_id', function () {
            // return App\Models\Seller::where('verification_status', 1)->pluck('user_id')->toArray();
        });
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null, $lang = false)
    {
        $settings = Cache::remember('business_settings', 86400, function () {
            return BusinessSetting::all();
        });

        if ($lang == false) {
            $setting = $settings->where('type', $key)->first();
        } else {
            $setting = $settings->where('type', $key)->where('lang', $lang)->first();
            $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
        }
        return $setting == null ? $default : $setting->value;
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if ($id && ($asset = \App\Models\Upload::find($id)) != null) {
            return $asset->external_link == null ? storage_asset($asset->file_name) : $asset->external_link;
        }

        return app('url')->asset('assets/img/placeholder.jpg');
    }
}

if (!function_exists('home_base_price')) {
    function home_base_price($product, $formatted = true)
    {
        $price = $product->unit_price;
        $tax = 0;

        if($product->taxes){
            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $tax += $product_tax->tax;
                }
            }
        }
        
        $price += $tax;
        return $formatted ? format_price(convert_price($price)) : $price;
    }
}

//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        if (get_setting('decimal_separator') == 1) {
            $fomated_price = number_format($price, get_setting('no_of_decimals'));
        } else {
            $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', ' ');
        }

        if (get_setting('symbol_format') == 1) {
            return currency_symbol() . $fomated_price;
        } else if (get_setting('symbol_format') == 3) {
            return currency_symbol() . ' ' . $fomated_price;
        } else if (get_setting('symbol_format') == 4) {
            return $fomated_price . ' ' . currency_symbol();
        }
        return $fomated_price . currency_symbol();
    }
}

//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        if (Session::has('currency_code') && (Session::get('currency_code') != get_system_default_currency()->code)) {
            $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
            $price = floatval($price) * floatval(Session::get('currency_exchange_rate'));
        }
        return $price;
    }
}

//gets currency symbol
if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        // if (Session::has('currency_symbol')) {
        //     return Session::get('currency_symbol');
        // }
        // return get_system_default_currency()->symbol;
    }
}

//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

if (!function_exists('storage_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function storage_asset($path, $secure = null)
    {
        return app('url')->asset('storage/' . $path, $secure);
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

function getAllCategories()
{
    return Cache::rememberForever('categoriesTree', function () {
        return CategoryUtility::getSidebarCategoryTree();
    });
}

function cleanSKU($sku)
{
    $sku = str_replace(' ', '', $sku);
    $sku = preg_replace('/[^a-zA-Z0-9_-]/', '', $sku);
    return $sku;
}

if (!function_exists('get_product_image')) {
    function get_product_image($path, $size = 'full')
    {
        if ($path) {
            if ($size == 'full') {
                return app('url')->asset($path);
            } else {
                $fileName = pathinfo($path)['filename'];
                $ext   = pathinfo($path)['extension'];
                $dirname   = pathinfo($path)['dirname'];
                $r_path = "{$dirname}/" . $fileName . "_{$size}px" . ".{$ext}";
                return app('url')->asset($r_path);
            }
        }

        return app('url')->asset('admin_assets/assets/img/placeholder.jpg');
    }
}

function get_product_attrValue($attrValue, $productStockId){
    $query = ProductAttributes::where('product_varient_id', $productStockId)
                                ->where('attribute_id', $attrValue)
                                ->first();
    $value = '';
    if($query){
        $value = $query->attribute_value_id;
    }
    return $value;
}


function get_attribute_values($attribute_id, $proAttr){
    $all_attribute_values = AttributeValue::with('attribute')->where('is_active',1)->where('attribute_id', $attribute_id)->get();

    $html = '';

    foreach ($all_attribute_values as $row) {
        $selected = ($proAttr == $row->id) ? 'selected' : '';
        $html .= '<option value="' . $row->id . '" '.$selected.'>' . $row->getTranslatedName('value') . '</option>';
    }

    return $html;
}

function getSidebarCategoryTree()
{
    $all_cats = Category::select([
        'id',
        'parent_id',
        'name',
        'level',
        'slug',
        'icon'
    ])->with(['child','iconImage'])->withCount('products')->where('parent_id', 0)->where('is_active', 1)->orderBy('categories.name','ASC')->get();
    foreach( $all_cats as $categ){
        $categ->icon = ($categ->iconImage?->file_name) ? storage_asset($categ->iconImage->file_name) : app('url')->asset('admin_assets/assets/img/placeholder.jpg');
        unset($categ->iconImage);
    }

    return $all_cats;
}

function getChildCategoryIds($parentId)
    {
        // Get the parent category
        $parentCategory = Category::find($parentId);

        // If the parent category doesn't exist, return an empty array or handle as needed
        if (!$parentCategory) {
            return [];
        }

        // Recursively get all child category IDs
        $childIds = getChildCategoryIdsRecursive($parentCategory);

        return $childIds;
    }

    function getChildCategoryIdsRecursive($category)
    {
        $childIds = [];

        if($category->child){
            foreach ($category->child as $child) {
                $childIds[] = $child->id;
    
                // Recursively get child category IDs for the current child
                $childIds = array_merge($childIds, getChildCategoryIdsRecursive($child));
            }
        }
        

        return $childIds;
    }

    //formats price to home default price with convertion
if (!function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}


function uploadImage($type, $imageUrl, $filename = null){
    $data_url = '';

    // try {
    $ext = $imageUrl->getClientOriginalExtension();
    
    if($type == 'page'){
        $path = 'pages/';
    }else if($type == 'mortgage'){
        $path = 'mortgages/';
    }else{
        $path = 'others/';
    }
    
    $filename = $path . $filename . '.' . $ext;

    $imageContents = file_get_contents($imageUrl);

    // Save the original image in the storage folder
    Storage::disk('public')->put($filename, $imageContents);
    $data_url = Storage::url($filename);
    
    return $data_url;
}

function getDirection()
{
    if (getActiveLanguage() == 'ar') {
        return 'rtl';
    }
    return 'ltr';
}

function getActiveLanguage()
{
    if (Session::exists('locale')) {
        return Session::get('locale');
    }
    return 'en';
}

function getProductOfferPrice($product){

    $data["original_price"] = $product->min_price ;
    $discountPrice = $product->min_price ;
    
    $offertag =  '';
    $tax = 0;
    
    $discount_applicable = false;
    if($product->discount_start_date != NULL && $product->discount_end_date != NULL){
        if(strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }
    }
   
    if ($discount_applicable) {
        if ($product->discount_type == 'percent') {
            $discountPrice -= ($discountPrice * $product->discount) / 100;
            $offertag = $product->discount.'% OFF';
        } elseif ($product->discount_type == 'amount') {
            $discountPrice -= $product->discount;
            $offertag = 'AED '.$product->discount.' OFF';
        }
    }
   
    $data["discounted_price"] = $discountPrice;
    $data["offer_tag"]  = $offertag;

    return $data;
}

function getProductPrice($productStock){

    $data["original_price"] = $productStock->price ;
    $discountPrice = $productStock->price ;
    
    $offertag =  '';
    $tax = 0;
    
    $discount_applicable = false;
    if($productStock->product->discount_start_date != NULL && $productStock->product->discount_end_date != NULL){
        if(strtotime(date('d-m-Y H:i:s')) >= $productStock->product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $productStock->product->discount_end_date) {
            $discount_applicable = true;
        }
    }
   
    if ($discount_applicable) {
        if ($productStock->product->discount_type == 'percent') {
            $discountPrice -= ($discountPrice * $productStock->product->discount) / 100;
            $offertag = $productStock->product->discount.'% OFF';
        } elseif ($productStock->product->discount_type == 'amount') {
            $discountPrice -= $productStock->product->discount;
            $offertag = 'AED '.$productStock->product->discount.' OFF';
        }
    }
   
    $data["discounted_price"] = $discountPrice;
    $data["offer_tag"]  = $offertag;

    return $data;
}

function reduceProductQuantity($productQuantities){
    if(!empty($productQuantities)){
        foreach($productQuantities as $key => $value){
            $product_stock = ProductStock::where('product_id', $key)->first();
            $product_stock->qty -= $value;
            $product_stock->save();
        }
    }
}

function getPageData($type){
    $page = Page::where('type',$type)->first();
    return $page;
}

function getUser()
{
    $user = array(
        'users_id_type' => 'temp_user_id',
        'users_id' => null
    );

    if (auth()->user()) {
        $user = array(
            'users_id_type' => 'user_id',
            'users_id' => auth()->user()->id
        );
    } else {
        $user = array(
            'users_id_type' => 'temp_user_id',
            'users_id' => Request::cookie('guest_token')
        );
    }

    return $user;
}

function cartCount()
{
    $user = getUser();
    $count = 0;
    if($user['users_id'] != ''){
        $count = Cart::where([
            $user['users_id_type'] => $user['users_id']
        ])->count();
    }

    return $count;
}

function wishlistCount()
{
    if (auth()->user()) {
        return Wishlist::where('user_id', auth()->user()->id)->count();
    }else{
        // return Wishlist::where('temp_user_id', Request::cookie('guest_token'))->count();
        return 0;
    }
}

function isWishlisted($productId, $productStockId)
{
    return Wishlist::where('user_id', Auth::id())
                   ->where('product_id', $productId)
                   ->exists();
}

function productWishlisted($sku, $product_slug){
    $variantProduct = ProductStock::leftJoin('products as p','p.id','=','product_stocks.product_id')
                                    ->where('product_stocks.sku', $sku)
                                    ->where('p.slug', $product_slug)
                                    ->select('product_stocks.*')->first()?->toArray();
    if(!empty($variantProduct)){
        $product_id         = $variantProduct['product_id'] ?? null;
        $product_stock_id   = $variantProduct['id'] ?? null;
        if($product_id != null &&  $product_stock_id != null){
            // Check if product already exist in wishlist
            $checkWhishlist =   Wishlist::where('user_id', Auth::id())
                                            ->where('product_id',$product_id)
                                            ->where('product_stock_id',$product_stock_id)->count();  
            return $checkWhishlist; 
        }
        return 0;
    } else{
        return 0;
    }                            
}

function getProductSkuFromSlug($slug){
    $product = Product::where('slug', $slug)->first();
    return $product->sku ?? null;
}

function getCategoryHeader(){
    $data['header_categories'] = Cache::rememberForever('header_categories', function () {
        $categories = get_setting('header_categories');
        if ($categories) {
            $details = Category::whereIn('id', json_decode($categories))->where('is_active', 1)
                ->get();
            return $details;
        }
    });

    $data['header_occasions'] = Cache::rememberForever('header_occasions', function () {
        $occasions = get_setting('header_occasions');
        if ($occasions) {
            $details = Occasion::whereIn('id', json_decode($occasions))->where('is_active', 1)
                ->get();
            return $details;
        }
    });

    $data['header_brands'] = Cache::rememberForever('header_brands', function () {
        $header_brands = get_setting('header_brands');
        if ($header_brands) {
            $details = Brand::whereIn('id', json_decode($header_brands))->where('is_active', 1)
                ->get();
            return $details;
        }
    });

    return $data;
}
