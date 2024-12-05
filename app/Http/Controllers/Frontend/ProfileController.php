<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\RentOrder;
use App\Models\RentOrderTracking;
use Hash;
use Illuminate\Support\Facades\File;
use Storage;
use Auth;

class ProfileController extends Controller
{
    public function counters()
    {
        return response()->json([
            'cart_item_count' => Cart::where('user_id', auth()->user()->id)->count(),
            'wishlist_item_count' => Wishlist::where('user_id', auth()->user()->id)->count(),
            'order_count' => Order::where('user_id', auth()->user()->id)->count(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|min:9',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
        // $user->update($request->only('name', 'phone'));

        session()->flash('message', trans('messages.profile_update_success'));
        session()->flash('alert-type', 'success');

        return redirect()->back();
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            session()->flash('message', trans('messages.current_password_incorrect'));
            session()->flash('alert-type', 'error');
            return redirect()->back();
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('message', trans('messages.password_updated_successfully'));
        session()->flash('alert-type', 'success');
        return redirect()->back();
    }

    public function updateImage(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if(!$user){
            return response()->json([
                'result' => false,
                'message' => translate("User not found."),
                'path' => ""
            ]);
        }

        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
        );

        try {
            $image = $request->image;
            $request->filename;
            $realImage = base64_decode($image);

            $dir = public_path('uploads/all');
            $full_path = "$dir/$request->filename";

            $file_put = file_put_contents($full_path, $realImage); // int or false

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "File uploading error",
                    'path' => ""
                ]);
            }


            $upload = new Upload;
            $extension = strtolower(File::extension($full_path));
            $size = File::size($full_path);

            if (!isset($type[$extension])) {
                unlink($full_path);
                return response()->json([
                    'result' => false,
                    'message' => "Only image can be uploaded",
                    'path' => ""
                ]);
            }


            $upload->file_original_name = null;
            $arr = explode('.', File::name($full_path));
            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }

            //unlink and upload again with new name
            unlink($full_path);
            $newFileName = rand(10000000000, 9999999999) . date("YmdHis") . "." . $extension;
            $newFullPath = "$dir/$newFileName";

            $file_put = file_put_contents($newFullPath, $realImage);

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "Uploading error",
                    'path' => ""
                ]);
            }

            $newPath = "uploads/all/$newFileName";

            if (env('FILESYSTEM_DRIVER') == 's3') {
                Storage::disk('s3')->put($newPath, file_get_contents(base_path('public/') . $newPath));
                unlink(base_path('public/') . $newPath);
            }

            $upload->extension = $extension;
            $upload->file_name = $newPath;
            $upload->user_id = $user->id;
            $upload->type = $type[$upload->extension];
            $upload->file_size = $size;
            $upload->save();

            $user->avatar_original = $upload->id;
            $user->save();



            return response()->json([
                'result' => true,
                'message' => translate("Image updated"),
                'path' => api_asset($upload->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage(),
                'path' => ""
            ]);
        }
    }

    // not user profile image but any other base 64 image through uploader
    public function imageUpload(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if(!$user){
            return response()->json([
                'result' => false,
                'message' => translate("User not found."),
                'path' => "",
                'upload_id' => 0
            ]);
        }

        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
        );

        try {
            $image = $request->image;
            $request->filename;
            $realImage = base64_decode($image);

            $dir = public_path('uploads/all');
            $full_path = "$dir/$request->filename";

            $file_put = file_put_contents($full_path, $realImage); // int or false

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "File uploading error",
                    'path' => "",
                    'upload_id' => 0
                ]);
            }


            $upload = new Upload;
            $extension = strtolower(File::extension($full_path));
            $size = File::size($full_path);

            if (!isset($type[$extension])) {
                unlink($full_path);
                return response()->json([
                    'result' => false,
                    'message' => "Only image can be uploaded",
                    'path' => "",
                    'upload_id' => 0
                ]);
            }


            $upload->file_original_name = null;
            $arr = explode('.', File::name($full_path));
            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }

            //unlink and upload again with new name
            unlink($full_path);
            $newFileName = rand(10000000000, 9999999999) . date("YmdHis") . "." . $extension;
            $newFullPath = "$dir/$newFileName";

            $file_put = file_put_contents($newFullPath, $realImage);

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "Uploading error",
                    'path' => "",
                    'upload_id' => 0
                ]);
            }

            $newPath = "uploads/all/$newFileName";

            if (env('FILESYSTEM_DRIVER') == 's3') {
                Storage::disk('s3')->put($newPath, file_get_contents(base_path('public/') . $newPath));
                unlink(base_path('public/') . $newPath);
            }

            $upload->extension = $extension;
            $upload->file_name = $newPath;
            $upload->user_id = $user->id;
            $upload->type = $type[$upload->extension];
            $upload->file_size = $size;
            $upload->save();

            return response()->json([
                'result' => true,
                'message' => translate("Image updated"),
                'path' => api_asset($upload->id),
                'upload_id' => $upload->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage(),
                'path' => "",
                'upload_id' => 0
            ]);
        }
    }

    public function orderList(Request $request){
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $user = User::find($user_id);
        $total_count = 0;
        $orderList = [];
        if($user){
            $sort_search = null;
            $delivery_status = null;

            $orders = Order::with(['orderDetails'])->select('id','code','delivery_status','payment_type','coupon_code','grand_total','created_at')->orderBy('id', 'desc')->where('user_id',$user_id);
            if ($request->has('search')) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($request->delivery_status != null) {
                $orders = $orders->where('delivery_status', $request->delivery_status);
                $delivery_status = $request->delivery_status;
            }
           
            $total_count = $orders->count();
            $orderList = $orders->get();
            $rentorders= RentOrder::where('user_id',$user->id)->orderBy('created_at', 'desc')->get();
            $rentordercount = $rentorders->count();
        }
        return view('frontend.orders',compact('orderList','total_count', 'rentorders','rentordercount'));
    }
    
    public function orderReturnList(Request $request){
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $user = User::find($user_id);
        $total_count = 0;
        $orderList = [];
        if($user){
            $sort_search = null;
            $delivery_status = null;

            $orders = Order::with(['orderDetails'])->select('id','code','delivery_status','payment_type','coupon_code','grand_total','created_at')->orderBy('id', 'desc')->where('user_id',$user_id)->where('return_request',1);
           
            $orderList = $orders->get();
        }
        return view('frontend.order-returns',compact('orderList'));
    }
    public function orderDetails(Request $request){
        $order_code = $request->code ?? '';
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $track_list = [];
        $lang = getActiveLanguage();
        $order = [];

        if($order_code != ''){
            $order = Order::where('code',$order_code)->where('user_id',$user_id)->first();
            if($order){
                $tracks = OrderTracking::where('order_id', $order->id)->orderBy('id','ASC')->get();
                
                if ($tracks) {
                    foreach ($tracks as $key=>$value) {
                        $temp = array();
                        $temp['id'] = $value->id;
                        $temp['status'] = $value->status;
                        $temp['date'] = date("d-m-Y H:i a", strtotime($value->status_date));
                        $track_list[] = $temp;
                    }
                }    
            }
        }

        if(!empty($track_list)){
            $dataByStatus = array_column($track_list, null, 'status');
        }else{
            $dataByStatus = [];
        }
        
        // echo '<pre>';
        // print_r($track_list);
        // print_r($dataByStatus);
        // die;

        return view('frontend.order_details',compact('lang','order','track_list','dataByStatus'));
    }

    public function getUserAccountInfo(){
        $user = Auth::user();
        return view('frontend.account', compact('user'));
    }
    
    public function rentOrderDetails(Request $request)
    {
        $order_code = $request->code ?? '';
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        $track_list = [];
        $lang = getActiveLanguage();
        $order = [];

        if($order_code != ''){
            $order = RentOrder::where('order_code',$order_code)->where('user_id',$user_id)->first();
            if($order){
                $tracks = RentOrderTracking::where('order_id', $order->id)->orderBy('id','ASC')->get();
                
                if ($tracks) {
                    foreach ($tracks as $key=>$value) {
                        $temp = array();
                        $temp['id'] = $value->id;
                        $temp['status'] = $value->status;
                        $temp['date'] = date("d-m-Y H:i a", strtotime($value->status_date));
                        $track_list[] = $temp;
                    }
                }    
            }
        }

        if(!empty($track_list)){
            $dataByStatus = array_column($track_list, null, 'status');
        }else{
            $dataByStatus = [];
        }
        // dd($order);
        // echo '<pre>';
        // print_r($track_list);
        // print_r($dataByStatus);
        // die;

        return view('frontend.rentorder_details',compact('lang','order','track_list','dataByStatus'));
    }
}
