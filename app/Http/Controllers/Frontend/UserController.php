<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function checkLoginStatus()
    {
        if (Auth::check()) {
            return response()->json(['is_logged_in' => true]);
        } else {
            return response()->json(['is_logged_in' => false]);
        }
    }
}