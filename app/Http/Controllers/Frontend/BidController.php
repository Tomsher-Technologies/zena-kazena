<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Cart;

class BidController extends Controller
{
    public function placeBid(Request $request, $id)
    {
        $product = Product::with('stocks')->findOrFail($id);

        // Check if the auction is still active
        if (now()->gt($product->auction_end_date)) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.auction_ended'),
            ], 200);
        }
        $high_bid_amount = $product->latestBid()->amount ?? 0;

        $validator = \Validator::make($request->all(), [
            'bid_amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($product) {
                    // Get the latest bid or default to the stock price if no bid exists
                    $latestBidAmount = $product->latestBid() ? $product->latestBid()->amount : $product->stocks[0]?->price;
        
                    // Set the minimum bid amount
                    $minBidAmount = $latestBidAmount + 1;
        
                    // Check if the bid is lower than the minimum required amount
                    if ($value < $minBidAmount) {
                        $fail(trans('messages.bid_min_msg') . ' ' . env('DEFAULT_CURRENCY').' ' . $minBidAmount . '.');
                    }
                },
            ],
        ], [
            'bid_amount.required' => trans('messages.bid_required'),
            'bid_amount.numeric' => trans('messages.enter_valid_bid_msg'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->get('bid_amount'),
            ], 200);  
        }

        if ($request->bid_amount > $high_bid_amount) {
            // Store the bid
            $bid = new Bid();
            $bid->user_id = auth()->id();
            $bid->product_id = $product->id;
            $bid->amount = $request->bid_amount;
            $bid->save();

            $productStock = ProductStock::find($product->stocks[0]?->id);
            $productStock->high_bid_amount = $request->bid_amount;
            $productStock->save();

        }

        return response()->json([
            'status' => true,
            'message' => trans('messages.bid_success'),
            'new_bid' => $request->bid_amount
        ], 200);
    }

    
}
