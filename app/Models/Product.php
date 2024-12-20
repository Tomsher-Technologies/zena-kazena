<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;
use URL;

class Product extends Model
{

    protected $fillable = [
        'name',
        'sku',
        'added_by',
        'user_id',
        'category_id',
        'brand_id',
        'video_provider',
        'video_link',
        'description',
        'unit_price',
        'purchase_price',
        'unit',
        'slug',
        'approved',
        'colors',
        'choice_options',
        'variations',
        'photos',
        'thumbnail_img',
        'type',
        'deposit',
    ];

    protected $with = ['product_translations','seo'];

    public function getTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $product_translations = $this->product_translations->where('lang', $lang)->first();
        return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function getSeoTranslation($field = '', $lang = false)
    {
        $lang = $lang == false ? App::getLocale() : $lang;
        $seo_translations = $this->seo->where('lang', $lang)->first();
        return $seo_translations != null ? $seo_translations->$field : $this->$field;
    }

    public function product_translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function latestBid()
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }

    public function seo()
    {
        return $this->hasMany(ProductSeo::class);
    }

    public function image($path)
    {
        return URL::to($path);
    }

    public function tabs()
    {
        return $this->hasMany(ProductTabs::class);
    }

    public function tabsLang($lang= null)
    {
        // Use the default app locale if no language is provided
        $lang = $lang ?? app()->getLocale();

        return $this->hasMany(ProductTabs::class)->where('lang', $lang);
    }

    public function recentlyViewedProducts()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }
    // public function getSeoTranslation($lang = "")
    // {
    //     $lang = $lang == "" ? App::getLocale() : $lang;
    //     return $this->seo->where('lang', $lang)->first();
    // }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function occasion()
    {
        return $this->belongsTo(Occasion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function winner_auction()
    {
        return $this->belongsTo(User::class,'auction_winner','id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function getMinPriceAttribute()
    {
        return $this->stocks->min('price');
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function scopePhysical($query)
    {
        return $query->where('digital', 0);
    }
}
