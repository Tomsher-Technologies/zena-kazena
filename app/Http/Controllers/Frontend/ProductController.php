<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\Page;
use App\Models\PageTranslations;
use App\Models\PageSeos;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\HomeSlider;
use App\Models\Occasion;
use App\Models\Partners;
use App\Models\BusinessSetting;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttributes;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\WebHomeProductsCollection;
use Storage;
use Validator;
use Mail;
use DB;
use Hash;
use Cache;
use Carbon\Carbon;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $price = $request->price;
        $min_price = $max_price = 0;
        if($price != null){
            preg_match_all('/\d+/', $price, $matches);
            if(isset($matches[0][0])){
                $min_price = $matches[0][0];
            }
            if(isset($matches[0][1])){
                $max_price = $matches[0][1];
            }
           
        }
       
        $lang = getActiveLanguage();
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 0;
        $category = $request->has('category') ? $request->category  : false;
        $brand = $request->has('brand') ? $request->brand  : false;
        $occasion = $request->has('occasion') ? $request->occasion  : false;
        $sort_by = $request->has('sort_by') ? $request->sort_by : null;

        $product_query  = Product::wherePublished(1);
        $categoryData = null;
        if ($category) {
            $categoryData = Category::whereHas('category_translations', function ($query) use ($category) {
                                        $query->where('slug', $category);
                                    })->where('is_active',1)->first();

            $childIds = [];
            $category_ids = Category::whereHas('category_translations', function ($query) use ($category) {
                                        $query->where('slug', $category);
                                    })->where('is_active',1)->pluck('id')->toArray();

            $childIds[] = $category_ids;
            if(!empty($category_ids)){
                foreach($category_ids as $cId){
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            // print_r($childIds);
            // die;
            $product_query->whereIn('category_id', $childIds);
          
        }
        
        if ($brand) {
            $brand_ids = Brand::whereHas('brand_translations', function ($query) use ($brand) {
                                    $query->whereIn('slug', $brand);
                                })->where('is_active', 1)->pluck('id')->toArray();

            $product_query->whereIn('brand_id', $brand_ids);
        }

        if ($occasion) {
            $occasion_ids = Occasion::whereHas('occasion_translations', function ($query) use ($occasion) {
                                    $query->whereIn('slug', $occasion);
                                })->where('is_active', 1)->pluck('id')->toArray();

            $product_query->whereIn('occasion_id', $occasion_ids);
        }

        if ($sort_by) {
            switch ($sort_by) {
                case 'latest':
                    $product_query->latest();
                    break;
                case 'oldest':
                    $product_query->oldest();
                    break;
                case 'name_asc':
                    $product_query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $product_query->orderBy('name', 'desc');
                    break;
                case 'price_high':
                    $product_query->select('*', DB::raw("(SELECT MAX(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    $product_query->orderBy('sort_price', 'desc');
                    break;
                case 'price_low':
                    $product_query->select('*', DB::raw("(SELECT MIN(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    $product_query->orderBy('sort_price', 'asc');
                    break;
                default:
                    # code...
                    break;
            }
        }

        if ($request->search) {
            $sort_search = $request->search;
            $products = $product_query->where(function ($query) use($sort_search) {
                $query->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                })->orWhereHas('product_translations', function ($q) use ($sort_search) {
                    $q->where('tags', 'like', '%' . $sort_search . '%')->orWhere('name', 'like', '%' . $sort_search . '%');
                });

            });
            // SearchUtility::store($sort_search, $request);
        }

        if($max_price != 0 && $min_price != 0){
            $product_query->whereHas('stocks', function ($query) use ($min_price, $max_price) {
                $query->whereBetween('price', [$min_price, $max_price]);
            });
        }

        if($request->has('offers')){
            $today = Carbon::now()->timestamp;
            $product_query->where('discount_start_date', '<=', $today) // Offer starts on or before today
            ->where('discount_end_date', '>=', $today);
        }
        $products = $product_query->paginate(20)->appends($request->query());


        $categories = Cache::rememberForever('categories', function () {
            $details = Category::where('parent_id',0)->where('is_active', 1)->orderBy('name','asc')->get();
            return $details;
        });

        $brands = Cache::rememberForever('brands', function () {
            $details = Brand::where('is_active', 1)->orderBy('name','asc')->get();
            return $details;
        });

        $occasions = Cache::rememberForever('occasions', function () {
            $details = Occasion::where('is_active', 1)->orderBy('name','asc')->get();
            return $details;
        });

        return view('frontend.products',compact('limit','products','offset','min_price','max_price','category','brand','occasion','sort_by','lang','categories','brands','occasions','categoryData','price'));
    }

    public function productDetails(Request $request){
        $slug = $request->has('slug') ? $request->slug :  ''; 
        $sku  = $request->has('sku') ? $request->sku :  ''; 
        $lang = getActiveLanguage();

        $product_stock = '';
        $response = $relatedProducts = [];
        if($slug !=  '' && $sku !=''){
            $product_stock = ProductStock::leftJoin('products','products.id','=','product_stocks.product_id')
                                    ->where('products.published',1)
                                    ->where('product_stocks.status',1)
                                    ->select('product_stocks.*')
                                    ->where('products.slug', $slug)
                                    ->where('product_stocks.sku', $sku)
                                    ->first();

            
            $category = [
                'id'=> 0,
                'name'=> "",
                'slug' => "",
                'logo'=> "",
            ];
            
            if($product_stock){

                trackRecentlyViewed($product_stock->product->id);

                $currentAttributes = ($product_stock->product->product_type == 1) ? $product_stock->attributes->toArray() : [];

                $curAttr = [];
                if($currentAttributes){
                    foreach ($currentAttributes as $cAttr) {
                        $curAttr[$cAttr['attribute_id']] = $cAttr['attribute_value_id'];
                    }
                }

                $productAttributes = ($product_stock->product->product_type == 1) ? json_decode($product_stock->product->attributes) : [];
                $prodAttr = [];
            
                if($productAttributes){
                    $allAttributes = Attribute::pluck('name','id')->toArray();
                    $allAttributeValues = AttributeValue::with('attr_value_translations')
                                            ->get()
                                            ->pluck('attr_value_translations.value', 'id')
                                            ->toArray();
                    foreach($productAttributes as $pAttr){
                        $attrs = [];
                        $attrs['id'] = $pAttr;
                        $attrs['name'] = $allAttributes[$pAttr];
                        $ids = ProductAttributes::where('product_id', $product_stock->product_id)->where('attribute_id',$pAttr)->pluck('attribute_value_id')->toArray();
                        $ids = array_unique($ids);
                        $values = [];
                        foreach($ids as  $vId){
                            $attrVal['id'] = $vId;
                            $attrVal['name'] = $allAttributeValues[$vId];
                            $values[] = $attrVal;
                        }
                        $attrs['values'] = $values;
                        $prodAttr[] = $attrs;
                    }
                }
                $varient_products = $varient_productsPrice = [];

                $varientProducts = ProductAttributes::leftJoin('product_stocks as ps','ps.id','=','product_attributes.product_varient_id')
                                                ->where('product_attributes.product_id', $product_stock->product_id)
                                                ->groupBy('product_attributes.product_varient_id')
                                                ->select(DB::raw('product_attributes.product_varient_id,ps.sku,
                                                GROUP_CONCAT(product_attributes.attribute_value_id) AS attr_values'))
                                                ->get();
                // print_r($varientProducts);
                if($varientProducts){
                    foreach($varientProducts as $varProd){
                        $varient_products[] = [
                            $varProd->sku => explode(',',$varProd->attr_values)
                        ];

                        $priceData = getProductPrice($varProd->stocks);

                        $varient_productsPrice[] = [
                            $varProd->sku => ['original_price' => $priceData['original_price'] ?? 0,
                                                'discounted_price' => $priceData['discounted_price'] ?? 0,
                                                'offer_tag' =>  $priceData['offer_tag']
                                            ]
                        ];


                    }
                }

                if($product_stock->product->category != null) {
                    $category = [
                        'id'=> $product_stock->product->category->id ?? '',
                        'name'=> $product_stock->product->category->getTranslation('name', $lang) ?? '',
                        'slug' => $product_stock->product->category->getTranslation('slug', $lang) ?? '',
                        'logo'=> uploaded_asset($product_stock->product->category->getTranslation('icon', $lang) ?? ''),
                    ];
                }
    
                $photo_paths = explode(',',$product_stock->product->photos);
         
                $photos = [];
                if (!empty($photo_paths)) {
                    foreach($photo_paths as $php){
                        $photos[] = get_product_image($php);
                    }
                }
                $priceData = getProductOfferPrice($product_stock->product);
                
                $response = [
                    'id' => (integer)$product_stock->id,
                    'wishlisted' => isWishlisted($product_stock->product_id, $product_stock->id),
                    'name' => $product_stock->product->getTranslation('name', $lang),
                    'slug' => $product_stock->product->slug,
                    'product_type' => $product_stock->product->product_type,
                    'occasion' => optional($product_stock->product->occasion)->getTranslation('name', $lang) ?? '',
                    'brand' => optional($product_stock->product->brand)->getTranslation('name',$lang) ?? '',
                    'category' => $category,
                    'video_provider' => $product_stock->product->video_provider ?? '',
                    'video_link' => $product_stock->product->video_link != null ?  $product_stock->product->video_link : "",
                    'return_refund' =>  $product_stock->product->return_refund ,
                    'published' =>  $product_stock->product->published ,
                    'photos' => $photos,
                    'thumbnail_image' => get_product_image($product_stock->product->thumbnail_img),
                    'variant_image' => ($product_stock->image != NULL) ?  get_product_image($product_stock->image) : '' ,
                    'tags' => explode(',', $product_stock->product->getTranslation('tags', $lang)),
                    'status' => $product_stock->status,
                    'sku' =>  $product_stock->sku,
                    'quantity' => $product_stock->qty ?? 0,
                    'description' => $product_stock->product->getTranslation('description', $lang),
                    'stroked_price' => $priceData['original_price'] ?? 0,
                    'main_price' => $priceData['discounted_price'] ?? 0,
                    'offer_tag' =>  $priceData['offer_tag'],
                    'current_stock' => (integer)$product_stock->qty,
                    'rating' => (double)$product_stock->product->rating,
                    'rating_count' => (integer)Review::where(['product_id' => $product_stock->product_id])->count(),
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

                $relatedProducts = $this->relatedProducts(10, 0, $slug, $product_stock->product->category->getTranslation('slug', $lang) ?? '');
                
            }

            
        } 
        // echo '<pre>';
        // print_r($response);
        // die;

        $recentlyViewedProducts = getRecentlyViewedProducts();
     
        return view('frontend.product_details', compact('lang','response','product_stock','relatedProducts','recentlyViewedProducts'));
    }


    public function relatedProducts($limit, $offset, $product_slug, $category_slug){
       
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
            if(!empty($category_ids)){
                foreach($category_ids as $cId){
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }

            $product_query->whereIn('products.category_id', $category_ids);
        }
        $product_query->where('products.slug','!=', $product_slug)->groupBy('products.id')->latest();

        $products = $product_query->skip($offset)->take($limit)->get();

        return $products;
    }
      public function rentProducts(Request $request)
    {
        $price = $request->price;
        $min_price = $max_price = 0;
        if($price != null){
            preg_match_all('/\d+/', $price, $matches);
            if(isset($matches[0][0])){
                $min_price = $matches[0][0];
            }
            if(isset($matches[0][1])){
                $max_price = $matches[0][1];
            }
           
        }
       
        $lang = getActiveLanguage();
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 0;
        $category = $request->has('category') ? $request->category  : false;
        $brand = $request->has('brand') ? $request->brand  : false;
        $occasion = $request->has('occasion') ? $request->occasion  : false;
        $sort_by = $request->has('sort_by') ? $request->sort_by : null;

        $product_query  = Product::where('type','rent');
        $product_query  = $product_query->wherePublished(1);
        $categoryData = null;
        if ($category) {
            $categoryData = Category::whereHas('category_translations', function ($query) use ($category) {
                                        $query->where('slug', $category);
                                    })->where('is_active',1)->first();

            $childIds = [];
            $category_ids = Category::whereHas('category_translations', function ($query) use ($category) {
                                        $query->where('slug', $category);
                                    })->where('is_active',1)->pluck('id')->toArray();

            $childIds[] = $category_ids;
            if(!empty($category_ids)){
                foreach($category_ids as $cId){
                    $childIds[] = getChildCategoryIds($cId);
                }
            }

            if(!empty($childIds)){
                $childIds = array_merge(...$childIds);
                $childIds = array_unique($childIds);
            }
            // print_r($childIds);
            // die;
            $product_query->whereIn('category_id', $childIds);
          
        }
        
        if ($brand) {
            $brand_ids = Brand::whereHas('brand_translations', function ($query) use ($brand) {
                                    $query->whereIn('slug', $brand);
                                })->where('is_active', 1)->pluck('id')->toArray();

            $product_query->whereIn('brand_id', $brand_ids);
        }

        if ($occasion) {
            $occasion_ids = Occasion::whereHas('occasion_translations', function ($query) use ($occasion) {
                                    $query->whereIn('slug', $occasion);
                                })->where('is_active', 1)->pluck('id')->toArray();

            $product_query->whereIn('occasion_id', $occasion_ids);
        }

        if ($sort_by) {
            switch ($sort_by) {
                case 'latest':
                    $product_query->latest();
                    break;
                case 'oldest':
                    $product_query->oldest();
                    break;
                case 'name_asc':
                    $product_query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $product_query->orderBy('name', 'desc');
                    break;
                case 'price_high':
                    $product_query->select('*', DB::raw("(SELECT MAX(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    $product_query->orderBy('sort_price', 'desc');
                    break;
                case 'price_low':
                    $product_query->select('*', DB::raw("(SELECT MIN(price) from product_stocks WHERE product_id = products.id) as sort_price"));
                    $product_query->orderBy('sort_price', 'asc');
                    break;
                default:
                    # code...
                    break;
            }
        }

        if ($request->search) {
            $sort_search = $request->search;
            $products = $product_query->where(function ($query) use($sort_search) {
                $query->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                })->orWhereHas('product_translations', function ($q) use ($sort_search) {
                    $q->where('tags', 'like', '%' . $sort_search . '%')->orWhere('name', 'like', '%' . $sort_search . '%');
                });

            });
            // SearchUtility::store($sort_search, $request);
        }

        if($max_price != 0 && $min_price != 0){
            $product_query->whereHas('stocks', function ($query) use ($min_price, $max_price) {
                $query->whereBetween('price', [$min_price, $max_price]);
            });
        }

        if($request->has('offers')){
            $today = Carbon::now()->timestamp;
            $product_query->where('discount_start_date', '<=', $today) // Offer starts on or before today
            ->where('discount_end_date', '>=', $today);
        }
        $products = $product_query->paginate(20)->appends($request->query());


        $categories = Cache::rememberForever('categories', function () {
            $details = Category::where('parent_id',0)->where('is_active', 1)->orderBy('name','asc')->get();
            return $details;
        });

        $brands = Cache::rememberForever('brands', function () {
            $details = Brand::where('is_active', 1)->orderBy('name','asc')->get();
            return $details;
        });

        $occasions = Cache::rememberForever('occasions', function () {
            $details = Occasion::where('is_active', 1)->orderBy('name','asc')->get();
            return $details;
        });
        return view('frontend.rentproducts',compact('limit','products','offset','min_price','max_price','category','brand','occasion','sort_by','lang','categories','brands','occasions','categoryData','price'));
    }
    public function rentProductDetails(Request $request)
    {
        $slug = $request->has('slug') ? $request->slug :  ''; 
        $sku  = $request->has('sku') ? $request->sku :  ''; 
        $lang = getActiveLanguage();

        $product_stock = '';
        $response = $relatedProducts = [];
        if($slug !=  '' && $sku !=''){
            $product_stock = ProductStock::leftJoin('products','products.id','=','product_stocks.product_id')
                                    ->where('products.published',1)
                                    ->where('product_stocks.status',1)
                                    ->select('product_stocks.*')
                                    ->where('products.slug', $slug)
                                    ->where('product_stocks.sku', $sku)
                                    ->first();

            
            $category = [
                'id'=> 0,
                'name'=> "",
                'slug' => "",
                'logo'=> "",
            ];
            
            if($product_stock){

                trackRecentlyViewed($product_stock->product->id);

                $currentAttributes = ($product_stock->product->product_type == 1) ? $product_stock->attributes->toArray() : [];

                $curAttr = [];
                if($currentAttributes){
                    foreach ($currentAttributes as $cAttr) {
                        $curAttr[$cAttr['attribute_id']] = $cAttr['attribute_value_id'];
                    }
                }

                $productAttributes = ($product_stock->product->product_type == 1) ? json_decode($product_stock->product->attributes) : [];
                $prodAttr = [];
            
                if($productAttributes){
                    $allAttributes = Attribute::pluck('name','id')->toArray();
                    $allAttributeValues = AttributeValue::with('attr_value_translations')
                                            ->get()
                                            ->pluck('attr_value_translations.value', 'id')
                                            ->toArray();
                    foreach($productAttributes as $pAttr){
                        $attrs = [];
                        $attrs['id'] = $pAttr;
                        $attrs['name'] = $allAttributes[$pAttr];
                        $ids = ProductAttributes::where('product_id', $product_stock->product_id)->where('attribute_id',$pAttr)->pluck('attribute_value_id')->toArray();
                        $ids = array_unique($ids);
                        $values = [];
                        foreach($ids as  $vId){
                            $attrVal['id'] = $vId;
                            $attrVal['name'] = $allAttributeValues[$vId];
                            $values[] = $attrVal;
                        }
                        $attrs['values'] = $values;
                        $prodAttr[] = $attrs;
                    }
                }
                $varient_products = $varient_productsPrice = [];

                $varientProducts = ProductAttributes::leftJoin('product_stocks as ps','ps.id','=','product_attributes.product_varient_id')
                                                ->where('product_attributes.product_id', $product_stock->product_id)
                                                ->groupBy('product_attributes.product_varient_id')
                                                ->select(DB::raw('product_attributes.product_varient_id,ps.sku,
                                                GROUP_CONCAT(product_attributes.attribute_value_id) AS attr_values'))
                                                ->get();
                // print_r($varientProducts);
                if($varientProducts){
                    foreach($varientProducts as $varProd){
                        $varient_products[] = [
                            $varProd->sku => explode(',',$varProd->attr_values)
                        ];

                        $priceData = getProductPrice($varProd->stocks);

                        $varient_productsPrice[] = [
                            $varProd->sku => ['original_price' => $priceData['original_price'] ?? 0,
                                                'discounted_price' => $priceData['discounted_price'] ?? 0,
                                                'offer_tag' =>  $priceData['offer_tag']
                                            ]
                        ];


                    }
                }
                if($product_stock->product->category != null) {
                    $category = [
                        'id'=> $product_stock->product->category->id ?? '',
                        'name'=> $product_stock->product->category->getTranslation('name', $lang) ?? '',
                        'slug' => $product_stock->product->category->getTranslation('slug', $lang) ?? '',
                        'logo'=> uploaded_asset($product_stock->product->category->getTranslation('icon', $lang) ?? ''),
                    ];
                }
    
                $photo_paths = explode(',',$product_stock->product->photos);
         
                $photos = [];
                if (!empty($photo_paths)) {
                    foreach($photo_paths as $php){
                        $photos[] = get_product_image($php);
                    }
                }
                $priceData = getProductOfferPrice($product_stock->product);
                $response = [
                    'id' => (integer)$product_stock->id,
                    'wishlisted' => isWishlisted($product_stock->product_id, $product_stock->id),
                    'name' => $product_stock->product->getTranslation('name', $lang),
                    'slug' => $product_stock->product->slug,
                    'product_type' => $product_stock->product->product_type,
                    'occasion' => optional($product_stock->product->occasion)->getTranslation('name', $lang) ?? '',
                    'brand' => optional($product_stock->product->brand)->getTranslation('name',$lang) ?? '',
                    'category' => $category,
                    'video_provider' => $product_stock->product->video_provider ?? '',
                    'video_link' => $product_stock->product->video_link != null ?  $product_stock->product->video_link : "",
                    'return_refund' =>  $product_stock->product->return_refund ,
                    'published' =>  $product_stock->product->published ,
                    'photos' => $photos,
                    'thumbnail_image' => get_product_image($product_stock->product->thumbnail_img),
                    'variant_image' => ($product_stock->image != NULL) ?  get_product_image($product_stock->image) : '' ,
                    'tags' => explode(',', $product_stock->product->getTranslation('tags', $lang)),
                    'status' => $product_stock->status,
                    'sku' =>  $product_stock->sku,
                    'quantity' => $product_stock->qty ?? 0,
                    'description' => $product_stock->product->getTranslation('description', $lang),
                    'stroked_price' => $priceData['original_price'] ?? 0,
                    'main_price' => $priceData['discounted_price'] ?? 0,
                    'offer_tag' =>  $priceData['offer_tag'],
                    'deposit' => $product_stock->product->deposit ?? 0,
                    'current_stock' => (integer)$product_stock->qty,
                    'rating' => (double)$product_stock->product->rating,
                    'rating_count' => (integer)Review::where(['product_id' => $product_stock->product_id])->count(),
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
                $relatedProducts = $this->relatedProducts(10, 0, $slug, $product_stock->product->category->getTranslation('slug', $lang) ?? '');
                
            }

            
        } 
        // echo '<pre>';
        // print_r($response);
        // die;

        $recentlyViewedProducts = getRecentlyViewedProducts();
     
        return view('frontend.rentproduct_details', compact('lang','response','product_stock','relatedProducts','recentlyViewedProducts'));
    }

}
