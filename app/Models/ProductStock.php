<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use URL;

class ProductStock extends Model
{
    protected $fillable = ['product_id', 'variant', 'status', 'sku', 'price', 'high_bid_amount', 'qty', 'offer_price', 'offer_tag', 'image'];
    //
    public function product(){
    	return $this->belongsTo(Product::class);
    }

    public function wholesalePrices() {
        return $this->hasMany(WholesalePrice::class);
    }

    public function image($path)
    {
        return URL::to($path);
    }
    public function attributes()
    {
        return $this->hasMany(ProductAttributes::class,'product_varient_id','id');
    }

}
