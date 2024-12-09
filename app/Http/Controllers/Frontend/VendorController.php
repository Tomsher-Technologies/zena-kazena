<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Models\Vendor;
use App\Services\ImageResizeAndDownload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Image;
use Str;

class VendorController extends Controller
{
    protected $imageService;

    public function __construct(ImageResizeAndDownload $imageService)
    {
        $this->imageService = $imageService;
    }

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
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        $authvendor = auth()->guard('vendor')->user();
        $vendor = Vendor::find($authvendor->id);
        // Check if the current password matches
        if (!Hash::check($request->current_password, $vendor->password)) {
            session()->flash('message', trans('messages.current_password_incorrect'));
            session()->flash('alert-type', 'error');
            return redirect()->back();
        }

        // Update the password
        $vendor->password = $request->new_password;
        $vendor->save();

        session()->flash('message', trans('messages.password_updated_successfully'));
        session()->flash('alert-type', 'success');
        return redirect()->back();
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
    public function vendorAll_products(Request $request)
    {
        $products = Product::where('vendor_id', auth()->guard('vendor')->id())->orderBy('created_at', 'desc')->get();
        dd($products);
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc');
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

        return view('backend.products.index', compact('category', 'products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }
    public function vendorProductCreate()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('frontend.vendor.product.create', compact('categories'));
    }
    public function vendorProductGet_attribute_values(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('is_active', 1)->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslatedName(env('DEFAULT_LANGUAGE', 'en')) . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function vendorProductStore(Request $request)
    {
        // echo '<pre>';
        // echo env('DEFAULT_LANGUAGE', 'en');
        // // print_r($request->all());
        // die;
        $skuMain = $request->sku;
        if ($request->has('products')) {
            $products = $request->products;
            if (isset($products[0])) {
                $skuMain = $products[0]['sku'];
            }
        }
        $product = new Product;
        $product->type = $request->type;
        $product->deposit = $request->has('deposit') ? $request->deposit : 0;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->vendor_id = Auth::guard('vendor')->id();
        $product->added_by = 'vendor';
        $product->published = 0;
        $product->approved = 0;
        $product->occasion_id = $request->occasion_id;
        $product->min_qty = $request->min_qty;
        $product->vat = $request->vat;
        $product->sku = cleanSKU($skuMain);
        $product_type = ($request->product_type == 'variant') ? 1 : 0;
        $product->product_type = $product_type;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->unit_price =  0;
        // $product->unit_price = $request->has('price') ? $request->price : 0;
        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }
        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;



        if (!empty($request->main_attributes)) {
            $product->attributes = json_encode($request->main_attributes);
        } else {
            $product->attributes = json_encode(array());
        }

        // $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        $product->save();
        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }

        $product_translation = ProductTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE', 'en'), 'product_id' => $product->id]);
        $product_translation->name = $request->name;
        $product_translation->unit = $request->unit;
        $product_translation->tags = implode(',', $tags);
        $product_translation->description = $request->description;
        $product_translation->save();
        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }
            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = $this->imageService->downloadAndResizeImage('main_product', $file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::exists($product->thumbnail_img)) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                    }
                    Storage::delete($product->thumbnail_img);
                }
            }
            $gallery = $this->imageService->downloadAndResizeImage('main_product', $request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }

        $product->save();
        // Tabs

        if ($request->has('tabs')) {
            foreach ($request->tabs as $tab) {
                if (!empty($tab['tab_heading']) && !empty($tab['tab_description'])) {
                    $p_tab = $product->tabs()->create([
                        'lang'    => env('DEFAULT_LANGUAGE', 'en'),
                        'heading' => $tab['tab_heading'],
                        'content' => $tab['tab_description'],
                    ]);
                }
            }
        }
        if ($request->product_type == 'single') {
            $product_stock = new ProductStock();
            $product_stock->product_id = $product->id;
            $product_stock->sku = $product['sku'];
            $product_stock->price = $request->price; // $prod['price'];
            $product_stock->qty = $request->current_stock;

            $offertag       = '';
            $productOrgPrice = $request->price;
            $discountPrice = $productOrgPrice;
            $discount_applicable = false;
            if (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                if ($product->discount_type == 'percent') {
                    $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                    $offertag = $product->discount . '% OFF';
                } elseif ($product->discount_type == 'amount') {
                    $discountPrice = $productOrgPrice - $product->discount;
                    $offertag = 'AED ' . $product->discount . ' OFF';
                }
            }
            $product_stock->price       = $productOrgPrice;
            $product_stock->offer_price = $discountPrice;
            $product_stock->offer_tag   = $offertag;
        } elseif ($request->product_type == 'variant') {
            $product_stock = new ProductStock();
            $product_stock->product_id = $product->id;
            $product_stock->sku = $product['sku'];
            $product_stock->price = $request->price;
            $product_stock->qty = $request->current_stock;

            $offertag       = '';
            $productOrgPrice = $request->price;
            $discountPrice = $productOrgPrice;
            $discount_applicable = false;
            $product_attributes = array();
            if (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                if ($product->discount_type == 'percent') {
                    $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                    $offertag = $product->discount . '% OFF';
                } elseif ($product->discount_type == 'amount') {
                    $discountPrice = $productOrgPrice - $product->discount;
                    $offertag = 'AED ' . $product->discount . ' OFF';
                }
            }
            $product_stock->price       = $productOrgPrice;
            $product_stock->offer_price = $discountPrice;
            $product_stock->offer_tag   = $offertag;
            $variantImage = (isset($request->product_variant_image)) ? $this->imageService->downloadAndResizeImage('varient', $request->product_variant_image, $request->sku, false) : NULL;
            $product_stock->image = $variantImage;
            $product_stock->save();
            if ($request->has('main_attributes')) {
                foreach ($request->main_attributes as $key => $no) {
                    $attrId = $no;
                    // Loop through each variant attribute
                    foreach ($request->variant_attributes as $variant_key => $variant_values) {
                        // If the variant has the current attribute
                        if (isset($variant_values[$attrId])) {
                            $product_attributes[] = [
                                'product_id' => $product->id,
                                'product_varient_id' => $product_stock->id,
                                'attribute_id' => $attrId,
                                'attribute_value_id' => $variant_values[$attrId][0], // Use the variant value for the current attribute
                            ];
                        }
                        if (!empty($product_attributes)) {
                            $p_attribute = ProductAttributes::insert($product_attributes);
                        }
                    }
                }
                foreach ($request->main_attributes as $key => $no) {
                    if ($request->has('variants')) {
                        $attrId = $no;
                        // Loop through each variant attribute
                        foreach ($request->variants as $variant_key => $variant) {
                            $product_stock = new ProductStock();
                            $product_stock->product_id = $product->id;
                            $product_stock->sku = $variant['sku'];
                            $product_stock->price = $variant['price'];
                            $product_stock->qty =  $variant['quantity'];

                            $offertag       = '';
                            $productOrgPrice = $variant['price'];
                            $discountPrice = $productOrgPrice;
                            $discount_applicable = false;
                            if (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                                if ($product->discount_type == 'percent') {
                                    $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                                    $offertag = $product->discount . '% OFF';
                                } elseif ($product->discount_type == 'amount') {
                                    $discountPrice = $productOrgPrice - $product->discount;
                                    $offertag = 'AED ' . $product->discount . ' OFF';
                                }
                            }
                            $product_stock->price       = $productOrgPrice;
                            $product_stock->offer_price = $discountPrice;
                            $product_stock->offer_tag   = $offertag;
                            $variantImage = (isset($variant['image'])) ? $this->imageService->downloadAndResizeImage('varient', $variant['image'], $variant['sku'], false) : NULL;
                            $product_stock->image = $variantImage;
                            $product_stock->save();
                            foreach ($variant['attributes'] as $variant_key => $attribute) {
                                $attribute_value = $attribute[0];  // Or use reset($attribute);
                                // If the variant has the current attribute
                                if (isset($attribute[$attrId])) {
                                    $product_attributes[] = [
                                        'product_id' => $product->id,
                                        'product_varient_id' => $product_stock->id,
                                        'attribute_id' => $attrId,
                                        'attribute_value_id' => $attribute_value[$attrId][0], // Use the variant value for the current attribute
                                    ];
                                }
                                if (!empty($product_attributes)) {

                                    $p_attribute = ProductAttributes::insert($product_attributes);
                                }
                            }
                        }
                    }
                }
            }
        }


        dd($p_attribute);

        flash(trans('messages.product') . ' ' . trans('messages.created_msg'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('vendor.products.all');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vendorProductShow($id)
    {
        //
    }
    public function vendorProductUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $skuMain = '';
        if ($request->has('oldproduct')) {
            $oldproduct = $request->oldproduct;
            if (isset($oldproduct[0])) {
                $skuMain = $oldproduct[0]['sku'];
            }
        }

        if ($request->lang == env("DEFAULT_LANGUAGE", 'en')) {
            $product->name = $request->name;
        }

        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->user_id = Auth::user()->id;
        $product->occasion_id = $request->occasion_id;
        $product->min_qty = $request->min_qty;
        $product->vat = $request->vat;
        $product->sku = cleanSKU($skuMain);
        $product_type = ($request->product_type == 'variant') ? 1 : 0;
        $product->product_type = $product_type;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->unit_price = $request->has('price') ? $request->price : 0;
        $product->published                 = ($request->has('published')) ? 1 : 0;

        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->video_provider            = $request->video_provider;
        $product->video_link                = $request->video_link;
        $product->discount                  = $request->discount;
        $product->discount_type             = $request->discount_type;

        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date   = strtotime($date_var[0]);
            $product->discount_end_date     = strtotime($date_var[1]);
        }

        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count    = Product::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $id)->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;


        if (!empty($request->main_attributes)) {
            $product->attributes = json_encode($request->main_attributes);
        } else {
            $product->attributes = json_encode(array());
        }

        if ($request->has('return_refund')) {
            $product->return_refund = 1;
        }

        // $product->save();

        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }

            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = $this->downloadAndResizeImage('main_product', $file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::exists($product->thumbnail_img)) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                    }
                    Storage::delete($product->thumbnail_img);
                }
            }
            $gallery = $this->downloadAndResizeImage('main_product', $request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }
        $product->save();


        $product_translation                       = ProductTranslation::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);
        $product_translation->name = $request->name;
        $product_translation->unit = $request->unit;
        $product_translation->tags = implode(',', $tags);
        $product_translation->description = $request->description;
        $product_translation->save();


        $seo = ProductSeo::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);

        $seo->meta_title        = $request->meta_title;
        $seo->meta_description  = $request->meta_description;

        $keywords = array();
        if ($request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }
        $seo->meta_keywords = implode(',', $keywords);

        $seo->og_title        = $request->og_title;
        $seo->og_description  = $request->og_description;

        $seo->twitter_title        = $request->twitter_title;
        $seo->twitter_description  = $request->twitter_description;

        if ($request->meta_title == null) {
            $seo->meta_title = $product->name;
        }
        if ($request->og_title == null) {
            $seo->og_title = $product->name;
        }
        if ($request->twitter_title == null) {
            $seo->twitter_title = $product->name;
        }

        $seo->save();


        if ($request->has('tabs')) {
            ProductTabs::where('lang', $request->lang)->where('product_id', $product->id)->delete();
            foreach ($request->tabs as $tab) {
                $p_tab = $product->tabs()->create([
                    'lang'    => $request->lang,
                    'heading' => $tab['tab_heading'],
                    'content' => $tab['tab_description'],
                ]);
            }
        }

        if ($request->has('oldproduct')) {
            $oldproduct = $request->oldproduct;
            $oldproduct_attributes = array();
            foreach ($oldproduct as $prodOld) {
                $product_stock                      = ProductStock::find($prodOld['stock_id']);
                $product_stock->product_id          = $product->id;
                $product_stock->product_id = $product->id;
                $product_stock->sku = $prodOld['sku'];
                $product_stock->price = $prodOld['price']; // $prod['price'];
                $product_stock->qty = $prodOld['current_stock'];
                $product_stock->status              = $prodOld['status'];

                $price  = 0;
                $offertag       = '';
                $productOrgPrice = $prodOld['price'];
                $discountPrice = $productOrgPrice;

                $discount_applicable = false;
                if (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                    if ($product->discount_type == 'percent') {
                        $discountPrice  = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                        $offertag       = $product->discount . '% OFF';
                    } elseif ($product->discount_type == 'amount') {
                        $discountPrice  = $productOrgPrice - $product->discount;
                        $offertag       = 'AED ' . $product->discount . ' OFF';
                    }
                }
                $product_stock->price       = $productOrgPrice;
                $product_stock->offer_price = $discountPrice;
                $product_stock->offer_tag   = $offertag;


                if (isset($prodOld['variant_images'])) {
                    if ($product_stock->image) {
                        if (Storage::exists($product_stock->image)) {
                            $info = pathinfo($product_stock->image);
                            $file_name = basename($product_stock->image, '.' . $info['extension']);
                            $ext = $info['extension'];

                            $sizes = config('app.img_sizes');
                            foreach ($sizes as $size) {
                                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                                if (Storage::exists($path)) {
                                    Storage::delete($path);
                                }
                            }
                            Storage::delete($product_stock->image);
                        }
                    }
                    $gallery = $this->downloadAndResizeImage('varient', $prodOld['variant_images'], $prodOld['sku'], false);
                    $product_stock->image = $gallery;
                }

                $product_stock->save();

                if ($request->has('main_attributes')) {
                    ProductAttributes::where('product_id', $product->id)->delete();
                    foreach ($request->main_attributes as $key => $no) {
                        $attrId = 'choice_options_' . $no;
                        $oldproduct_attributes[] = [
                            'product_id' => $product->id,
                            'product_varient_id' => $product_stock->id,
                            'attribute_id' => $no,
                            'attribute_value_id' => $prodOld[$attrId]
                        ];
                    }
                }
            }
            if (!empty($oldproduct_attributes)) {
                ProductAttributes::insert($oldproduct_attributes);
            }
        }

        if ($request->has('products')) {
            $products = $request->products;
            $product_attributes = array();
            foreach ($products as $prod) {

                $product_stock = new ProductStock;
                $product_stock->product_id = $product->id;
                $product_stock->sku = $prod['sku'];
                $product_stock->price = $prod['price']; // $prod['price'];
                $product_stock->qty = $prod['current_stock'];

                $offertag       = '';
                $productOrgPrice = $prod['price'];
                $discountPrice = $productOrgPrice;

                $discount_applicable = false;
                if (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
                    if ($product->discount_type == 'percent') {
                        $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                        $offertag = $product->discount . '% OFF';
                    } elseif ($product->discount_type == 'amount') {
                        $discountPrice = $productOrgPrice - $product->discount;
                        $offertag = 'AED ' . $product->discount . ' OFF';
                    }
                }

                $product_stock->price       = $productOrgPrice;
                $product_stock->offer_price = $discountPrice;
                $product_stock->offer_tag   = $offertag;


                $variantImage = (isset($prod['variant_images'])) ? $this->downloadAndResizeImage('varient', $prod['variant_images'], $prod['sku'], false) : NULL;
                $product_stock->image = $variantImage;

                $product_stock->save();

                if ($request->has('main_attributes')) {
                    foreach ($request->main_attributes as $key => $no) {
                        $attrId = 'choice_options_' . $no;
                        $product_attributes[] = [
                            'product_id' => $product->id,
                            'product_varient_id' => $product_stock->id,
                            'attribute_id' => $no,
                            'attribute_value_id' => $prod[$attrId]
                        ];
                    }
                }
            }
            if (!empty($product_attributes)) {
                ProductAttributes::insert($product_attributes);
            }
        }

        flash(trans('messages.product') . ' ' . trans('messages.updated_msg'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.all');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vendorProductDestroy($id)
    {
        $product = Product::findOrFail($id);
        // foreach ($product->product_translations as $key => $product_translations) {
        //     $product_translations->delete();
        // }

        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
    public function vendorProductDelete_thumbnail(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

        $fil_url = str_replace('/storage/', '', $product->thumbnail_img);
        $fil_url = $path = Storage::disk('public')->path($fil_url);

        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                // if (Storage::exists($path)) {
                //     Storage::delete($path);
                // }
                unlink($path);
            }

            // Storage::delete($product->thumbnail_img);1
            unlink($fil_url);
            $product->thumbnail_img = null;
            $product->save();
            return 1;
        }
    }

    public function vendorProductDelete_variant_image(Request $request)
    {
        // $product = ProductStock::where('id', $request->id)->first();

        // $fil_url = str_replace('/storage/', '', $product->image);
        // $fil_url = $path = Storage::disk('public')->path($fil_url);

        // if (File::exists($fil_url)) {
        //     $info = pathinfo($fil_url);
        //     $file_name = basename($fil_url, '.' . $info['extension']);
        //     $ext = $info['extension'];

        //     $sizes = config('app.img_sizes');
        //     foreach ($sizes as $size) {
        //         $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
        //         // if (Storage::exists($path)) {
        //         //     Storage::delete($path);
        //         // }
        //         unlink($path);
        //     }

        //     // Storage::delete($product->thumbnail_img);1
        //     unlink($fil_url);
        //     $product->thumbnail_img = null;
        //     $product->save();
        //     return 1;
        // }
    }

    public function vendorProductDelete_gallery(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        $fil_url = str_replace('/storage/', '', $request->url);
        $fil_url = $path = Storage::disk('public')->path($fil_url);
        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                unlink($path);
            }

            unlink($fil_url);

            $thumbnail_img = explode(',', $product->photos);
            $thumbnail_img =  array_diff($thumbnail_img, [$request->url]);
            if ($thumbnail_img) {
                $product->photos = implode(',', $thumbnail_img);
            } else {
                $product->photos = null;
            }

            $product->save();
            return 1;
        } else {
            return 0;
        }
    }
}
