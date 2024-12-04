<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTabs extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'lang',
        'heading',
        'content',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
