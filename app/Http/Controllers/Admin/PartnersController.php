<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partners;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class PartnersController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partners = Partners::orderBy('sort_order','asc')->paginate(15);

        return view('backend.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // echo '<pre>';
        // print_r($request->all());
        // die;
        $request->validate([
            // 'image' => 'required|max:1024',
            'image' => 'required',
            'name' => 'required',
            'sort_order' => 'nullable|integer',
            'status' => 'required',
        ],[
            // 'image.uploaded' => 'File size should be less than 1 MB'
        ]);
        $data = [
            'name'=> $request->name,
            'sort_order' => ($request->sort_order != '') ? $request->sort_order : 0,
            'link' => $request->link,
            'status' => $request->status,
            'image' => $request->image
        ];

        $partner = Partners::create($data);
       
        return redirect()->route('partners.index')->with([
            'status' => "Partners Created"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Partners $partner)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partners $partner)
    {
        return view('backend.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partners $partner)
    {
        $request->validate([
            // 'image' => 'nullable|max:1024',
            'image' => 'nullable',
            'name' => 'required',
            'sort_order' => 'nullable|integer',
            'status' => 'required',
        ],[
            // 'image.uploaded' => 'File size should be less than 1 MB'
        ]);

        $partner->name = $request->name;
        $partner->sort_order = ($request->sort_order != '') ? $request->sort_order : 0;
        $partner->status = $request->status;
        $partner->link = $request->link;
        $partner->image = $request->image;
        $partner->save();

        return redirect()->route('partners.index')->with([
            'status' => "Client details Updated"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partners $partner)
    {
        $img = $partner->image;
        if ($partner->delete()) {
            deleteImage($img);
        }
        return redirect()->route('partners.index')->with([
            'status' => "Banner Deleted"
        ]);
    }
}
