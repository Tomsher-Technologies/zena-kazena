<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use DB;
use Auth;

class WishlistController extends Controller
{

    public function index(Request $request)
    {
        $lang = getActiveLanguage();
        $result = [];
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        if($user_id != ''){
            $wishlist = Wishlist::with('product','product_stock')->where('user_id', $user_id)->get();
           
            if($wishlist){
                foreach($wishlist as $data){
                    if($data->product && $data->product_stock){
                        $priceData = getProductPrice($data->product_stock);

                        $result[] = [
                            'id' => (int) $data->id,
                            'product' => [
                                'variant_id' => $data->product_stock->id ?? '',
                                'product_id' => $data->product_id ?? '',
                                'name' => $data->product->getTranslation('name', $lang) ?? '',
                                'sku' => $data->product_stock->sku ?? '',
                                'slug' => $data->product->slug ?? '',
                                'thumbnail_image' => ($data->product_stock->image != NULL && $data->product_stock->image != '0') ? get_product_image($data->product_stock->image,'300') : get_product_image($data->product->thumbnail_img,'300'),
                                'stroked_price' => $priceData['discounted_price'] ?? 0,
                                'main_price' => $priceData['original_price'] ?? 0,
                                'min_qty' => $data->product->min_qty ?? 0,
                                'quantity' => $data->product_stock->qty ?? 0,
                                'offer_tag' => $priceData['offer_tag'] ?? '',
                                'attributes' => getProductAttributes($data->product_stock->attributes)
                            ]
                        ];
                    }
                }    
            }
        }
        // echo '<pre>';
        // print_r($result);
        // die;

        return view('frontend.wishlists',compact('lang','result','wishlist'));
    }

    public function store(Request $request)
    {
        $product_slug   = $request->productSlug;
        $sku            = $request->productSku ;
        $user_id = (!empty(auth()->user())) ? auth()->user()->id : '';
        if($user_id != ''){
            // DB::enableQueryLog();
            $variantProduct = ProductStock::leftJoin('products as p','p.id','=','product_stocks.product_id')
                                    ->where('product_stocks.sku', $sku)
                                    ->where('p.slug', $product_slug)
                                    ->select('product_stocks.*')->first()?->toArray();
                                    // dd(DB::getQueryLog());
                            
            if(!empty($variantProduct)){
                $product_id         = $variantProduct['product_id'] ?? null;
                $product_stock_id   = $variantProduct['id'] ?? null;
                if($product_id != null &&  $product_stock_id != null){
                    // Check if product already exist in wishlist
                    $checkWhishlist =   Wishlist::where('user_id',$user_id)
                                                ->where('product_id',$product_id)
                                                ->where('product_stock_id',$product_stock_id)->count();

                    if($checkWhishlist != 0){
                        $status = 0;
                        Wishlist::where('user_id',$user_id)->where('product_id',$product_id)
                                ->where('product_stock_id',$product_stock_id)->delete();
                    }else{
                        $status = 1;
                        Wishlist::create([
                                'user_id' => $user_id,
                                'product_id' => $product_id,
                                'product_stock_id' => $product_stock_id
                        ]);
                    }
                    return response()->json([
                        'status' => true,
                        'wishlist_count' => $this->getWishlistCount($user_id),
                        'message' => ($status == 1) ? trans('messages.wishlist_product_added') : trans('messages.wishlist_product_removed'),
                        'wishlist_status' => $status
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' =>  trans('messages.something_went_wrong')
                    ], 200);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' =>  trans('messages.something_went_wrong')
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => trans('messages.login_msg')
            ], 200);
        }
        
        
    }

    public function getWishlistCount($user)
    {
        return Wishlist::where([
            'user_id' => $user
        ])->count();
    }

    public function getCount(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'wishlist_count' => $this->getWishlistCount($request->user()->id),
        ], 200);
    }

    public function destroy($id)
    {
        try {
            Wishlist::destroy($id);
            return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your wishlist')], 200);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 200);
        }

    }


    public function delete(Request $request)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())
                                ->where('id', $request->id)
                                ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json(['success' => true, 'message' => trans('messages.wishlist_product_removed')]);
        }

        return response()->json(['success' => false, 'message' => trans('messages.item_not_found')], 404);
    }

}
