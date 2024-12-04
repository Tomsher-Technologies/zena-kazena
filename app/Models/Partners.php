<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Partners extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'sort_order',
        'status',
        'link'
    ];

    public function getImage()
    {
        return $this->image ? URL::to($this->image) : asset('assets/img/placeholder.png');
    }
}
