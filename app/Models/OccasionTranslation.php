<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OccasionTranslation extends Model
{
  protected $fillable = [
    'occasion_id', 'lang', 'name', 'slug', 'meta_title', 'meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'meta_keywords'
  ];

  public function occasion()
  {
    return $this->belongsTo(Occasion::class);
  }
}
