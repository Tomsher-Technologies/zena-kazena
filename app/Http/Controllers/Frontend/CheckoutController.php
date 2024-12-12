<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CartController;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\OrderTracking;
use App\Models\Address;
use App\Models\CombinedOrder;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use App\Models\OrderPayments;
use Illuminate\Http\Request;
use App\Utility\NotificationUtility;
use App\Utility\SendSMSUtility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Cart;
use App\Mail\EmailManager;
use App\Models\Category;
use App\Models\RentOrder;
use App\Models\RentOrderTracking;
use App\Models\User;
use Carbon\Carbon;
use Mail;

class CheckoutController
{
    public function index()
    {
        $user = Auth::user();
        $cartController = new CartController();
        $response = $cartController->index();
        $addresses = Address::where('user_id', auth()->user()->id)->get();
       
        return view('frontend.checkout', compact('response', 'user','addresses'));
    }

    public function apply_coupon_code(Request $request)
    {
        $user = getUser();
        // print_r($user);
        if($user['users_id'] != ''){
            $cart_items = Cart::where([$user['users_id_type'] => $user['users_id']])->get();
            $cartCount = count($cart_items);
            $coupon = Coupon::where('code', $request->coupon)->first();

            if ($cart_items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.cart_empty')
                ], 200);
            }

            if ($coupon == null) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.invalid_coupon') 
                ], 200);
            }

            $in_range = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;

            if (!$in_range) {
                return response()->json([
                    'success' => false,
                    'message' => trans('messages.coupon_expired') 
                ], 200);
            }

            if($coupon->one_time_use == 1){
                $is_used = CouponUsage::where('user_id', $user['users_id'])->where('coupon_id', $coupon->id)->first() != null;

                if ($is_used) {
                    return response()->json([
                        'success' => false,
                        'message' => trans('messages.already_used_coupon') 
                    ], 200);
                }
            }

            $coupon_details = json_decode($coupon->details);

            if ($coupon->type == 'cart_base') {
                $subtotal = 0;
                $tax = 0;
                $shipping = 0;
                foreach ($cart_items as $key => $cartItem) {
                    $subtotal += $cartItem['offer_price'] * $cartItem['quantity'];
                    $tax += $cartItem['tax'];
                    $shipping += $cartItem['shipping'];
                }
                $sum = $subtotal + $tax + $shipping;

                if ($sum >= $coupon_details->min_buy) {
                    if ($coupon->discount_type == 'percent') {
                        $coupon_discount = ($sum * $coupon->discount) / 100;
                        if ($coupon_discount > $coupon_details->max_discount) {
                            $coupon_discount = $coupon_details->max_discount;
                        }
                    } elseif ($coupon->discount_type == 'amount') {
                        $coupon_discount = $coupon->discount;
                    }

                    Cart::where('user_id', $user['users_id'])->update([
                        'discount' => $coupon_discount / $cartCount,
                        'coupon_code' => $request->coupon,
                        'coupon_applied' => 1
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => trans('messages.coupon_applied')
                    ], 200);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => trans('messages.coupon_cannot_applied')
                    ], 200);
                }
            } elseif ($coupon->type == 'product_base') {
                $coupon_discount = 0;

                foreach ($cart_items as $key => $cartItem) {
                    foreach ($coupon_details as $key => $coupon_detail) {
                        if ($coupon_detail->product_id == $cartItem['product_id']) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount += ($cartItem['offer_price'] * $coupon->discount / 100) * $cartItem['quantity'];
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount += $coupon->discount * $cartItem['quantity'];
                            }
                        }
                    }
                }

                Cart::where('user_id', $user['users_id'])->update([
                    'discount' => $coupon_discount / $cartCount,
                    'coupon_code' => $request->coupon,
                    'coupon_applied' => 1
                ]);

                return response()->json([
                    'success' => true,
                    'message' => trans('messages.coupon_applied')
                ], 200);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => trans('messages.user_not_found')
            ], 200);
        }
    }

    public function remove_coupon_code(Request $request)
    {
        $user = getUser();
        Cart::where([$user['users_id_type'] => $user['users_id']])->update([
            'discount' => 0.00,
            'coupon_code' => "",
            'coupon_applied' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('messages.coupon_removed')
        ], 200);
    }

    public function placeOrder(Request $request){

        // echo '<pre>';
        // print_r($request->all());
        
        // die;

        $validatedData = $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_zipcode' => 'required|string|max:10',
            'billing_phone' => 'required|string|max:15',  // Add phone validation
            'billing_email' => 'required|email|max:255', // Add email validation
            'shipping_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_zipcode' => 'nullable|string|max:10',
            'shipping_phone' => 'nullable|string|max:15',  
        ],[
            'billing_name.required' => 'This field is required.',
            'billing_address.required' => 'This field is required.',
            'billing_city.required' => 'This field is required.',
            'billing_zipcode.required' => 'This field is required.',
            'billing_phone.required' => 'This field is required.',
            'billing_phone.max' => 'The phone number must not exceed 15 characters.',
            'billing_email.required' => 'This field is required.',
            'billing_email.email' => 'The email address must be a valid email.',
            'billing_email.max' => 'The email address must not exceed 255 characters.',
        ]);

        // $address_id = $request->address_id ?? null;
        $billing_shipping_same = $request->same_as_billing ?? null;

        $shipping_address = [];
        $billing_address = [];

        $user = getUser();
        $user_id = $user['users_id'];
        
        // if($user_id != ''){
        //     $address = Address::where('id', $address_id)->first();
        //     if($address){
        //         $shipping_address['name']        = $address->name;
        //         $shipping_address['email']       = auth('sanctum')->user()->email;
        //         $shipping_address['address']     = $address->address;
        //         $shipping_address['country']     = $address->country_name;
        //         $shipping_address['state']       = $address->state_name;
        //         $shipping_address['city']        = $address->city;
        //         $shipping_address['phone']       = $address->phone;
        //         $shipping_address['longitude']   = $address->longitude;
        //         $shipping_address['latitude']    = $address->latitude;
        //     }else{
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Shipping address not exist',
        //             'data' => array(
        //                 'order_id' => '',
        //                 'order_code' => '',
        //                 'grand_total' => 0,
        //                 'payment_type' => '',
        //                 'url' => ''
        //             )
        //         ], 200);
        //     }
        // }

        $billing_address['name']        = $request->billing_name;
        $billing_address['email']       = $request->billing_email;
        $billing_address['address']     = $request->billing_address;
        $billing_address['zipcode']     = $request->billing_zipcode;
        $billing_address['city']        = $request->billing_city;
        $billing_address['phone']       = $request->billing_phone;

        if ($billing_shipping_same != 'on') {
            $shipping_address['name']        = $request->shipping_name;
            $shipping_address['email']       = $request->billing_email;
            $shipping_address['address']     = $request->shipping_address;
            $shipping_address['zipcode']     = $request->shipping_zipcode;
            $shipping_address['city']        = $request->shipping_city;
            $shipping_address['phone']       = $request->shipping_phone;
        } else {
            $shipping_address = $billing_address;
        }

        $shipping_address_json = json_encode($shipping_address);
        $billing_address_json = json_encode($billing_address);

        $carts = Cart::where('user_id', $user_id)->orderBy('id','asc')->get();
            
        if(!empty($carts[0])){
            $carts->load(['product', 'product_stock']);

            $sub_total = $discount = $coupon_applied = $total_coupon_discount = $grand_total = $total_shipping = $total_tax = 0;
            $coupon_code = '';

            $order = Order::create([
                'user_id' => $user_id,
                'shipping_address' => $shipping_address_json,
                'billing_address' => $billing_address_json,
                'order_notes' => $request->order_note ?? '',
                'shipping_type' => 'free_shipping',
                'shipping_cost' => 0,
                'delivery_status' => 'pending',
                'payment_type' => $request->payment_method ?? '',
                'payment_status' => 'un_paid',
                'grand_total' =>  0,
                'tax' => 0,
                'sub_total' => 0,
                'offer_discount' => 0,
                'coupon_discount' => 0,
                'code' => date('Ymd-His') . rand(10, 99),
                'date' => strtotime('now'),
                'delivery_viewed' => 0
            ]);

            $track = new OrderTracking;
            $track->order_id = $order->id;
            $track->status = 'pending';
            $track->description = "The order has been placed successfully";
            $track->status_date = date('Y-m-d H:i:s');
            $track->save();

            $orderItems = $productQuantities = [];

            foreach($carts as $data){
                $sub_total = $sub_total + ($data->price * $data->quantity);
                $total_tax = $total_tax + $data->tax;
                $total_shipping = $total_shipping + $data->shipping_cost;
                $discount = $discount + (($data->price * $data->quantity) - ($data->offer_price * $data->quantity)) + $data->offer_discount;
                $coupon_code = $data->coupon_code;
                $coupon_applied = $data->coupon_applied;
                if($data->coupon_applied == 1){
                    $total_coupon_discount += $data->discount;
                }
                $orderItems[] = [
                    'order_id' => $order->id,
                    'product_id' => $data->product_id,
                    'product_stock_id' => $data->product_stock->id,
                    'variation' => json_encode(getProductAttributes($data->product_stock->attributes)),
                    'og_price' => $data->price,
                    'tax' => $data->tax,
                    'shipping_cost' => $data->shipping_cost,
                    'offer_price' => $data->offer_price,
                    'price' => $data->offer_price * $data->quantity,
                    'quantity' => $data->quantity,
                ];
                $productQuantities[$data->product_id] = $data->quantity;

                $product = Product::find($data->product_id);
                $product->num_of_sale += $data->quantity;
                $product->save();
            }
            OrderDetail::insert($orderItems);
            $grand_total = ($sub_total + $total_tax + round($total_shipping))  - ($discount + $total_coupon_discount);

            $order->grand_total         = $grand_total;
            $order->sub_total           = $sub_total;
            $order->offer_discount      = $discount;
            $order->tax                 = $total_tax;
            $order->shipping_cost       = round($total_shipping);
            $order->shipping_type       = ($total_shipping == 0) ? 'free_shipping' : 'flat_rate';
            $order->coupon_discount     = $total_coupon_discount;
            $order->coupon_code         = $coupon_code;
            $order->save();

            if($coupon_code != ''){
                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = $user_id;
                $coupon_usage->coupon_id = Coupon::where('code', $coupon_code)->first()->id;
                $coupon_usage->save();
            }
            if($request->payment_method == 'cod'){
                reduceProductQuantity($productQuantities);
                Cart::where('user_id', $user_id)->delete();

                // NotificationUtility::sendOrderPlacedNotification($order);
                // NotificationUtility::sendNotification($order, 'created');
            
                return redirect()->route('order.success', $order->id);
            }else{
              
                
            }
        }else{
            return redirect()->route('order.failed');
        }
    }
    
    public function successPayment(Request $request){
        $encResponse = $request->encResp;          //This is the response sent by the CCAvenue Server
        $rcvdString = decryptCC($encResponse,env('CCA_WORKING_KEY')); //Crypto Decryption used as per the specified working key.
        $order_status = $order_code = $tracking_id = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        $details = [];
        for($i = 0; $i < $dataSize; $i++) {
            $information=explode('=',$decryptValues[$i]);
            $details[$information[0]] = $information[1];
            if($i==0)  $order_code=$information[1];
            if($i==1)  $tracking_id=$information[1];
            if($i==3)  $order_status=$information[1];
        }
     
        $payment_details = json_encode($details);

        if($order_code != ''){
            $order = Order::where('code','=',$order_code)->firstOrFail();
            if(strtolower($order_status) === "success"){
                $order->payment_status = 'paid';
                $order->payment_tracking_id = $tracking_id;
                $order->payment_details = $payment_details;
                $order->save();
                Cart::where('user_id', $order->user_id)->delete();

                NotificationUtility::sendOrderPlacedNotification($order);
                NotificationUtility::sendNotification($order, 'created');
                $message = getOrderStatusMessageTest($order->user->name, $order->code);
                $userPhone = $order->user->phone ?? '';
                if($userPhone != '' && $message['order_placed'] != ''){
                    SendSMSUtility::sendSMS($userPhone, $message['order_placed']);
                }
    
                $orderDetails = OrderDetail::where('order_id', $order->id)->get();

                if(!empty($orderDetails[0])){
                    foreach($orderDetails as $od){
                        $od->payment_status = 'paid';
                        $od->save();

                        $product_stock = ProductStock::where('product_id', $od->product_id)->first();
                        $product_stock->qty -= ($od->quantity >= $product_stock->qty) ? 0: ($product_stock->qty - $od->quantity);
                        $product_stock->save();
                    }
                }
    
                $orderPayments = new OrderPayments();
                $orderPayments->order_id = $order->id;
                $orderPayments->payment_status = $order_status;
                $orderPayments->payment_details = $payment_details;
                $orderPayments->save();
            }else{
                $orderDetails = Order::where('code','=',$order_code)->delete();
            }    
        }
        return redirect(env('CCA_PAYMENT_SUCCESS').'?status='.$order_status.'&code='.$order_code);
    }

    public function cancelPayment(Request $request){
        $encResponse = $request->encResp;          //This is the response sent by the CCAvenue Server
        $rcvdString = decryptCC($encResponse,env('CCA_WORKING_KEY')); //Crypto Decryption used as per the specified working key.
        $order_status = $order_code = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        $details = [];
        for($i = 0; $i < $dataSize; $i++) {
            $information=explode('=',$decryptValues[$i]);
            $details[$information[0]] = $information[1];
            if($i==0)  $order_code=$information[1];
            if($i==3)  $order_status=$information[1];
        }
     
        $payment_details = json_encode($details);
        
        if($order_code != ''){
            $orderDetails = Order::where('code','=',$order_code)->delete();

            // $orderPayments = new OrderPayments();
            // $orderPayments->order_id = $orderDetails->id;
            // $orderPayments->payment_status = $order_status;
            // $orderPayments->payment_details = $payment_details;
            // $orderPayments->save();
        }
        return redirect(env('CCA_PAYMENT_CANCEL').'?status='.$order_status.'&code='.$order_code);
    }

    public function cancelOrderRequest(Request $request){
        $order_id = $request->order_id ?? '';
        $reason   = $request->reason ?? '';
        $user = getUser();
        if($order_id != ''){
            $order = Order::find($order_id);
            if($order){
                if($order->cancel_request == 0 && $order->delivery_status == "pending"){
                    $order->cancel_request = 1;
                    $order->cancel_request_date = date('Y-m-d H:i:s');
                    $order->cancel_reason = $reason;
                    $order->save();

                    // $array['view'] = 'emails.commonmail';
                    // $array['subject'] = "New Order Cancel Request - ".$order->code;
                    // $array['from'] = env('MAIL_FROM_ADDRESS');
                    // $array['content'] = "<p>Hi,</p>
                    //                 <p style='line-height: 25px;'>We have received a new order cancel request. Below are the details of the order:</p>
                    //                 <p><b>Order Code : </b>".$order->code."</p>
                    //                 <p><b>Customer Name : </b>".$order->user->name ."</p>
                    //                 <p style='line-height: 25px;'><b>Reason for cancel: </b>".$reason ."</p>
                    //                 <p><b>Cancel Request Date: </b>".date('d-M-Y H:i a')."</p><br>
                    //                 <p>Thank you for your cooperation.</p>
                    //                 <p>Best regards,</p>
                    //                 <p>Team ".env('APP_NAME')."</p>";
                    // Mail::to(env('MAIL_ADMIN'))->queue(new EmailManager($array));
                    
                    return response()->json([
                        'status' => true,
                        'message' => trans('messages.request_success')
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.request_already_send')
                    ], 200);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => trans('messages.not_found')
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.not_found')
            ], 200);
        }
    }

    public function returnOrderRequest(Request $request){
        $order_id = $request->order_id ?? '';
        $reason   = $request->reason ?? '';
        $user = getUser();
        if($order_id != ''){
            $order = Order::find($order_id);
            if($order){
                if($order->return_request == 0 && $order->delivery_status == "delivered"){
                    $order->return_request = 1;
                    $order->return_request_date = date('Y-m-d H:i:s');
                    $order->return_reason = $reason;
                    $order->save();

                    // $array['view'] = 'emails.commonmail';
                    // $array['subject'] = "New Order Cancel Request - ".$order->code;
                    // $array['from'] = env('MAIL_FROM_ADDRESS');
                    // $array['content'] = "<p>Hi,</p>
                    //                 <p style='line-height: 25px;'>We have received a new order cancel request. Below are the details of the order:</p>
                    //                 <p><b>Order Code : </b>".$order->code."</p>
                    //                 <p><b>Customer Name : </b>".$order->user->name ."</p>
                    //                 <p style='line-height: 25px;'><b>Reason for cancel: </b>".$reason ."</p>
                    //                 <p><b>Cancel Request Date: </b>".date('d-M-Y H:i a')."</p><br>
                    //                 <p>Thank you for your cooperation.</p>
                    //                 <p>Best regards,</p>
                    //                 <p>Team ".env('APP_NAME')."</p>";
                    // Mail::to(env('MAIL_ADMIN'))->queue(new EmailManager($array));
                    
                    return response()->json([
                        'status' => true,
                        'message' => trans('messages.request_success')
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.request_already_send')
                    ], 200);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => trans('messages.not_found')
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.not_found')
            ], 200);
        }
    }

    public function success($order_id)
    {
        $order = Order::findOrFail($order_id);
        return view('frontend.order_success', compact('order'));
    }

    // Handle the failed page
    public function failed()
    {
        return view('frontend.order_failed');
    }
    
    public function rentProductOrder(Request $request)
    {
        $request->validate([
            'accept_terms' => 'accepted',
        ]);
        $lang = getActiveLanguage();
        $user = Auth::user();
        // dd($user->eid_no, $user->eid_image_front, $user->eid_image_back, $user->eid_approval_status, $user->id);
        if (is_null($user->eid_no) || is_null($user->eid_image_front) || is_null($user->eid_image_back) || $user->eid_approval_status != 1)
        {
            $message = __('messages.profile_eid_verification_required');
            $alertType = 'error'; // or 'warning', 'info', etc., 
        
            session()->flash('message', $message);
            session()->flash('alert-type', $alertType);
            return redirect()->route('account');
        }
        $product_stock = ProductStock::leftJoin('products', 'products.id', '=', 'product_stocks.product_id')
            ->where('products.published', 1)
            ->where('product_stocks.status', 1)
            ->select('product_stocks.*')
            ->where('products.slug', $request['slug'])
            ->where('product_stocks.sku', $request['sku'])
            ->first();
        $category = Category::find($request->category_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;
        $quantity_price = $product_stock->offer_price * $request['product_quantity'];
        $subtotal = $quantity_price * $days;
        $total = $subtotal + $request['deposit'];
        // dd($product_stock);
        //  dd($days, $request['price'],  $subtotal, $request['deposit'], $total, $request->all());
        $order = RentOrder::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'product_stock_id' => $product_stock->id,
            'sku' => $request->sku,
            'quantity' => $request->product_quantity,
            // 'shipping_address' => $shipping_address_json,
            // 'billing_address' => $billing_address_json,
            // 'order_notes' => $request->order_note ?? '',
            // 'delivery_status' => 'pending',
            // 'payment_type' => $request->payment_method ?? '',
            // 'payment_status' => 'un_paid',
            'shipping_type' => 'free_shipping',
            'shipping_cost' => 0,
            'deposit' => $request->deposit,
            'grand_total' =>  $total,
            'tax' => 0,
            'sub_total' => $subtotal,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'no_of_days' => $days,
            'offer_discount' => 0,
            'coupon_discount' => 0,
            'order_code' => date('Ymd-His') . rand(10, 99),
            'order_date' => strtotime('now'),
            'delivery_viewed' => 0
        ]);

        $products = Product::find($request->product_id);
        $product = [
            'id' => $products->id,
            'product_variant_id' => $product_stock->id,
            'name' => $products->getTranslation('name', $lang),
            'brand' => $products->brand ? $products->brand->getTranslation('name', $lang) : '',
            'slug' => $products->slug,
            'sku' => $product_stock->sku,
            'image' => \Storage::disk('public')->exists($products->thumbnail_img) 
            ? get_product_image($products->thumbnail_img, '300') 
            : $products->thumbnail_img,
            'attributes' => getProductAttributes($product_stock->attributes),
            'price' => $product_stock->offer_price,
        ];
        // return view('frontend.rentcheckout', compact('response', 'user'));
        return view('frontend.rentcheckout', compact('order', 'user', 'product'));
    }
    public function placeRentOrder(Request $request)
    {
        $validatedData = $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_zipcode' => 'required|string|max:10',
            'billing_phone' => 'required|string|max:15',  // Add phone validation
            'billing_email' => 'required|email|max:255', // Add email validation
            'shipping_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_zipcode' => 'nullable|string|max:10',
            'shipping_phone' => 'nullable|string|max:15',
        ], [
            'billing_name.required' => 'This field is required.',
            'billing_address.required' => 'This field is required.',
            'billing_city.required' => 'This field is required.',
            'billing_zipcode.required' => 'This field is required.',
            'billing_phone.required' => 'This field is required.',
            'billing_phone.max' => 'The phone number must not exceed 15 characters.',
            'billing_email.required' => 'This field is required.',
            'billing_email.email' => 'The email address must be a valid email.',
            'billing_email.max' => 'The email address must not exceed 255 characters.',
        ]);

        // $address_id = $request->address_id ?? null;
        $billing_shipping_same = $request->same_as_billing ?? null;

        $shipping_address = [];
        $billing_address = [];

        $user = getUser();
        $user_id = $user['users_id'];

        $billing_address['name']        = $request->billing_name;
        $billing_address['email']       = $request->billing_email;
        $billing_address['address']     = $request->billing_address;
        $billing_address['zipcode']     = $request->billing_zipcode;
        $billing_address['city']        = $request->billing_city;
        $billing_address['phone']       = $request->billing_phone;

        if ($billing_shipping_same != 'on') {
            $shipping_address['name']        = $request->shipping_name;
            $shipping_address['email']       = $request->billing_email;
            $shipping_address['address']     = $request->shipping_address;
            $shipping_address['zipcode']     = $request->shipping_zipcode;
            $shipping_address['city']        = $request->shipping_city;
            $shipping_address['phone']       = $request->shipping_phone;
        } else {
            $shipping_address = $billing_address;
        }

        $shipping_address_json = json_encode($shipping_address);
        $billing_address_json = json_encode($billing_address);

        $order = RentOrder::find($request->order_id);
        $order->update([
            'shipping_address' => $shipping_address_json,
            'billing_address' => $billing_address_json,
            'order_notes' => $request->order_note ?? '',
            'delivery_status' => 'pending',
            'payment_type' => $request->payment_method ?? '',
            'payment_status' => 'un_paid',
        ]);

        $track = new RentOrderTracking();
        $track->order_id = $order->id;
        $track->status = 'pending';
        $track->description = "The order has been placed successfully";
        $track->status_date = date('Y-m-d H:i:s');
        $track->save();

        if ($order) {

            if ($order->payment_type == 'cod') {
                $product_stock = ProductStock::find($order->product_stock_id);
                // dd($product_stock->qty , $order->quantity);
                $product_stock->qty -= $order->quantity;
                // dd($product_stock->qty);
                // reduceProductQuantity($order->quantity);
                // dd($order->id);
                return redirect()->route('rent.order.success', $order->id);
            } else {
                
            return redirect()->route('rent.order.failed');
            }
        } else {
            return redirect()->route('rent.order.failed');
        }
    }
    public function cancelRentOrderRequest(Request $request)
    {
        $order_id = $request->order_id ?? '';
        $reason   = $request->reason ?? '';
        $user = getUser();
        if ($order_id != '') {
            $order = RentOrder::find($order_id);
            if ($order) {
                if ($order->cancel_request == 0 && $order->delivery_status !== "delivered") {
                    $order->cancel_request = 1;
                    $order->cancel_request_date = date('Y-m-d H:i:s');
                    $order->cancel_reason = $reason;
                    $order->save();
                    return response()->json([
                        'status' => true,
                        'message' => trans('messages.request_success')
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => trans('messages.request_already_send')
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => trans('messages.not_found')
                ], 200);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => trans('messages.not_found')
            ], 200);
        }
    }
    public function successRentOrder($order_id)
    {
        $order = RentOrder::findOrFail($order_id);
        return view('frontend.rentorder_success', compact('order'));
    }

    // Handle the failed page
    public function failedRentOrder()
    {
        return view('frontend.rentorder_failed');
    }
}
