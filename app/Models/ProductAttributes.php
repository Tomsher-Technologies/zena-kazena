<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'product_varient_id', 
        'attribute_id', 
        'attribute_value_id'
      ];
    
    public function attributes()
    {
        return $this->belongsTo(Attribute::class,'attribute_id','id');
    }

    public function attribute_value()
    {
        return $this->belongsTo(AttributeValue::class,'attribute_value_id','id');
    }

    public function stocks()
    {
        return $this->belongsTo(ProductStock::class,'product_varient_id','id');
    }
  
}
