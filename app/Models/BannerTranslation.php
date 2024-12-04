<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerTranslation extends Model
{

  
  protected $fillable = [
    'banner_id','lang', 'image', 'mobile_image', 'title', 'sub_title', 'btn_text'
  ];

  public function banner()
  {
    return $this->belongsTo(Banner::class);
  }

  // public function image()
  //   {
  //       return $this->hasOne(Upload::class, 'id', 'image');
  //   }

  public function mainImage()
  {
      return $this->belongsTo(Upload::class, 'image', 'id');
  }
  
  public function mobileImage()
  {
      return $this->belongsTo(Upload::class, 'mobile_image', 'id');
  }
  
}
