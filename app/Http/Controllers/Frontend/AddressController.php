<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Cart;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $lang = getActiveLanguage();
        $addresses = Address::where('user_id', $request->user()->id)->get();
        return view('frontend.addresses',compact('addresses','lang'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required'
        ]);

        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';

        if($user_id != ''){
            $address = new Address;
            $address->user_id = $user_id;
            $address->address = $request->address ?? null;
            $address->name = $request->name ?? null;
            $address->city = $request->city ?? null;
            $address->postal_code = $request->zipcode ?? null;
            $address->phone = $request->phone ?? null;
            $address->save();
    
            return response()->json([
                'status' => true,
                'message' => trans('messages.address').' '.trans('messages.created_msg')
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.something_went_wrong')
            ]);
        }
    }

    public function setDefaultAddress(Request $request)
    {

        $validate = $request->validate([
            'address_id' => 'required'
        ]);

        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';

        if($user_id != ''){
            $address =  Address::where([
                'id' => $request->address_id,
                'user_id' => $user_id
            ])->first();

            if($address){
                Address::where('user_id', $user_id)->update(['set_default' => 0]); //make all user addressed non default first
    
                $add = Address::find($request->address_id);
                $add->set_default = 1;
                $add->save();
                
                return response()->json([
                    'status' => true,
                    'message' => trans('messages.default').' '.trans('messages.address').' '.trans('messages.updated_msg')
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => trans('messages.something_went_wrong')
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.something_went_wrong')
            ]);
        }
    }

    public function deleteAddress(Request $request){
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $address_id = $request->address_id ?? null;
        if($user_id != '' && $address_id != null){
            Address::where(['id' => $request->address_id,'user_id' => $user_id])->delete();
            return response()->json([
                'status' => true,
                'message' => trans('messages.address').' '.trans('messages.deleted_msg')
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.something_went_wrong')
            ]);
        }
    }

    public function updateAddress(Request $request){
        $validate = $request->validate([
            'address_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ]);

        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $address_id = $request->address_id ?? null;
        if($user_id != '' && $address_id != null){
            $address = Address::find($address_id);
            if($address){
                if ($address->user_id !== $user_id) {
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.unauthorized')
                    ]);
                }else{
                    $address->address = $request->address ?? null;
                    $address->name = $request->name ?? null;
                    $address->city = $request->city ?? null;
                    $address->postal_code = $request->zip_code ?? null;
                    $address->phone = $request->phone ?? null;
                    $address->save();
            
                    return response()->json([
                        'status' => true,
                        'message' =>  trans('messages.address').' '.trans('messages.updated_msg')
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => trans('messages.something_went_wrong')
                ]);
            }
        }
    }
}
