<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
use App\Models\Subscriber;
use App\Models\Contacts;
use App\Mail\ContactEnquiry;
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

class FrontendController extends Controller
{

    public function loadSEO($model)
    {
        SEOTools::setTitle($model['title']);
        OpenGraph::setTitle($model['title']);
        TwitterCard::setTitle($model['title']);

        SEOMeta::setTitle($model['meta_title'] ?? $model['title']);
        SEOMeta::setDescription($model['meta_description']);
        SEOMeta::addKeyword($model['keywords']);

        OpenGraph::setTitle($model['og_title']);
        OpenGraph::setDescription($model['og_description']);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addProperty('locale', 'en_US');
        
        JsonLd::setTitle($model['meta_title']);
        JsonLd::setDescription($model['meta_description']);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model['twitter_title']);
        TwitterCard::setSite("@aldourigroup");
        TwitterCard::setDescription($model['twitter_description']);

        SEOTools::jsonLd()->addImage(URL::to(asset('assets/img/favicon.svg')));
    }

    public function loadDynamicSEO($model)
    {
        SEOTools::setTitle($model->title);
        OpenGraph::setTitle($model->title);
        TwitterCard::setTitle($model->title);

        SEOMeta::setTitle($model->seo_title ?? $model->title);
        SEOMeta::setDescription($model->seo_description);
        SEOMeta::addKeyword($model->keywords);

        OpenGraph::setTitle($model->og_title);
        OpenGraph::setDescription($model->og_description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addProperty('locale', 'en_US');
           
        JsonLd::setTitle($model->seo_title);
        JsonLd::setDescription($model->seo_description);
        JsonLd::setType('Page');

        TwitterCard::setTitle($model->twitter_title);
        TwitterCard::setSite("@aldourigroup");
        TwitterCard::setDescription($model->twitter_description);

        SEOTools::jsonLd()->addImage(URL::to(asset('assets/img/favicon.svg')));
    }
    public function home()
    {
        $page = Page::where('type','home')->first();
        $lang = getActiveLanguage();
        $seo = [
            'title'                 => $page->getTranslation('title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];
        
        $this->loadSEO($seo);

        $data['slider'] = Cache::rememberForever('homeSlider', function () {
            $sliders = HomeSlider::where('status',1)->orderBy('sort_order')->get();
            return $sliders;
        });

        $data['discover_categories'] = Cache::rememberForever('discover_categories', function () {
            $categories = get_setting('discover_categories');
            if ($categories) {
                $details = Category::whereIn('id', json_decode($categories))->where('is_active', 1)
                    ->get();
                return $details;
            }
        });

       

        $home_banners = BusinessSetting::whereIn('type', array('home_mid_section_banner','home_center_banner', 'home_mid_banner'))->get()->keyBy('type');
        
        $banners = [];
        $all_banners = Banner::where('status', 1);
        if(!empty($home_banners)){
            foreach($home_banners as $key => $hb){
                $bannerid = json_decode($hb->value);
                
                $bannerData = [];
                if(!empty($bannerid)){
                    $bannerData = Banner::where('status', 1)->whereIn('id', $bannerid)->get();
                }
                
                if(!empty($bannerData)){
                    foreach($bannerData as $bData){
                        
                        $banners[$key][] = array(
                            'type' => $bData->link_type ?? '',
                            'link' => $bData->link_type == 'external' ? $bData->link : $bData->getBannerLink(),
                            'type_id' => $bData->link_ref_id,
                            'image' => ($bData->getTranslation('image', $lang)) ? uploaded_asset($bData->getTranslation('image', $lang)) : '',
                            'mob_image' => ($bData->getTranslation('mobile_image', $lang)) ? uploaded_asset($bData->getTranslation('mobile_image', $lang)) : '',
                            'title' => $bData->getTranslation('title', $lang),
                            'sub_title' => $bData->getTranslation('sub_title', $lang),
                            'btn_text' => $bData->getTranslation('btn_text', $lang) 
                        );
                        
                    }
                }else{
                    $banners[$key] = array();
                }
            }
        }
       
       
        $data['banners'] = $banners;

        $data['new_arrival_products'] = Cache::remember('new_arrival_products', 3600, function () {
            $product_ids = get_setting('new_arrival_products');
            if ($product_ids) {
                $products =  Product::where('published', 1)->whereIn('id', json_decode($product_ids))->with('brand')->get();
                return $products;
            }
        });

        $data['home_occasions'] = Cache::rememberForever('home_occasions', function () {
            $occasions = get_setting('home_occasions');
            if ($occasions) {
                $details = Occasion::whereIn('id', json_decode($occasions))->where('is_active', 1)
                    ->get();
                return $details;
            }
        });

        

        $data['special_products'] = Cache::remember('special_products', 3600, function () {
            $product_ids = get_setting('special_products');
            if ($product_ids) {
                $products =  Product::where('published', 1)->whereIn('id', json_decode($product_ids))->with('brand')->get();
                return $products;
            }
        });

        $data['shop_by_brands'] = Cache::rememberForever('shop_by_brands', function () {
            $details = Brand::where('is_active', 1)->get();
            return $details;
        });

        $data['partners'] = Cache::rememberForever('partners', function () {
            $details = Partners::where('status', 1)->get();
            return $details;
        });

        // echo '<pre>';
        // print_r($data);
        // die;


        return view('frontend.home',compact('page','data','lang'));
    }

    public function about()
    {
        $page = Page::where('type','about_us')->first();
        $lang = getActiveLanguage();
        $seo = [
            'title'                 => $page->getTranslation('title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];
        
        $this->loadSEO($seo);
        return view('frontend.about',compact('page','lang'));
    }

    public function terms()
    {
        $page = Page::where('type','terms')->first();
        $lang = getActiveLanguage();
        $seo = [
            'title'                 => $page->getTranslation('title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];
        
        $this->loadSEO($seo);
        return view('frontend.terms',compact('page','lang'));
    }

    public function privacy()
    {
        $page = Page::where('type','privacy_policy')->first();
        $lang = getActiveLanguage();
        $seo = [
            'title'                 => $page->getTranslation('title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];
        
        $this->loadSEO($seo);
        return view('frontend.privacy_policy',compact('page','lang'));
    }

    public function mortgage(){
        $lang = getActiveLanguage();
        return view('frontend.mortgage', compact('lang'));
    }

    public function submitMortgageForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|digits_between:10,15',
            'description' => 'required|string',
            'idimage' => 'required|image|mimes:jpeg,png,jpg,gif', // Max size 2MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',  // Optional image
        ]);
    
        try {
            // Process uploaded files
            $idImagePath = null;
            if ($request->hasFile('idimage')) {
                $idimage = $request->file('idimage');
                $uniqueFileNameId = time() . '_' . uniqid() . '.' . $idimage->getClientOriginalExtension();
                $idImagePath = uploadImage('page', $request->idimage, $uniqueFileNameId);
            }

            $productImagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('idimage');
                $uniqueFileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $productImagePath = uploadImage('page', $request->image, $uniqueFileName);
            }

            // Save data to the database (example model: FormSubmission)
            $formData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'description' => $request->input('description'),
                'id_image_path' => $idImagePath,
                'product_image_path' => $productImagePath,
            ];

            // Assuming you have a FormSubmission model
            \App\Models\Mortgages::create($formData);

            // Respond with success
            return response()->json(['message' => trans('messages.contact_success_msg')]);

        } catch (\Exception $e) {
            return response()->json(['message' => trans('messages.something_went_wrong')]);
        }

        // Handle the form submission logic here
        
    }

    public function contact()
    {
        $page = Page::where('type','contact_us')->first();
        $lang = getActiveLanguage();
        $seo = [
            'title'                 => $page->getTranslation('title', $lang),
            'meta_title'            => $page->getTranslation('meta_title', $lang),
            'meta_description'      => $page->getTranslation('meta_description', $lang),
            'keywords'              => $page->getTranslation('keywords', $lang),
            'og_title'              => $page->getTranslation('og_title', $lang),
            'og_description'        => $page->getTranslation('og_description', $lang),
            'twitter_title'         => $page->getTranslation('twitter_title', $lang),
            'twitter_description'   => $page->getTranslation('twitter_description', $lang),
        ];
        
        $this->loadSEO($seo);
        return view('frontend.contact_us', compact('page','lang'));
    }

    public function submitContactForm(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string',
            'message' => 'required|string|max:5000',
        ]);

        $con                = new Contacts;
        $con->name          = $request->firstName.' '.$request->lastName;
        $con->email         = $request->email;
        $con->phone         = $request->phone;
        $con->subject       = $request->subject;
        $con->message       = $request->message;
        $con->save();
        
        // Send an email (optional)
        Mail::to(env('MAIL_ADMIN'))->queue(new ContactEnquiry($con));

        session()->flash('message', trans('messages.contact_success_msg'));
        session()->flash('alert-type', 'success');

        return redirect()->back();
    }

    public function changeLanguage(Request $request)
    {
       
        Session::put('locale', $request->locale);
        App::setLocale($request->locale);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ],[
            'email.required' => trans('messages.enter_email'),
            'email.email' => trans('messages.enter_valid_email'),
            'email.unique' => trans('messages.email_already_subscribed'),
        ]);

        Subscriber::create(['email' => $request->email]);

        return response()->json(['success' => trans('messages.newsletter_success')]);
    }

}
