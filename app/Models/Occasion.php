<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Occasion extends Model
{

  protected $with = ['occasion_translations'];

  protected $fillable = ['name', 'logo', 'is_active'];

  public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $occasion_translation = $this->occasion_translations->where('lang', $lang)->first();
      return $occasion_translation != null ? $occasion_translation->$field : $this->$field;
  }

  public function occasion_translations(){
      return $this->hasMany(OccasionTranslation::class);
  }

}
