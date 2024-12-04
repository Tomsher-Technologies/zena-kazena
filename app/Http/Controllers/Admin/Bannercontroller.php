<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;
use App\Models\Occasion;
use App\Models\BannerTranslation;
use App\Models\Product;
use Cache;
use Illuminate\Http\Request;
use Artisan;
class Bannercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::paginate(15);
        return view('backend.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'banner' => 'required',
            'mobile_banner' => 'required',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ], [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

        $banner = Banner::create([
            'name' => $request->name,
            // 'image' => $request->banner,
            // 'mobile_image' => $request->mobile_banner,
            // 'title' => $request->title,
            // 'sub_title' => $request->sub_title,
            // 'btn_text' => $request->btn_text,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        $banner_translation                 = BannerTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'banner_id' => $banner->id]);
        $banner_translation->image          = $request->banner;
        $banner_translation->mobile_image   = $request->mobile_banner;
        $banner_translation->title          = $request->title;
        $banner_translation->sub_title      = $request->sub_title;
        $banner_translation->btn_text       = $request->btn_text;
        $banner_translation->save();

        Artisan::call('cache:clear');
        flash(trans('messages.banner').' '.trans('messages.created_msg'))->success();
        return redirect()->route('banners.index');
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
        $lang   = $request->lang;
        $banner  = Banner::findOrFail($id);
        return view('backend.banners.edit', compact('banner','lang'));
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
        $request->validate([
            'name' => 'required',
            'banner' => 'required',
            'mobile_banner' => 'required',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ], [
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

        $banner = Banner::findOrFail($id);

        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $banner->name           = $request->name;
            $banner->link_type      = $request->link_type;
            $banner->link_ref       = $request->link_type;
            $banner->link_ref_id    = $request->link_ref_id;
            $banner->link           = $request->link;
            $banner->status         = $request->status;
            $banner->save();
        }
       
        $banner_translation                 = BannerTranslation::firstOrNew(['lang' => $request->lang, 'banner_id' => $banner->id]);
        $banner_translation->image          = $request->banner;
        $banner_translation->mobile_image   = $request->mobile_banner;
        $banner_translation->title          = $request->title;
        $banner_translation->sub_title      = $request->sub_title;
        $banner_translation->btn_text       = $request->btn_text;
        $banner_translation->save();

        Artisan::call('cache:clear');
        flash(trans('messages.banner').' '.trans('messages.updated_msg'))->success();
        return redirect()->route('banners.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        Cache::forget('smallBanners');
        flash(trans('messages.banner').' '.trans('messages.deleted_msg'))->success();
        return redirect()->route('banners.index');
    }

    public function get_form(Request $request)
    {
        $old_data = $request->old_data ?? null;
        if ($request->link_type == "product") {
            $products = Product::select(['id', 'name'])->get();
            return view('partials.banners.banner_form_product', compact('products', 'old_data'));
        } elseif ($request->link_type == "category") {
            $categories = Category::where('parent_id', 0)
                ->with('childrenCategories')
                ->get();
            return view('partials.banners.banner_form_category', compact('categories', 'old_data'));
        } elseif ($request->link_type == "brand") {
            $brands = Brand::select(['id', 'name'])->where('is_active', 1)->get();
            return view('partials.banners.banner_form_brand', compact('old_data', 'brands'));
        }elseif ($request->link_type == "occasion") {
            $occasions = Occasion::select(['id', 'name'])->get();
            return view('partials.banners.banner_form_occasion', compact('old_data', 'occasions'));
        }else {
            return view('partials.banners.banner_form', compact('old_data'));
        }
    }
}
