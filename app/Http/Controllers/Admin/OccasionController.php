<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Occasion;
use App\Models\OccasionTranslation;
use App\Models\Product;
use Illuminate\Support\Str;
use DB;

class OccasionController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_occasions', ['only' => ['index','create','store','edit','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $occasions = Occasion::orderBy('name', 'asc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $occasions = $occasions->where('name', 'like', '%' . $sort_search . '%');
        }
        $occasions = $occasions->paginate(15);
        return view('backend.occasions.index', compact('occasions', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.occasions.create');
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
            'slug' => 'required',
        ]);
       
        $occasion               = new Occasion;
        $occasion->name         = $request->name ?? NULL;
        $occasion->is_active    = $request->status;
        $occasion->save();

        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $slug               = Str::lower($slug);
        $same_slug_count    = OccasionTranslation::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug              .= $slug_suffix;

        $occasion_translation                       = OccasionTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'occasion_id' => $occasion->id]);
        $occasion_translation->name                 = $request->name;
        $occasion_translation->slug                 = $slug;
        $occasion_translation->logo                 = $request->logo;
        $occasion_translation->meta_title           = $request->meta_title;
        $occasion_translation->meta_description     = $request->meta_description;
        $occasion_translation->meta_keywords        = $request->meta_keywords;
        $occasion_translation->og_title             = $request->og_title;
        $occasion_translation->og_description       = $request->og_description;
        $occasion_translation->twitter_title        = $request->twitter_title;
        $occasion_translation->twitter_description  = $request->twitter_description;
        $occasion_translation->save();

        flash(trans('messages.occasion').trans('messages.created_msg'))->success();
        return redirect()->route('occasions.index');
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
        $occasion  = Occasion::findOrFail($id);
        return view('backend.occasions.edit', compact('occasion', 'lang'));
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
            'slug' => 'required',
        ]);

        $occasion = Occasion::findOrFail($id);

        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $occasion->name         = $request->name;
            $occasion->is_active    = $request->status;
            $occasion->save();
        }

        $slug = '';
        if ($request->slug != null) {
            $slug = strtolower(Str::slug($request->slug, '-'));
            $same_slug_count = OccasionTranslation::where('slug', 'LIKE', $slug . '%')->where('occasion_id', '!=', $occasion->id)->count();
            $slug_suffix = $same_slug_count > 0 ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;
        }

        $occasion_translation                       = OccasionTranslation::firstOrNew(['lang' => $request->lang, 'occasion_id' => $occasion->id]);
        $occasion_translation->name                 = $request->name;
        $occasion_translation->slug                 = $slug;
        $occasion_translation->logo                 = $request->logo;
        $occasion_translation->meta_title           = $request->meta_title;
        $occasion_translation->meta_description     = $request->meta_description;
        $occasion_translation->meta_keywords        = $request->meta_keywords;
        $occasion_translation->og_title             = $request->og_title;
        $occasion_translation->og_description       = $request->og_description;
        $occasion_translation->twitter_title        = $request->twitter_title;
        $occasion_translation->twitter_description  = $request->twitter_description;
        $occasion_translation->save();

        flash(trans('messages.occasion').trans('messages.updated_msg'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $occasion = Occasion::findOrFail($id);
        Product::where('occasion_id', $occasion->id)->delete();
        foreach ($occasion->occasion_translations as $key => $occasion_translation) {
            $occasion_translation->delete();
        }
        Occasion::destroy($id);

        flash(trans('messages.occasion').trans('messages.deleted_msg'))->success();
        return redirect()->route('occasions.index');
    }

    public function updateStatus(Request $request)
    {
        $occasion = Occasion::findOrFail($request->id);
        
        $occasion->is_active = $request->status;
        $occasion->save();
       
        return 1;
    }
}
