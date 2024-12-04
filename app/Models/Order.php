<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['code', 'user_id', 'estimated_delivery', 'shipping_address', 'billing_address', 'order_notes', 'delivery_status', 'payment_type', 'payment_status', 'payment_details', 'payment_tracking_id', 'shipping_type', 'shipping_cost', 'tax', 'grand_total', 'sub_total', 'coupon_discount', 'coupon_code', 'offer_discount', 'tracking_code', 'delivery_completed_date', 'date', 'cancel_request', 'cancel_request_date', 'cancel_approval', 'cancel_approval_date', 'cancel_reason'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function refund_requests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->hasOne(Shop::class, 'user_id', 'seller_id');
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function proxy_cart_reference_id()
    {
        return $this->hasMany(ProxyPayment::class)->select('reference_id');
    }

    public function order_transfer()
    {
        return $this->hasMany(OrderTransfers::class);
    }
}
