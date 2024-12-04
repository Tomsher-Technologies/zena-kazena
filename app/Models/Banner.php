<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\Upload;
use Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;

class Banner extends Model
{
    use HasFactory;

    protected $with = ['banner_translations'];

    protected $fillable = [
        'name',
        'image',
        'mobile_image',
        'title',
        'sub_title',
        'btn_text',
        'link_type',
        'link_ref',
        'link_ref_id',
        'link',
        'status',
    ];

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;

        $banner_translation = $this->banner_translations
            ->where('lang', $lang)
            ->first();
           
        // if ($field === 'image') {
        //     // Return the file_name if the image relationship is loaded
        //     return $banner_translation && $banner_translation->relationLoaded('mainImage') && $banner_translation->image
        //         ? $banner_translation->mainImage->file_name
        //         : null; // Return null or a default image path if needed
        // }

        // if ($field === 'mobile_image') {
        //     return $banner_translation && $banner_translation->mobileImage
        //         ? $banner_translation->mobileImage->file_name
        //         : null;
        // }

        return $banner_translation != null ? $banner_translation->$field : $this->$field;
    }
  
    public function banner_translations(){
        return $this->hasMany(BannerTranslation::class)->with('mainImage', 'mobileImage');
    }


    public function getALink()
    {
        if ($this->link_ref == 'product' && $this->link_ref_id !== null) {
            $product = Product::where('id', $this->link_ref_id)->select('slug')->first()->slug;
            return route('product', [
                'slug' => $product
            ]);
        } elseif ($this->link_ref == 'category' && $this->link_ref_id !== null) {
            $product = Category::where('id', $this->link_ref_id)->select('slug')->first()->slug;
            return route('products.category', [
                'category_slug' => $product
            ]);
        } elseif ($this->link_ref == 'external' && $this->link !== null) {
            return $this->link;
        } else {
            return '#';
        }
    }

    public function getBannerLink()
    {
        if ($this->link_ref == 'product' && $this->link_ref_id !== null) {
            $product = Product::where('id', $this->link_ref_id)->select('slug')->first();
            if($product){
                return $product->slug;
            }else{
                return '#';
            }
        } elseif ($this->link_ref == 'category' && $this->link_ref_id !== null) {
            $product = Category::where('id', $this->link_ref_id)->first();
            if($product){
                return $product->getTranslation('slug',getActiveLanguage());
            }else{
                return '#';
            }
        } elseif ($this->link_ref == 'external' && $this->link !== null) {
            return $this->link;
        } else {
            return '#';
        }
    }

    public function getBannerSKU()
    {
        if ($this->link_ref == 'product' && $this->link_ref_id !== null) {
            $product = Product::where('id', $this->link_ref_id)->select('sku')->first();
            if($product){
                return $product->sku;
            }else{
                return '';
            }
        } 
        return '';
    }

    public static function boot()
    {
        static::creating(function ($model) {
            Cache::forget('smallBanners');
            Cache::forget('ads_banners');
        });

        static::updating(function ($model) {
            Cache::forget('smallBanners');
            Cache::forget('ads_banners');
        });

        static::deleting(function ($model) {
            Cache::forget('smallBanners');
            Cache::forget('ads_banners');
        });

        parent::boot();
    }
}
