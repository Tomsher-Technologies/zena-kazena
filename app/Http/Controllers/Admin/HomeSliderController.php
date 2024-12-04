<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Cache;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = HomeSlider::paginate(15);
        return view('backend.home_sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.home_sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
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
        ],[
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

        $slider = HomeSlider::create([
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
        ]);

        Cache::forget('homeSlider');

        flash(trans('messages.slider').' '.trans('messages.created_msg'))->success();
        return redirect()->route('home-slider.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function show(HomeSlider $homeSlider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function edit(HomeSlider $homeSlider)
    {
        return view('backend.home_sliders.edit', compact('homeSlider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HomeSlider $homeSlider)
    {
        $request->validate([
            'name' => 'required',
            'banner' => 'required',
            'mobile_banner' => 'required',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ],[
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);


        $homeSlider->update([
            'name' => $request->name,
            'image' => $request->banner,
            'mobile_image' => $request->mobile_banner,
            'link_type' => $request->link_type,
            'link_ref' => $request->link_type,
            'link_ref_id' => $request->link_ref_id,
            'link' => $request->link,
            'sort_order' => $request->sort_order,
            'status' => $request->status,
        ]);

        Cache::forget('homeSlider');

        flash(trans('messages.slider').' '.trans('messages.updated_msg'))->success();
        return redirect()->route('home-slider.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Frontend\HomeSlider  $homeSlider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        HomeSlider::destroy($id);
        Cache::forget('homeSlider');
        flash(trans('messages.slider').' '.trans('messages.deleted_msg'))->success();
        return redirect()->route('home-slider.index');
    }

    public function updateStatus(Request $request)
    {
        $slider = HomeSlider::findOrFail($request->id);
        Cache::forget('homeSlider');
        $slider->status = $request->status;
        if ($slider->save()) {
            return 1;
        }
        return 0;
    }
}
