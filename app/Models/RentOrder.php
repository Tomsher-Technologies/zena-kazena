<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentOrder extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel's naming convention)
    protected $table = 'rent_orders';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'user_id',
        'product_id',
        'product_stock_id',
        'variation',
        'sku',
        'quantity',
        'shipping_address',
        'billing_address',
        'delivery_status',
        'payment_type',
        'payment_status',
        'deposit',
        'grand_total',
        'tax',
        'sub_total',
        'start_date',
        'end_date',
        'no_of_days',
        'order_code',
        'order_date',
        'order_notes',
        'tracking_code',
        'cancel_request',
        'cancel_request_date',
        'cancel_reason',
        'cancel_approval',
        'cancel_approval_date',
        'shipping_type',
        'shipping_cost',
        'offer_discount',
        'coupon_discount',
        'delivery_viewed',
    ];

    // Cast attributes to specific types
    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'order_date' => 'datetime',
        'delivery_viewed' => 'boolean',
    ];

    /**
     * Relationships (if needed)
     */

    // User who placed the order
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Product associated with the order
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Product stock associated with the order
    public function productStock()
    {
        return $this->belongsTo(ProductStock::class);
    }
}

