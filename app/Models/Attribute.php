<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Attribute extends Model
{
    protected $with = ['attribute_translations'];

    protected $fillable = [
        'name','is_active'
    ];


    public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $attribute_translation = $this->attribute_translations->where('lang', $lang)->first();
      return $attribute_translation != null ? $attribute_translation->$field : $this->$field;
    }

    public function attribute_translations(){
      return $this->hasMany(AttributeTranslation::class);
    }

    public function attribute_values() {
        return $this->hasMany(AttributeValue::class);
    }
    
    public function getTranslatedName($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->attribute_values()->where('lang', $lang)->first()->name ?? $this->attribute_values()->first()->name;
    }

}
