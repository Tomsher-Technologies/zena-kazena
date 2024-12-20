<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_stock_id', 'variation', 'og_price', 'offer_price', 'price', 'tax', 'shipping_cost', 'quantity', 'payment_status', 'delivery_status', 'return_expiry_date'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_stock()
    {
        return $this->belongsTo(ProductStock::class);
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function refund_request()
    {
        return $this->hasOne(RefundRequest::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function order_transfer()
    {
        return $this->hasOne(OrderTransfers::class);
    }
}
