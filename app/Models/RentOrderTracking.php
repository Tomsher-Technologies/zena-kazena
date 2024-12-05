<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentOrderTracking extends Model
{
    use HasFactory;
    public $table = 'rent_order_tracking';

    protected $fillable = [
        'order_id', 'status', 'description', 'status_date'
    ];

    public function rentOrder()
    {
        return $this->belongsTo(RentOrder::class,'order_id','id');
    }
}
