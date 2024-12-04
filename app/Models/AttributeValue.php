<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{

    protected $fillable = [
        'attribute_id', 'is_active'
    ];

    public function attribute() {
        return $this->belongsTo(Attribute::class);
    }

    public function attribute_value_translations()
    {
        return $this->hasMany(AttributeValueTranslation::class);
        
    }

    public function attr_value_translations()
    {
        return $this->hasOne(AttributeValueTranslation::class, 'attribute_value_id')
        ->where('lang', app()->getLocale());
    }

    public function getTranslatedName($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->attribute_value_translations()->where('lang', $lang)->first()->value ?? $this->attribute_value_translations()->first()->value;
    }
}
