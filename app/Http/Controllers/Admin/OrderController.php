<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\OrderDetail;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\User;
use App\Models\OrderTracking;
use App\Models\BusinessSetting;
use App\Models\RentOrder;
use App\Models\RentOrderTracking;
use App\Models\Commission;
use Auth;
use Session;
use DB;
use Mail;
use DateTime;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            //->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct();

        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);

        foreach ($orders as $key => $value) {
            $order = \App\Models\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('frontend.user.seller.orders', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    // All Orders
    public function all_orders(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();
        $request->session()->put('last_url', url()->full());

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;

        $orders = Order::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.all_orders.index', compact('orders', 'sort_search', 'delivery_status', 'date'));
    }

    public function all_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();

        return view('backend.sales.all_orders.show', compact('order', 'delivery_boys'));
    }

    public function allCancelRequests(Request $request)
    {
        $request->session()->put('last_url', url()->full());
        $search         = ($request->has('search')) ? $request->search : '';
        $ca_search      = ($request->has('ca_search')) ? $request->ca_search : '';
        $date           = ($request->has('date')) ? $request->date : ''; //
        $refund_search  = ($request->has('refund_search')) ? $request->refund_search : '';

        $orders = Order::where('cancel_request', 1)->orderBy('cancel_request_date', 'DESC');
        if ($search) {
            $orders = $orders->where('code', 'like', '%' . $search . '%');
        }
        if ($ca_search) {
            $ca_search = ($ca_search == 10) ? 0 : $ca_search;
            $orders = $orders->where('cancel_approval', $ca_search);
        }

        if ($date != null) {
            $orders = $orders->whereDate('cancel_request_date', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('cancel_request_date', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        if ($refund_search) {
            $orders = $orders->where('cancel_refund_type', $refund_search);
        }

        $orders = $orders->paginate(15);
        // echo '<pre>';
        // print_r($orders);
        // die;
        return view("backend.sales.cancel_requests", compact('orders', 'search', 'ca_search', 'date', 'refund_search'));
    }

    public function allReturnRequests(Request $request)
    {
        $request->session()->put('return_last_url', url()->full());
        $search         = ($request->has('search')) ? $request->search : '';
        $ca_search      = ($request->has('ca_search')) ? $request->ca_search : '';
        $date           = ($request->has('date')) ? $request->date : ''; //
        $refund_search  = ($request->has('refund_search')) ? $request->refund_search : '';

        $orders = Order::where('return_request', 1)->orderBy('return_request_date', 'DESC');
        if ($search) {
            $orders = $orders->where('code', 'like', '%' . $search . '%');
        }
        if ($ca_search) {
            $ca_search = ($ca_search == 10) ? 0 : $ca_search;
            $orders = $orders->where('return_approval', $ca_search);
        }

        if ($date != null) {
            $orders = $orders->whereDate('return_request_date', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('return_request_date', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        if ($refund_search) {
            $orders = $orders->where('return_refund_type', $refund_search);
        }

        $orders = $orders->paginate(15);
        // echo '<pre>';
        // print_r($orders);
        // die;
        return view("backend.sales.return_requests", compact('orders', 'search', 'ca_search', 'date', 'refund_search'));
    }

    public function returnRequestStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $return_request = Order::findOrFail($id);
        if ($return_request->return_request == 1) {
            $return_request->return_approval = $status;

            $return_request->return_approval_date = date('Y-m-d H:i:s');
            $return_request->save();

            echo 1;
        } else {
            echo 0;
        }
    }

    public function return_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('backend.sales.return_orders_show', compact('order'));
    }


    public function cancelRequestStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $cancel_request = Order::findOrFail($id);
        if ($cancel_request->cancel_request == 1) {

            // $message = getOrderStatusMessageTest($cancel_request->user->name, $cancel_request->code);
            // $userPhone = $cancel_request->user->phone ?? '';

            $cancel_request->cancel_approval = $status;
            if ($status == 1) {
                $cancel_request->delivery_status = 'cancelled';

                // if($userPhone != '' && isset($message['cancelled']) && $message['cancelled'] != ''){
                //     SendSMSUtility::sendSMS($userPhone, $message['cancelled']);
                // }
            } else {
                // if($userPhone != '' && isset($message['cancel_reject']) && $message['cancel_reject'] != ''){
                //     SendSMSUtility::sendSMS($userPhone, $message['cancel_reject']);
                // }
            }
            $cancel_request->cancel_approval_date = date('Y-m-d H:i:s');
            $cancel_request->save();

            echo 1;
        } else {
            echo 0;
        }
    }

    public function cancel_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('backend.sales.cancel_orders_show', compact('order'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                } catch (\Exception $e) {
                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('frontend.user.seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        // $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $return_days = get_setting('default_return_time') ?? 0;
        if ($request->status == 'delivered') {
            $order->delivery_completed_date = date('Y-m-d H:i:s');
        }
        $order->save();

        $track              = new OrderTracking;
        $track->order_id    = $order->id;
        $track->status      = $request->status;
        $track->description = null;
        $track->status_date = date('Y-m-d H:i:s');
        $track->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;

                $return_status = getProductReturnRefundStatus($orderDetail->product_id);
              
                if ($request->status === 'delivered') {
                    if($return_status == 1){
                        $return_expiry_date = (new DateTime('today midnight'))->modify("+$return_days days")->setTime(23, 59, 59)->format('Y-m-d H:i:s');
                    }else{
                        $return_expiry_date = date('Y-m-d H:i:s');
                    }
                    $orderDetail->return_expiry_date = $return_expiry_date;
                }
               
                $orderDetail->save();

                $product_stock = ProductStock::where('id', $orderDetail->product_stock_id)
                    ->first();

                if ($request->status == 'cancelled') {
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        }



        return 1;
    }
    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);


        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }

        $order->payment_status = $request->status;
        $order->save();

        return 1;
    }
    public function orderView($id)
    {
        $orderdetails = OrderDetail::find($id);
        $products = $orderdetails->product;
        $order = $orderdetails->order;
        $photos = explode(',', $products->photos);
        return view('frontend.vendor.order.view', compact('orderdetails', 'products', 'order', 'photos'));
    }
    public function rentAll_orders(Request $request)
    {
        //CoreComponentRepository::instantiateShopRepository();
        $request->session()->put('last_url', url()->full());
        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;

        $orders = RentOrder::orderBy('id', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('order_code', 'like', '%' . $sort_search . '%');
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.sales.all_orders.rentindex', compact('orders', 'sort_search', 'delivery_status', 'date'));
    }
    public function rentAll_orders_show($id)
    {
        $order = RentOrder::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        // $delivery_boys = User::where('city', $order_shipping_address->city)
        //     ->where('user_type', 'delivery_boy')
        //     ->get();
        return view('backend.sales.all_orders.rentshow', compact('order'));
    }
    public function rentUpdate_delivery_status(Request $request)
    {
        $order = RentOrder::findOrFail($request->order_id);
        // $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        $track              = new RentOrderTracking();
        $track->order_id    = $order->id;
        $track->status      = $request->status;
        $track->description = null;
        $track->status_date = date('Y-m-d H:i:s');
        $track->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            $order->delivery_status = $request->status;
            $order->save();

            if ($request->status == 'cancelled') {
                $variant = $order->productStock->variant;
                if ($order->productStock->variant == null) {
                    $variant = '';
                }

                $product_stock = ProductStock::where('product_id', $orde->product_id)
                    ->where('variant', $variant)
                    ->first();

                if ($product_stock != null) {
                    $product_stock->qty += $order->quantity;
                    $product_stock->save();
                }
            }
        } else {


            $order->delivery_status = $request->status;
            $order->save();

            $product_stock = ProductStock::where('id', $order->product_stock_id)
                ->first();

            if ($request->status == 'cancelled') {
                if ($product_stock != null) {
                    $product_stock->qty += $order->quantity;
                    $product_stock->save();
                }
            }
        }



        return 1;
    }
    public function rentUpdate_tracking_code(Request $request)
    {
        $order = RentOrder::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }
    public function rentUpdate_payment_status(Request $request)
    {
        $order = RentOrder::findOrFail($request->order_id);
        $order->payment_status = $request->status;
        $order->save();
        return 1;
    }
    public function rentAllCancelRequests(Request $request)
    {
        $request->session()->put('last_url', url()->full());
        $search         = ($request->has('search')) ? $request->search : '';
        $ca_search      = ($request->has('ca_search')) ? $request->ca_search : '';
        $date           = ($request->has('date')) ? $request->date : ''; //
        $refund_search  = ($request->has('refund_search')) ? $request->refund_search : '';

        $orders = RentOrder::where('cancel_request', 1)->orderBy('cancel_request_date', 'DESC');
        if ($search) {
            $orders = $orders->where('order_code', 'like', '%' . $search . '%');
        }
        if ($ca_search) {
            $ca_search = ($ca_search == 10) ? 0 : $ca_search;
            $orders = $orders->where('cancel_approval', $ca_search);
        }

        if ($date != null) {
            $orders = $orders->whereDate('cancel_request_date', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('cancel_request_date', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }
        if ($refund_search) {
            $orders = $orders->where('cancel_refund_type', $refund_search);
        }
        $orders = $orders->paginate(15);
        // echo '<pre>';
        // print_r($orders);
        // die;
        return view("backend.sales.rentcancel_requests", compact('orders', 'search', 'ca_search', 'date', 'refund_search'));
    }
    public function rentCancelRequestStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $cancel_request = RentOrder::findOrFail($id);
        if ($cancel_request->cancel_request == 1) {

            $cancel_request->cancel_approval = $status;
            if ($status == 1) {
                $cancel_request->delivery_status = 'cancelled';
            } else {
            }
            $cancel_request->cancel_approval_date = date('Y-m-d H:i:s');
            $cancel_request->save();
            echo 1;
        } else {
            echo 0;
        }
    }
    public function rentCancel_orders_show($id)
    {
        $order = RentOrder::findOrFail(decrypt($id));
        return view('backend.sales.rentcancel_orders_show', compact('order'));
    }

    public function saveCommission(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'order_id' => 'required|integer',
            'product_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'total_amount' => 'required|numeric',
            'admin_amount' => 'required|numeric',
            'vendor_amount' => 'required|numeric',
            'share_percentage' => 'required|numeric',
        ]);

        // Create a new commission record
        $commission = Commission::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'vendor_id' => $request->vendor_id,
            'total_amount' => $request->total_amount,
            'admin_amount' => $request->admin_amount,
            'vendor_amount' => $request->vendor_amount,
            'share_percentage' => $request->share_percentage,
            'paid_status' => $request->paid_status,
            'order_type' => $request->order_type
        ]);

        // Return success message
        return response()->json(['message' => 'Payment has been successfully processed!']);
    }
}
