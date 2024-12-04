<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function vendorRegister()
    {
        return view('frontend.vendor.auth.register');
    }
    public function saveRegistration(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:vendors,email',
            'phone'             => 'required|string|max:15',
            'password'          => 'required|string|min:8|confirmed', // Make sure the password is confirmed
            'business_name'     => 'required|string|max:255',
            'business_type'     => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'trade_license'     => 'required|file|mimes:jpg,png,jpeg,pdf', // Assuming trade license is a file
            'address'           => 'required|string|max:500',
            'business_logo'     => 'required|file|mimes:jpg,png,jpeg', // Assuming business logo is a file
        ]);
        // Save vendor details to the database
        $vendor = new Vendor();
        $vendor->name = $request->name;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = $request->password;
        $vendor->business_name = $request->business_name;
        $vendor->business_type = $request->business_type;
        $vendor->registration_number = $request->registration_number;
        $vendor->address = $request->address;

        // Save the business logo
        if ($request->hasFile('business_logo')) {
            $logoPath = $request->file('business_logo')->store('vendors/logos', 'public');
            $vendor->logo = $logoPath;
        }

        // Save the trade license
        if ($request->hasFile('trade_license')) {
            $licensePath = $request->file('trade_license')->store('vendors/licenses', 'public');
            $vendor->trade_license = $licensePath;
        }

        // Save the vendor to the database
        $vendor->save();
        // Optionally, you can log the user in after registration
        // Auth::guard('vendor')->login($vendor);

        // Redirect to a specific page or return a success message
        return redirect()->route('vendor.login')->with('success', 'Vendor registered successfully');
    }
    public function vendorLogin()
    {
        return view('frontend.vendor.auth.login');
    }
    public function vendorDoLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('vendor.login')
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt to log the user in
        if (Auth::guard('vendor')->attempt($request->only('email', 'password'))) {

            $vendor = Auth::guard('vendor')->user();
            if ($vendor->is_active == 0) {
                Auth::guard('vendor')->logout(); // Log out the vendor
                return redirect()->route('vendor.login')
                    ->withErrors(['email' => 'Your account is inactive. Please contact support.'])
                    ->withInput();
            }
            return redirect()->route('vendor.myaccount'); // Redirect to home page if login is successful
        }

        // If authentication fails
        return redirect('vendor.login')->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
    public function vendorAccount(Request $request)
    {
        $request->session()->put('last_url', url()->full());

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;

        $orders = OrderDetail::whereIn(
            'product_id',
            Product::where('vendor_id', auth()->guard('vendor')->id())->pluck('id')
        )->orderBy('id', 'desc');

        // $orders = Order::whereIn(
        //     'id', 
        //     OrderDetail::whereIn(
        //         'product_id', 
        //         Product::where('vendor_id', auth()->guard('vendor')->id())->pluck('id')
        //     )->pluck('order_id') 
        // )->orderBy('id', 'desc');


        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('frontend.vendor.myaccount', compact('orders'));
    }
    public function vendorProfile()
    {
        $vendor = auth()->guard('vendor')->user();
        return view('frontend.vendor.auth.profile', compact('vendor'));
    }
    public function vendorLogout()
    {
        Auth::guard('vendor')->logout();
        return redirect()->route('home');
    }
    public function index(Request $request)
    {
        $sort_search = null;
        $vendors = Vendor::query();
        if ($request->has('search')) {
            $sort_search = $request->search;
            $vendors->where(function ($q) use ($sort_search) {
                $q->where('name', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%' . $sort_search . '%');
            });
        }
        $vendors = $vendors->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.vendors.index', compact('vendors', 'sort_search'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $vendor = Vendor::find($id);
        return view('backend.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required',
            'phone'             => 'required|string|max:15',
            'password'          => 'required|nullable|min:8|confirmed', // Make sure the password is confirmed
            'business_name'     => 'required|string|max:255',
            'business_type'     => 'required|string|max:255',
            'address'           => 'required|string|max:500',

        ]);
        // if ($validator->fails()) {
        //     flash(trans('messages.vendor') . ' ' . trans('messages.error_try_again'))->error();
        //     return redirect()->back();
        // }
        $vendor = Vendor::findOrFail($id);
        $data = [
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => $request->password,
            'business_name'     => $request->business_name,
            'business_type'     => $request->business_type,
            'address'           => $request->address,
        ];
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('vendors/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Save the trade license
        if ($request->hasFile('trade_license')) {
            $licensePath = $request->file('trade_license')->store('vendors/licenses', 'public');
            $data['trade_license'] = $licensePath;
        }
        $vendor->update($data);

        flash(trans('messages.vendor') . ' ' . trans('messages.updated_msg'))->success();
        return redirect()->route('vendors.index');
    }
    public function vendorUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            // 'email'             => 'required',
            'phone'             => 'required|string|max:15',
            // 'password'          => 'required|nullable|min:8|confirmed', // Make sure the password is confirmed
            'business_name'     => 'required|string|max:255',
            'business_type'     => 'required|string|max:255',
            'address'           => 'required|string|max:500',

        ]);
        if ($validator->fails()) {
            flash(trans('messages.vendor') . ' ' . trans('messages.error_try_again'))->error();
            return redirect()->back();
        }
        $id = auth()->guard('vendor')->user()->id;
        $vendor = Vendor::find($id);
        $data = [
            'name'              => $request->name,
            'phone'             => $request->phone,
            'business_name'     => $request->business_name,
            'business_type'     => $request->business_type,
            'address'           => $request->address,
        ];
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('vendors/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Save the trade license
        // if ($request->hasFile('trade_license')) {
        //     $licensePath = $request->file('trade_license')->store('vendors/licenses', 'public');
        //     $data['trade_license'] = $licensePath;
        // }
        $vendor->update($data);

        flash(trans('messages.vendor') . ' ' . trans('messages.updated_msg'))->success();
        return redirect()->route('vendor.myprofile');
    }
    public function destroy($id)
    {
        Vendor::destroy($id);
        flash(trans('messages.vendor') . ' ' . trans('messages.deleted_msg'))->success();
        return redirect()->route('vendors.index');
    }

    public function bulk_vendor_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $vendor_id) {
                $this->destroy($vendor_id);
            }
        }
        return 1;
    }
    public function ban($id)
    {
        $vendor = Vendor::findOrFail(decrypt($id));
        if ($vendor->is_active == true) {
            $vendor->is_active = false;
            flash(trans('messages.vendor') . ' ' . trans('messages.rejected') . ' ' . trans('messages.successfully'))->success();
        } else {
            $vendor->is_active = true;
            flash(trans('messages.vendor') . ' ' . trans('messages.approved') . ' ' . trans('messages.successfully'))->success();
        }
        $vendor->save();
        return back();
    }
    public function downloadTradeLicense($id)
    {

        $vendor = Vendor::find($id);
        $path = 'public/' . $vendor->trade_license;
        if (!Storage::exists($path)) {
            abort(404);
        }
        return Storage::download($path);
    }
    public function products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $id = auth()->guard('vendor')->id();
        $products = Product::where('vendor_id', $id)->orderBy('created_at', 'desc');

        $category = ($request->has('category')) ? $request->category : '';

        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            if ($col_name == 'status') {
                $products = $products->where('published', $query);
            } else {
                $products = $products->orderBy($col_name, $query);
            }

            $sort_type = $request->type;
        }
        if ($request->has('category') && $request->category !== '0') {
            $childIds = [];
            $categoryfilter = $request->category;
            $childIds[] = array($request->category);

            if ($categoryfilter != '') {
                $childIds[] = getChildCategoryIds($categoryfilter);
            }

            if (!empty($childIds)) {
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }

            $products = $products->whereHas('category', function ($q) use ($childIds) {
                $q->whereIn('id', $childIds);
            });
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }



        $products = $products->paginate(15);
        $type = 'All';

        return view('frontend.vendor.product.index', compact('category', 'products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addProduct()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('frontend.vendor.product.add', compact('categories'));
    }
}
