<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Color;
use App\Models\AttributeTranslation;
use App\Models\AttributeValue;
use App\Models\AttributeValueTranslation;
// use CoreComponentRepository;
use Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::orderBy('created_at', 'desc')->get();
        return view('backend.attribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->save();

        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(trans('messages.attribute').' '.trans('messages.created_msg'))->success();
        return redirect()->route('attributes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['attribute'] = Attribute::findOrFail($id);
        $data['all_attribute_values'] = AttributeValue::with('attribute')->where('attribute_id', $id)->get();
        return view("backend.attribute.attribute_value.index", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang      = $request->lang;
        $attribute = Attribute::findOrFail($id);
        return view('backend.attribute.edit', compact('attribute','lang'));
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
        $attribute = Attribute::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
          $attribute->name = $request->name;
        }
        $attribute->save();

        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => $request->lang, 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(trans('messages.attribute').' '.trans('messages.updated_msg'))->success();
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
        $attribute = Attribute::findOrFail($id);

        foreach ($attribute->attribute_translations as $key => $attribute_translation) {
            $attribute_translation->delete();
        }

        Attribute::destroy($id);
        flash(trans('messages.attribute').' '.trans('messages.deleted_msg'))->success();
        return redirect()->route('attributes.index');

    }

    public function store_attribute_value(Request $request)
    {
        $attribute_value = new AttributeValue;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->is_active = 1;
        $attribute_value->save();

        $attribute_value_translation = AttributeValueTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'attribute_value_id' => $attribute_value->id]);
        $attribute_value_translation->value = $request->value;
        $attribute_value_translation->save();

        flash(trans('messages.attribute').' '.trans('messages.value').' '.trans('messages.created_msg'))->success();
        return redirect()->route('attributes.show', $request->attribute_id);
    }

    public function edit_attribute_value(Request $request, $id)
    {
        $lang      = $request->lang;
        $attribute_value = AttributeValue::findOrFail($id);
        return view("backend.attribute.attribute_value.edit", compact('attribute_value','lang'));
    }

    public function update_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
       

        $attribute_value_translation = AttributeValueTranslation::firstOrNew(['lang' => $request->lang, 'attribute_value_id' => $attribute_value->id]);
        $attribute_value_translation->value = $request->value;
        $attribute_value_translation->save();

        flash(trans('messages.attribute').' '.trans('messages.value').' '.trans('messages.updated_msg'))->success();
        return back();
    }

    public function destroy_attribute_value($id)
    {
        $attribute_values = AttributeValue::findOrFail($id);
        AttributeValue::destroy($id);
        
        flash(trans('messages.attribute').' '.trans('messages.value').' '.trans('messages.deleted_msg'))->success();
        return redirect()->route('attributes.show', $attribute_values->attribute_id);

    }
   
    public function updateAttributeStatus(Request $request)
    {
        $attribute = Attribute::findOrFail($request->id);
        $attribute->is_active = $request->status;
        $attribute->save();
        
        return 1;
    }

    public function updateAttributeValueStatus(Request $request)
    {
        $attribute = AttributeValue::findOrFail($request->id);
        $attribute->is_active = $request->status;
        $attribute->save();
        
        return 1;
    }
    
}
