<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Auth;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->put('coupon_last_url', url()->full());

        $sort_search = null;
        $coupon_type = $use_type = null;
        $coupons = Coupon::orderBy('id', 'desc');
        
        if ($request->has('search')) {
            $sort_search = $request->search;
            $coupons = $coupons->where('code', 'like', '%' . $sort_search . '%');
        }

        if ($request->has('coupon_type') && $request->coupon_type != NULL) {
            $coupon_type = $request->coupon_type;
            $coupons = $coupons->where('type', 'like', '%' . $coupon_type . '%');
        }

        if ($request->has('use_type') && $request->use_type != NULL) {
            $use_type = $request->use_type;
            $coupons = $coupons->where('one_time_use', $use_type);
        }

        $coupons = $coupons->paginate(15);
        return view('backend.marketing.coupons.index', compact('coupons','sort_search','coupon_type','use_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.marketing.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (count(Coupon::where('code', $request->coupon_code)->get()) > 0) {
            flash(trans('messages.coupon_already_exist'))->error();
            return back();
        }

        $coupon = new Coupon;
        // $coupon->user_id = User::where('user_type', 'admin')->first()->id;
        $coupon = $this->setCouponData($request, $coupon);
        $coupon->save();

        flash(trans('messages.coupon').' '.trans('messages.created_msg'))->success();
        return redirect()->route('coupon.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));
        return view('backend.marketing.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (count(Coupon::where('id', '!=', $id)->where('code', $request->coupon_code)->get()) > 0) {
            flash(trans('messages.coupon_already_exist'))->error();
            return back();
        }

        $coupon = Coupon::findOrFail($id);
        $this->setCouponData($request, $coupon);
        $coupon->save();

        flash(trans('messages.coupon').' '.trans('messages.updated_msg'))->success();
        return redirect()->route('coupon.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coupon::destroy($id);
        flash(trans('messages.coupon').' '.trans('messages.deleted_msg'))->success();
        return redirect()->route('coupon.index');
    }

    public function setCouponData($request, $coupon)
    {
        if ($request->coupon_type == "product_base") {
            $coupon->type = $request->coupon_type;
            $coupon->code = $request->coupon_code;
            $coupon->discount = $request->discount;
            $coupon->discount_type = $request->discount_type;
            $date_var                 = explode(" - ", $request->date_range);
            $coupon->start_date       = strtotime($date_var[0]);
            $coupon->end_date         = strtotime($date_var[1]);
            $cupon_details = array();
            foreach ($request->product_ids as $product_id) {
                $data['product_id'] = $product_id;
                array_push($cupon_details, $data);
            }
            $coupon->details = json_encode($cupon_details);
        } elseif ($request->coupon_type == "cart_base") {
            $coupon->type             = $request->coupon_type;
            $coupon->code             = $request->coupon_code;
            $coupon->discount         = $request->discount;
            $coupon->discount_type    = $request->discount_type;
            $date_var                 = explode(" - ", $request->date_range);
            $coupon->start_date       = strtotime($date_var[0]);
            $coupon->end_date         = strtotime($date_var[1]);
            $data                     = array();
            $data['min_buy']          = $request->min_buy;
            $data['max_discount']     = $request->max_discount;
            $coupon->details          = json_encode($data);
        }

        $coupon->one_time_use = $request->one_time_use;

        return $coupon;
    }

    public function get_coupon_form(Request $request)
    {
        if ($request->coupon_type == "product_base") {
            $products = Product::all();
            return view('partials.coupons.product_base_coupon', compact('products'));
        } elseif ($request->coupon_type == "cart_base") {
            return view('partials.coupons.cart_base_coupon');
        }
    }

    public function get_coupon_form_edit(Request $request)
    {
        if ($request->coupon_type == "product_base") {
            $coupon = Coupon::findOrFail($request->id);

            $products = Product::all();

            return view('partials.coupons.product_base_coupon_edit', compact('coupon', 'products'));
        } elseif ($request->coupon_type == "cart_base") {
            $coupon = Coupon::findOrFail($request->id);
            return view('partials.coupons.cart_base_coupon_edit', compact('coupon'));
        }
    }
}
