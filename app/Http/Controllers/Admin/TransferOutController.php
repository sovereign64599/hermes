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
                $img = empty($item->item_photo) ? asset('img/default_item_photo.png') : asset('img/item_photo/'.$item->item_photo.'');
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
            'errors' => '<img class="w-25" src="'.asset('img/no-item.svg').'" alt="No Item">',
        ], 404);
    }

    public function getCart()
    {
        $carts = Cart::orderBy('created_at', 'desc')->get();
        if($carts->count() > 0){
            $totalAmount = 0;
            $html = '';
            foreach($carts as $cart){
                $totalAmount = $totalAmount + ((int) $cart->cart_quantity * (int) $cart->cart_sell);
                $img = empty($cart->cart_photo) ? asset('img/default_item_photo.png') : asset('img/item_photo/'.$cart->cart_photo.'');
                $html .= '<div class="card mb-2 order-item">';
                $html .= '<div class="card-body">';
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-2">';
                $html .= '<img class="img-fluid" src="'.$img.'" alt="'.$cart->cart_name.'">';
                $html .= '</div>';
                $html .= '<div class="col-lg-6 d-flex flex-column">';
                $html .= '<small>'.$cart->cart_name.'</small>';
                $html .= '<small>'.$cart->cart_sell.'</small>';
                $html .= '</div>';
                $html .= '<div class="col-lg-2 d-flex align-items-center justify-content-end">';
                $html .= '<input type="number" value="'.$cart->cart_quantity.'" class="form-control ch-input">';
                $html .= '</div>';
                $html .= '<div class="col-lg-2 d-flex align-items-center justify-content-end">';
                $html .= '<button class="btn">';
                $html .= '<i class="fas fa-trash text-light"></i>';
                $html .= '</button>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            return response()->json([
                'data' => $html,
                'totalAmount' => $totalAmount,
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
        ], 404);
    }

    public function getItemQuantity($id)
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

    public function addItemToCart(Request $request)
    {
        $itemID = $request->data;
        // get the item where id is equal to item id
        $itemData = Items::where('id', $itemID)->firstOrFail();
        if($itemData){
            // check if cart is not equal to 10 else return a message "cart limit to 10"
            if(Cart::count() != 10){
                // check if item is already exist in cartthen increment the cart quantity to 1 else add it to cart
                if($itemData->item_quantity == 0){
                    return response()->json([
                        'errors' => $itemData->item_name . ' is out of stock.',
                    ], 422);
                }
                if(Cart::where('item_id', $itemID)->exists()){
                    Cart::where('item_id', $itemID)->increment('cart_quantity', 1);
                    $itemData->decrement('item_quantity', 1);
                    return response()->json([
                        'success' => $itemData->item_name . ' quantity incremented',
                    ], 200);
                }else{
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
                            'success' => $itemData->item_name . ' added to cart',
                        ], 200);
                    }else{
                        return response()->json([
                            'errors' => "Connection lost.",
                        ], 422);
                    }
                }
            }
        }
    }
}
