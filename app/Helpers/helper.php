<?php

use App\Models\BusinessSetting;
use App\Utility\CategoryUtility;
use App\Models\ProductAttributes;
use App\Models\Product;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Occasion;



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