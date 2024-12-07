<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'description', 'id_image', 'product_image', 'metal', 'metal_type', 'weight', 'eid_no', 'id_image_back'
    ];

}
