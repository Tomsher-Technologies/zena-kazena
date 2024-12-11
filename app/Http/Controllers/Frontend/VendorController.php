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
use App\Models\Review;
use App\Models\Vendor;
use App\Services\ImageResizeAndDownload;
use App\Models\Attribute;
use App\Models\AttributeValue;
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
        $credentials = $request->only('email', 'password');
        // Attempt to log the user in
        if (Auth::guard('vendor')->attempt($credentials)) {

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
            'profit_share'      => 'required',

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
            'profit_share'      => $request->profit_share,
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
        $products = Product::where('vendor_id', auth()->guard('vendor')->id())
            ->whereHas('stocks', function ($query) {
                $query->whereColumn('product_stocks.sku', 'sku');
            })
            ->with(['stocks' => function ($query) {
                $query->whereColumn('product_stocks.sku', 'sku');
            }])
            ->orderBy('created_at', 'desc');



        $products = $products->paginate(15);
        $type = 'All';
        return view('frontend.vendor.product.index', compact('products'));
    }
    public function vendorProductCreate()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('frontend.vendor.product.create', compact('categories'));
    }
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
            $product_stock->save();
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
                $product_attributes = []; // Initialize outside to collect all attributes once

                // Loop through each variant attribute
                foreach ($request->main_attributes as $attrId) {
                    if ($request->has('variant_attributes')) {
                        foreach ($request->variant_attributes as $variant_values) {
                            // Check if the current attribute exists in variant attributes
                            if (isset($variant_values[$attrId])) {
                                $product_attributes[] = [
                                    'product_id' => $product->id,
                                    'product_varient_id' => $product_stock->id,
                                    'attribute_id' => $attrId,
                                    'attribute_value_id' => $variant_values[$attrId][0], // Use the first value for the attribute
                                ];
                            }
                        }
                    }
                }

                // Bulk insert collected product attributes
                if (!empty($product_attributes)) {
                    ProductAttributes::insert($product_attributes);
                }

                // Handle variants
                if ($request->has('variants')) {
                    foreach ($request->variants as $variant) {
                        $product_stock = new ProductStock();
                        $product_stock->product_id = $product->id;
                        $product_stock->sku = $variant['sku'];
                        $product_stock->price = $variant['price'];
                        $product_stock->qty = $variant['quantity'];

                        $offertag = '';
                        $productOrgPrice = $variant['price'];
                        $discountPrice = $productOrgPrice;

                        // Calculate discount if applicable
                        $current_time = strtotime(date('d-m-Y H:i:s'));
                        if ($current_time >= $product->discount_start_date && $current_time <= $product->discount_end_date) {
                            if ($product->discount_type == 'percent') {
                                $discountPrice = $productOrgPrice - (($productOrgPrice * $product->discount) / 100);
                                $offertag = $product->discount . '% OFF';
                            } elseif ($product->discount_type == 'amount') {
                                $discountPrice = $productOrgPrice - $product->discount;
                                $offertag = 'AED ' . $product->discount . ' OFF';
                            }
                        }

                        $product_stock->price = $productOrgPrice;
                        $product_stock->offer_price = $discountPrice;
                        $product_stock->offer_tag = $offertag;

                        // Handle image
                        $product_stock->image = isset($variant['image'])
                            ? $this->imageService->downloadAndResizeImage('varient', $variant['image'], $variant['sku'], false)
                            : null;

                        $product_stock->save();

                        // Store attributes for this product variant
                        $variant_attributes = [];
                        foreach ($variant['attributes'] as $attribute_id => $attribute_values) {
                            if (!empty($attribute_values)) {
                                $variant_attributes[] = [
                                    'product_id' => $product->id,
                                    'product_varient_id' => $product_stock->id,
                                    'attribute_id' => $attribute_id,
                                    'attribute_value_id' => $attribute_values[0], // Use the first value for the attribute
                                ];
                            }
                        }

                        if (!empty($variant_attributes)) {
                            ProductAttributes::insert($variant_attributes);
                        }
                    }
                }
            }
        }


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
        $product = Product::find($id);
        $slug = $product->slug;
        $sku = $product->sku;
        $lang = getActiveLanguage();
        $product_stock = '';
        $response = $relatedProducts = [];
        if ($slug !=  '' && $sku != '') {
            $product_stock = ProductStock::leftJoin('products', 'products.id', '=', 'product_stocks.product_id')
                ->where('products.published', 1)
                ->where('product_stocks.status', 1)
                ->select('product_stocks.*')
                ->where('products.slug', $slug)
                ->where('product_stocks.sku', $sku)
                ->first();

            $category = [
                'id' => 0,
                'name' => "",
                'slug' => "",
                'logo' => "",
            ];
            if ($product_stock) {

                trackRecentlyViewed($product_stock->product->id);

                $currentAttributes = ($product_stock->product->product_type == 1) ? $product_stock->attributes->toArray() : [];

                $curAttr = [];
                if ($currentAttributes) {
                    foreach ($currentAttributes as $cAttr) {
                        $curAttr[$cAttr['attribute_id']] = $cAttr['attribute_value_id'];
                    }
                }

                $productAttributes = ($product_stock->product->product_type == 1) ? json_decode($product_stock->product->attributes) : [];
                $prodAttr = [];
                if ($productAttributes) {
                    $allAttributes = Attribute::pluck('name', 'id')->toArray();
                    $allAttributeValues = AttributeValue::with('attr_value_translations')
                        ->get()
                        ->pluck('attr_value_translations.value', 'id')
                        ->toArray();
                    foreach ($productAttributes as $pAttr) {
                        $attrs = [];
                        $attrs['id'] = $pAttr;
                        $attrs['name'] = $allAttributes[$pAttr];
                        $ids = ProductAttributes::where('product_id', $product_stock->product_id)->where('attribute_id', $pAttr)->pluck('attribute_value_id')->toArray();
                        $ids = array_unique($ids);
                        $values = [];
                        foreach ($ids as  $vId) {
                            $attrVal['id'] = $vId;
                            $attrVal['name'] = $allAttributeValues[$vId];
                            $values[] = $attrVal;
                        }
                        $attrs['values'] = $values;
                        $prodAttr[] = $attrs;
                    }
                }
                $varient_products = $varient_productsPrice = [];

                $varientProducts = ProductAttributes::leftJoin('product_stocks as ps', 'ps.id', '=', 'product_attributes.product_varient_id')
                    ->where('product_attributes.product_id', $product_stock->product_id)
                    ->groupBy('product_attributes.product_varient_id')
                    ->select(DB::raw('product_attributes.product_varient_id,ps.sku,
                                                GROUP_CONCAT(product_attributes.attribute_value_id) AS attr_values'))
                    ->get();
                // print_r($varientProducts);
                if ($varientProducts) {
                    foreach ($varientProducts as $varProd) {
                        $varient_products[] = [
                            $varProd->sku => explode(',', $varProd->attr_values)
                        ];

                        $priceData = getProductPrice($varProd->stocks);

                        $varient_productsPrice[] = [
                            $varProd->sku => [
                                'original_price' => $priceData['original_price'] ?? 0,
                                'discounted_price' => $priceData['discounted_price'] ?? 0,
                                'offer_tag' =>  $priceData['offer_tag']
                            ]
                        ];
                    }
                }

                if ($product_stock->product->category != null) {
                    $category = [
                        'id' => $product_stock->product->category->id ?? '',
                        'name' => $product_stock->product->category->getTranslation('name', $lang) ?? '',
                        'slug' => $product_stock->product->category->getTranslation('slug', $lang) ?? '',
                        'logo' => uploaded_asset($product_stock->product->category->getTranslation('icon', $lang) ?? ''),
                    ];
                }

                $photo_paths = explode(',', $product_stock->product->photos);

                $photos = [];
                if (!empty($photo_paths)) {
                    foreach ($photo_paths as $php) {
                        $photos[] = get_product_image($php);
                    }
                }
                $priceData = getProductOfferPrice($product_stock->product);
                if ($product->type == 'sale') {
                    $response = [
                        'id' => (int)$product_stock->id,
                        'wishlisted' => isWishlisted($product_stock->product_id, $product_stock->id),
                        'name' => $product_stock->product->getTranslation('name', $lang),
                        'slug' => $product_stock->product->slug,
                        'product_type' => $product_stock->product->product_type,
                        'occasion' => optional($product_stock->product->occasion)->getTranslation('name', $lang) ?? '',
                        'brand' => optional($product_stock->product->brand)->getTranslation('name', $lang) ?? '',
                        'category' => $category,
                        'video_provider' => $product_stock->product->video_provider ?? '',
                        'video_link' => $product_stock->product->video_link != null ?  $product_stock->product->video_link : "",
                        'return_refund' =>  $product_stock->product->return_refund,
                        'published' =>  $product_stock->product->published,
                        'photos' => $photos,
                        'thumbnail_image' => get_product_image($product_stock->product->thumbnail_img),
                        'variant_image' => ($product_stock->image != NULL) ?  get_product_image($product_stock->image) : '',
                        'tags' => explode(',', $product_stock->product->getTranslation('tags', $lang)),
                        'status' => $product_stock->status,
                        'sku' =>  $product_stock->sku,
                        'quantity' => $product_stock->qty ?? 0,
                        'description' => $product_stock->product->getTranslation('description', $lang),
                        'stroked_price' => $priceData['original_price'] ?? 0,
                        'main_price' => $priceData['discounted_price'] ?? 0,
                        'offer_tag' =>  $priceData['offer_tag'],
                        'current_stock' => (int)$product_stock->qty,
                        'rating' => (float)$product_stock->product->rating,
                        'rating_count' => (int)Review::where(['product_id' => $product_stock->product_id])->count(),
                        'tabs' => $product_stock->product->tabsLang,
                        'meta_title' => $product_stock->product->getSeoTranslation('meta_title', $lang) ?? '',
                        'meta_description' => $product_stock->product->getSeoTranslation('meta_description', $lang) ?? '',
                        'meta_keywords' => $product_stock->product->getSeoTranslation('meta_keywords', $lang) ?? '',
                        'og_title' => $product_stock->product->getSeoTranslation('og_title', $lang) ?? '',
                        'og_description' => $product_stock->product->getSeoTranslation('og_description', $lang) ?? '',
                        'twitter_title' => $product_stock->product->getSeoTranslation('twitter_title', $lang) ?? '',
                        'twitter_description' => $product_stock->product->getSeoTranslation('twitter_description', $lang) ?? '',
                        'current_attribute' => $curAttr,
                        'product_attributes' => $prodAttr,
                        'varient_products' => $varient_products,
                        'varient_productsPrice' => $varient_productsPrice
                    ];
                } elseif ($product->type == 'rent') {
                    $response = [
                        'id' => (int)$product_stock->id,
                        'wishlisted' => isWishlisted($product_stock->product_id, $product_stock->id),
                        'name' => $product_stock->product->getTranslation('name', $lang),
                        'slug' => $product_stock->product->slug,
                        'product_type' => $product_stock->product->product_type,
                        'occasion' => optional($product_stock->product->occasion)->getTranslation('name', $lang) ?? '',
                        'brand' => optional($product_stock->product->brand)->getTranslation('name', $lang) ?? '',
                        'category' => $category,
                        'video_provider' => $product_stock->product->video_provider ?? '',
                        'video_link' => $product_stock->product->video_link != null ?  $product_stock->product->video_link : "",
                        'return_refund' =>  $product_stock->product->return_refund,
                        'published' =>  $product_stock->product->published,
                        'photos' => $photos,
                        'thumbnail_image' => get_product_image($product_stock->product->thumbnail_img),
                        'variant_image' => ($product_stock->image != NULL) ?  get_product_image($product_stock->image) : '',
                        'tags' => explode(',', $product_stock->product->getTranslation('tags', $lang)),
                        'status' => $product_stock->status,
                        'sku' =>  $product_stock->sku,
                        'quantity' => $product_stock->qty ?? 0,
                        'description' => $product_stock->product->getTranslation('description', $lang),
                        'stroked_price' => $priceData['original_price'] ?? 0,
                        'main_price' => $priceData['discounted_price'] ?? 0,
                        'offer_tag' =>  $priceData['offer_tag'],
                        'deposit' => $product_stock->product->deposit ?? 0,
                        'current_stock' => (int)$product_stock->qty,
                        'rating' => (float)$product_stock->product->rating,
                        'rating_count' => (int)Review::where(['product_id' => $product_stock->product_id])->count(),
                        'tabs' => $product_stock->product->tabsLang,
                        'meta_title' => $product_stock->product->getSeoTranslation('meta_title', $lang) ?? '',
                        'meta_description' => $product_stock->product->getSeoTranslation('meta_description', $lang) ?? '',
                        'meta_keywords' => $product_stock->product->getSeoTranslation('meta_keywords', $lang) ?? '',
                        'og_title' => $product_stock->product->getSeoTranslation('og_title', $lang) ?? '',
                        'og_description' => $product_stock->product->getSeoTranslation('og_description', $lang) ?? '',
                        'twitter_title' => $product_stock->product->getSeoTranslation('twitter_title', $lang) ?? '',
                        'twitter_description' => $product_stock->product->getSeoTranslation('twitter_description', $lang) ?? '',
                        'current_attribute' => $curAttr,
                        'product_attributes' => $prodAttr,
                        'varient_products' => $varient_products,
                        'varient_productsPrice' => $varient_productsPrice
                    ];
                }

                $relatedProducts = $this->relatedProducts(10, 0, $slug, $product_stock->product->category->getTranslation('slug', $lang) ?? '');
            }
        }
        // echo '<pre>';
        // print_r($response);
        // die;
        $recentlyViewedProducts = getRecentlyViewedProducts();
        // return view('frontend.vendor.product.show', compact('lang','response','product_stock','relatedProducts','recentlyViewedProducts'));
        if ($product->type == 'sale') {
            return view('frontend.product_details', compact('lang', 'response', 'product_stock', 'relatedProducts', 'recentlyViewedProducts'));
        } elseif ($product->type == 'rent') {
            return view('frontend.rentproduct_details', compact('lang', 'response', 'product_stock', 'relatedProducts', 'recentlyViewedProducts'));
        }
    }
    public function vendorProductEdit($id)
    {
        $product = Product::find($id);
        return view('frontend.vendor.product.edit', compact('product'));
    }
    public function vendorProductUpdate(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count    = Product::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $id)->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;
        $product->save();


        if ($request->has('name')) {
            // Save English translation
            $product_translation_en = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $product->id]);
            $product_translation_en->name = $request->name;
            $product_translation_en->unit = $product->unit;
            $product_translation_en->description = $request->description;
            $product_translation_en->lang = 'en';
            $product_translation_en->save();
        }
        if ($request->has('name_ar')) {
            // Save Arabic translation
            $product_translation_ar = ProductTranslation::firstOrNew(['lang' => 'ar', 'product_id' => $product->id]);
            $product_translation_ar->name = $request->name_ar;
            $product_translation_ar->unit = $product->unit;
            $product_translation_ar->description = $request->description_ar;
            $product_translation_ar->lang = 'ar';
            $product_translation_ar->save();
        }
        flash(trans('messages.product') . ' ' . trans('messages.updated_msg'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('vendor.products.all');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vendorProductDeactivate($id)
    {
        $product = Product::findOrFail($id);

        if ($product) {
            $product->published = 0;
            $product->save();

            flash(__('messages.deactive_success_message'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(__('messages.Something went wrong'))->error();
            return back();
        }
    }
    public function relatedProducts($limit, $offset, $product_slug, $category_slug)
    {

        // $product_query = ProductStock::leftJoin('products','products.id','=','product_stocks.product_id')
        //                             ->where('products.published',1)
        //                             ->where('product_stocks.status',1)
        //                             ->select('product_stocks.*','products.id');

        $product_query = Product::leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->where('products.published', 1)
            ->where('product_stocks.status', 1)
            ->select('products.*') // Ensure only product fields are selected
            ->groupBy('products.id'); // Prevent duplication

        if ($category_slug) {
            $category_ids = Category::whereHas('category_translations', function ($query) use ($category_slug) {
                $query->where('slug', $category_slug);
            })->pluck('id')->toArray();;

            $childIds[] = $category_ids;
            if (!empty($category_ids)) {
                foreach ($category_ids as $cId) {
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if (!empty($childIds)) {
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }

            $product_query->whereIn('products.category_id', $category_ids);
        }
        $product_query->where('products.slug', '!=', $product_slug)->groupBy('products.id')->latest();

        $products = $product_query->skip($offset)->take($limit)->get();

        return $products;
    }
}
