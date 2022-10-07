<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Items;
use Illuminate\Http\Request;

class TransferOutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('admin.items.transfer-out');
    }

    public function showItems()
    {
        $items = Items::orderBy('created_at', 'desc')->get();
        if($items->count() > 0){
            $html = '';
            foreach($items as $item){
                $img = empty($item->item_photo) ? asset('img/default_item_photo.jpg') : asset('img/item_photo/'.$item->item_photo.'');
                $qty = $item->item_quantity === 0 ? '<span class="text-danger">Out of Stock</span>' : 'Available: '.$item->item_quantity.'';
                $html .= '<div class="col-xl-3 col-lg-4">';
                $html .= '<div class="card text-center">';
                $html .= '<div class="card-header">';
                $html .= '<img class="t-o-item-img img-fluid" src="'.$img.'" alt="test">';
                $html .= '</div>';
                $html .= '<div class="card-body p-2 pb-4">';
                $html .= '<h4 class="text-tertiary fw-bolder">$'.number_format($item->item_sell).'</h4>';
                $html .= '<p class="text-light">'.$item->item_name.'</p>';
                $html .= '<div class="d-flex align-items-center justify-content-center gap-2">';
                $html .= '<small>Bar code: '.$item->item_barcode.'</small>';
                $html .= '<small>|</small>';
                $html .= '<small class="item-qty-'.$item->id.'">'.$qty.'</small>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="card-footer d-flex align-items-center justify-content-center border-0 p-0">';
                $html .= '<button data="'.$item->id.'" class="btn-block h-100 mt-0 border-0 bg-transparent text-light p-1" onclick="viewItem(this)"><small>View</small></button><span></span>';
                $html .= '<button data="'.$item->id.'" class="btn-block h-100 mt-0 border-0 bg-transparent text-light p-1" onclick="addCart(this)"><small>Add Cart</small></button>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => '<img class="w-25" src="'.asset('img/undraw_rocket.svg').'" alt="No Item">',
        ], 404);
    }

    public function getCart()
    {
        $carts = Cart::orderBy('created_at', 'desc')->get();
        if($carts->count() > 0){
            $html = '';
            foreach($carts as $cart){
                $img = empty($cart->cart_photo) ? asset('img/default_item_photo.jpg') : asset('img/item_photo/'.$cart->cart_photo.'');
                $html .= '<div class="card mb-2 order-item">';
                $html .= '<div class="card-body">';
                $html .= '<div class="row align-items-center justify-content-center">';
                $html .= '<div class="col-lg-2">';
                $html .= '<img class="img-fluid" src="'.$img.'" alt="'.$cart->cart_name.'">';
                $html .= '</div>';
                $html .= '<div class="col-lg-5 d-flex flex-column pl-0">';
                $html .= '<small>'.$cart->cart_name.'</small>';
                $html .= '<small>$'.number_format($cart->cart_sell).'</small>';
                $html .= '</div>';
                $html .= '<div class="col-lg-3 d-flex align-items-center justify-content-end">';
                $html .= '<input type="number" role="button" value="'.$cart->cart_quantity.'" class="form-control ch-input" data="'.$cart->item_id.'" onchange="upadateQuantity(this)">';
                $html .= '</div>';
                $html .= '<div class="col-lg-2 d-flex align-items-center justify-content-end">';
                $html .= '<button role="button" type="button" class="btn" data="'.$cart->id.'" onclick="deleteCart(this)">';
                $html .= '<i class="fas fa-trash text-light"></i>';
                $html .= '</button>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }

        $empty = '<div class="card mb-2 order-item">';
        $empty .= '<div class="card-body d-flex justify-content-center align-items-center gap-3">';
        $empty .= '<div><i class="fas fa-shopping-cart"></i></div>';
        $empty .= '<div><span>Cart is Empty</span></div>';
        $empty .= '</div>';
        $empty .= '</div>';
        return response()->json([
            'errors' => $empty,
        ], 410);
    }

    public function getItemQuantity(String $id)
    {
        if($itemData = Items::where('id', $id)->firstOrFail()){
            if($itemData->item_quantity == 0){
                return response()->json([
                    'quantity' => '<span class="text-danger">Out of Stock</span>',
                ], 200);
            }
            return response()->json([
                'quantity' => 'Available: ' . $itemData->item_quantity,
            ], 200);
        }
        
    }

    public function getCartTotalAmount()
    {
        $carts = Cart::get();
        $totalAmount = 0;
        foreach($carts as $cart){
            $totalAmount = $totalAmount + ((float)$cart->cart_sell * (float)$cart->cart_quantity);
        }
        return response()->json([
            'totalAmount' => $totalAmount,
        ], 200);
    }

    public function updateCartQuantity(Request $request)
    {
        if($cart = Cart::where('item_id', $request->id)->first()){

            if($request->quantity > $cart->cart_quantity){
                $qty = (float)$request->quantity - (float)$cart->cart_quantity;
            }else{
                $qty =  (float)$cart->cart_quantity -(float)$request->quantity;
            }

            dd((int)$qty + (int)$request->quantity);

            // $cart->update(['cart_quantity' => $qty]);
            // $item = Items::where('id', $request->id)->first();

            // $item->decrement('item_quantity', $qty);


            return response()->json([
                'success' => $cart->cart_name . 'is updated.',
            ], 200);
        }
        return response()->json([
            'errors' => 'Something went wrong.',
        ], 404);
    }

    public function addItemToCart(Request $request)
    {
        $itemID = $request->data;
        // get the item where id is equal to item id
        $itemData = Items::where('id', $itemID)->firstOrFail();
        if($itemData){
                // check if item is already exist in cart then increment the cart quantity to 1 else add it to cart 
                if($itemData->item_quantity == 0){
                    return response()->json([
                        'errors' => $itemData->item_name . ' is out of stock.',
                    ], 422);
                }
                $itemData->decrement('item_quantity', 1);
                if(Cart::where('item_id', $itemID)->exists()){
                    Cart::where('item_id', $itemID)->increment('cart_quantity', 1);
                    return response()->json([
                        'success' => $itemData->item_name . ' quantity incremented',
                    ], 200);
                }else{
                    if(Cart::count() < 10){
                        $addCart = Cart::create([
                            'item_id' => $itemData->id,
                            'cart_name' => $itemData->item_name,
                            'cart_category' => $itemData->item_category,
                            'cart_sub_category' => $itemData->item_sub_category,
                            'cart_quantity' => 1,
                            'cart_barcode' => $itemData->item_barcode,
                            'cart_description' => $itemData->item_description,
                            'cart_cost' => $itemData->item_cost,
                            'cart_sell' => $itemData->item_sell,
                            'cart_notes' => $itemData->item_notes,
                            'cart_photo' => $itemData->item_photo
                        ]);
                        if($addCart){
                            return response()->json([
                                'success' => $itemData->item_name . ' Added to cart',
                            ], 200);
                        }else{
                            return response()->json([
                                'errors' => "Something went wrong.",
                            ], 422);
                        }
                    }
                    return response()->json([
                        'errors' => "Cart limit to " . Cart::count(),
                    ], 410);
                }
            
        }
    }

    public function deleteCart(String $id)
    {
        if($cart = Cart::where('id', $id)->first()){
            $cart->delete();
            return response()->json([
                'message' => 'Item Deleted.',
            ], 200);
        }
        return response()->json([
            'errors' => $id .' Not Found.',
        ], 404);
    }
}
