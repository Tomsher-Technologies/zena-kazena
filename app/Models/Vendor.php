<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Vendor extends Authenticatable
{
    use HasFactory;
    protected $table = 'vendors';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'business_name',
        'business_type',
        'registration_number',
        'trade_license',
        'address',
        'logo',
        'is_active',
        'status',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'status' => 'string',
    ];
    protected $hidden = [
        'password','remember_token',
    ];
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
