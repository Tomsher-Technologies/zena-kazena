<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValueTranslation extends Model
{
 
    protected $fillable = ['value', 'lang', 'attribute_value_id'];
    public $timestamps = false;

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
