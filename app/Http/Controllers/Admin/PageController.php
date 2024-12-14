<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Occasion;
use App\Models\BusinessSetting;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Contacts;
use App\Models\Mortgages;
use App\Models\Sales;
use Storage;
use File;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pages = \App\Models\Page::where('status', 1)->orderBy('slug', 'asc')->get();

        return view('backend.website_settings.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.website_settings.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $page = new Page;
        $page->title = $request->title;
        if (Page::where('slug', preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)))->first() == null) {
            $page->slug             = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
            $page->type             = "custom_page";
            $page->content          = $request->content;
            $page->meta_title       = $request->meta_title;
            $page->meta_description = $request->meta_description;
            $page->keywords         = $request->keywords;
            $page->meta_image       = $request->meta_image;
            $page->save();

            $page_translation           = PageTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'page_id' => $page->id]);
            $page_translation->title    = $request->title;
            $page_translation->content  = $request->content;
            $page_translation->save();

            //     flash(translate('New page has been created successfully'))->success();
            //     return redirect()->route('website.pages');
            // }

            // flash(translate('Slug has been used already'))->warning();
            // return back();
            flash(__('messages.page_created_success'))->success();
            return redirect()->route('website.pages');
        }

        flash(__('messages.slug_used_message'))->warning();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $page_name = $request->page;
        $page = Page::where('type', $id)->first();
        if ($page != null) {
            $page_id = $page->id;

            if ($id == 'home') {
                $banners = Banner::where('status', 1)->get();
                $current_banners = BusinessSetting::whereIn('type', array('home_banner','home_mid_section_banner','home_center_banner', 'home_mid_banner', 'home_large_banner'))->get()->keyBy('type');
                
                $categories = Category::where('parent_id', 0)->where('is_active',1)->with('childrenCategories')->get();
    
                $products = Product::select('id', 'name')->where('published',1)->where('type','sale')->get();
                $brands = Brand::where('is_active',1)->orderBy('name', 'asc')->get();
                // $occasions = Occasion::where('is_active',1)->orderBy('name', 'asc')->get();
    
                return view('backend.website_settings.pages.home_page_edit', compact('page', 'banners', 'current_banners', 'categories', 'brands', 'products','lang','page_id'));
                
            }else if ($id == 'auction_home') {
                $banners = Banner::where('status', 1)->get();
                $current_banners = BusinessSetting::whereIn('type', array('auction_home_mid_section_banner','auction_home_center_banner', 'auction_home_mid_banner'))->get()->keyBy('type');

                $categories = Category::where('parent_id', 0)->where('is_active',1)->with('childrenCategories')->get();

                $products = Product::select('id', 'name')->where('published',1)->where('type','auction')->get();
                $brands = Brand::where('is_active',1)->orderBy('name', 'asc')->get();
                
                return view('backend.website_settings.pages.auction_home_page_edit', compact('page', 'banners', 'current_banners', 'categories', 'brands', 'products','lang','page_id'));
            
            }else if ($id == 'rent_home') {
                $banners = Banner::where('status', 1)->get();
                $current_banners = BusinessSetting::whereIn('type', array('rent_home_mid_section_banner','rent_home_center_banner', 'rent_home_mid_banner'))->get()->keyBy('type');

                $categories = Category::where('parent_id', 0)->where('is_active',1)->with('childrenCategories')->get();

                $products = Product::select('id', 'name')->where('published',1)->where('type','rent')->get();
                $brands = Brand::where('is_active',1)->orderBy('name', 'asc')->get();
                
                return view('backend.website_settings.pages.rent_home_page_edit', compact('page', 'banners', 'current_banners', 'categories', 'brands', 'products','lang','page_id'));
            
            }else if ($id == 'find_us' || $id == 'news' || $id == 'faq') {
                return view('backend.website_settings.pages.find_us', compact('page','lang','page_id'));
            }else if ($id == 'contact_us') {
                return view('backend.website_settings.pages.contact_us', compact('page','lang','page_id'));
            }else if ($id == 'mortgage' || $id == 'sales') {
                return view('backend.website_settings.pages.mortgage_sales', compact('page','lang','page_id'));
            }else if ($id == 'about_us') {
                return view('backend.website_settings.pages.about_us', compact('page','lang','page_id'));
            }else{
                return view('backend.website_settings.pages.edit', compact('page','lang','page_id'));
            }
        }
        abort(404);
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
        $image1 = $image2 = NULL;
        $page = Page::findOrFail($id);
        if ($page) {
            if ($request->hasfile('image')) {
                $photo = uploadImage('page', $request->image, 'image_1');
                $page->image = $photo;
                $page->save();
            }

            if ($request->hasfile('image1')) {
                $fileName1 = time() . '_' . uniqid();
                $image1 = uploadImage('page', $request->image1, $fileName1);
            }

            if ($request->hasfile('image2')) {
                $fileName2 = time() . '_' . uniqid();
                $image2 = uploadImage('page', $request->image2, $fileName2);
            }
            
            $page_translation                       = PageTranslation::firstOrNew(['lang' => $request->lang, 'page_id' => $page->id]);
            $page_translation->title                = $request->title;
            $page_translation->content              = $request->has('content') ? $request->content : NULL;
            $page_translation->sub_title            = $request->has('sub_title') ? $request->sub_title : NULL;
            $page_translation->heading1             = $request->has('heading1') ? $request->heading1 : NULL;
            $page_translation->content1             = $request->has('content1') ? $request->content1 : NULL;
            $page_translation->heading2             = $request->has('heading2') ? $request->heading2 : NULL;
            $page_translation->content2             = $request->has('content2') ? $request->content2 : NULL;
            $page_translation->heading3             = $request->has('heading3') ? $request->heading3 : NULL;
            $page_translation->content3             = $request->has('content3') ? $request->content3 : NULL;
            $page_translation->content4             = $request->has('content4') ? $request->content4 : NULL;
            $page_translation->content5             = $request->has('content5') ? $request->content5 : NULL;
            $page_translation->heading4             = $request->has('heading4') ? $request->heading4 : NULL;
            $page_translation->meta_title           = $request->meta_title;
            $page_translation->meta_description     = $request->meta_description;
            $page_translation->og_title             = $request->og_title;
            $page_translation->og_description       = $request->og_description;
            $page_translation->twitter_title        = $request->twitter_title;
            $page_translation->twitter_description  = $request->twitter_description;
            $page_translation->keywords             = $request->keywords;
            $page_translation->image1               = $image1;
            $page_translation->image2               = $image2;
            $page_translation->save();

            flash(trans('messages.page') . ' ' . trans('messages.updated_msg'))->success();
            return redirect()->route('website.pages');
        }

        flash(trans('messages.not_found'))->warning();
        return back();
    }

    public function enquiries()
    {
        $query = Contacts::latest();
        $contact = $query->paginate(20);
        return view('backend.contact', compact('contact'));
    }

    public function enquiriesMortgage()
    {
        $query = Mortgages::latest();
        $enquiries = $query->paginate(20);
        return view('backend.mortgage_enquiries', compact('enquiries'));
    }

    public function enquiriesSales()
    {
        $query = Sales::latest();
        $enquiries = $query->paginate(20);
        return view('backend.sales_enquiries', compact('enquiries'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        foreach ($page->page_translations as $key => $page_translation) {
            $page_translation->delete();
        }
        if (Page::destroy($id)) {
            flash(translate('Page has been deleted successfully'))->success();
            return redirect()->back();
        }
        return back();
    }

    public function show_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page != null) {
            return view('frontend.custom_page', compact('page'));
        }
        abort(404);
    }
    public function mobile_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page != null) {
            return view('frontend.m_custom_page', compact('page'));
        }
        abort(404);
    }

    public function delete_image(Request $request)
    {
        $page = Page::where('id', $request->id)->first();
        $fil_url = str_replace('/storage/', '', $request->url);
        $fil_url = $path = Storage::disk('public')->path($fil_url);
        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            unlink($fil_url);

            $page_img = explode(',', $page->image);
            $page_img =  array_diff($page_img, [$request->url]);
            if ($page_img) {
                $page->image = implode(',', $page_img);
            } else {
                $page->image = null;
            }

            $page->save();
            return 1;
        } else {
            return 0;
        }
    }
}
