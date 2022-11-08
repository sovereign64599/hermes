<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Items;
use App\Models\Sales;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        // set a session for sales item
        $sessionList = session()->get('salesItem', []);
        session()->put('salesItem', $sessionList);
        $discount = session()->get('salesItemDiscount', ['discount' => 0]);
        session()->put('salesItemDiscount', $discount);

        $collection = collect(SubCategory::get());
        $unique = $collection->unique(['category_name']);
        $unique->values()->all();
        $getSubCategories = $unique;
        $items = Items::orderBy('created_at', 'desc')->paginate(3);
        return view('admin.sales', compact(['getSubCategories', 'items']));
    }

    public function collectItem($input, Items $itemsResult){

        $search = $itemsResult->newQuery();

        $search->where(function($query) use($input) {
            $query->where('item_name', 'LIKE', '%'.$input.'%')->orWhere( 'item_description', 'LIKE', '%'.$input.'%')->orWhere('item_barcode', 'LIKE', '%'.$input.'%');
        });

        $items = $search->get();

        if($items->count() > 0){
            $html = '';
            foreach($items as $item){
                $html .= '<li data="'.$item->id.'" onclick="setValue(this)">'.$item->item_name.'</li>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => '<li>No Item Found</li>',
        ], 410);
    }

    public function collectItemData(String $id)
    {
        $collect = Items::find($id);
        if ($collect) {
            $data = [
                'category' => $collect->item_category,
                'subCategory' => $collect->item_sub_category,
                'barcode' => $collect->item_barcode,
                'cost' => $collect->item_cost,
                'sell' => $collect->item_sell,
                'quantity' => $collect->item_quantity,
                'description' => $collect->item_description,
            ];
            return response()->json([
                'data' => $data,
            ], 200);
        }
        return response()->json([
            'error' => 'Something went wrong.',
        ], 410);
    }

    public function addSalesList(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'deductQty' => 'required|numeric',
            'price' => 'required|numeric',
        ],
        [
            'deductQty.required' => 'Quantity is required.',
            'deductQty.numeric' => 'Quantity must be a digit.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a digit.',
        ]);

        $items = Items::find($request->id);
        if($items){
            $sessionList = session()->get('salesItem', []);
            if(count($sessionList) != 10){
                if(isset($sessionList[$request->id])){
                    return response()->json([
                        'error' => $items->item_name . ' already listed.'
                    ], 409);
                }

                // if($request->deductQty > $items->item_quantity){
                //     return response()->json([
                //         'error' => 'Current Quantity must be greater than Deduct Quantity.'
                //     ], 409);
                // }
    
                $sessionList[$request->id] = [
                    'id' => $request->id,
                    'name' => $items->item_name,
                    'category' => $items->item_category,
                    'subCategory' => $items->item_sub_category,
                    'barcode' => $items->item_barcode,
                    'sell' => $items->item_sell,
                    'quantity' => $items->item_quantity,
                    'deductQty' => $request->deductQty,
                    'amount' => (int)$items->item_sell * (int)$request->deductQty,
                    'delivery_status' => 'Delivered',
                ];
    
                session()->put('salesItem', $sessionList);
                return response()->json([
                    'message' => $items->item_name . ' added to list.'
                ], 200);
            }
            return response()->json([
                'error' => 'The Maximum lists is 10.'
            ], 404);
        }else{
            return response()->json([
                'error' => 'Item not found, please try again.'
            ], 404);
        }
    }

    public function getSalesList()
    {
        $sessionList = session('salesItem');
        $discount = session('salesItemDiscount');
        $percent = $discount['discount'];
        $totalAmountDiscounted = 0;

        if(!empty($sessionList)){
            $totalAmount = 0;
            $html = '';
            foreach ($sessionList as $list) {
                $totalAmount = $totalAmount + ((float)$list['sell'] * (int)$list['deductQty']);
                $totalAmountDiscounted = (float)$totalAmount - ((float)$totalAmount * ((int)$percent / 100));

                $delivery_status = $list['delivery_status'] == 'For Delivery' ? 'checked value="For Delivery"' : 'value="Delivered"';

                $html .= '<tr>';
                $html .= '<td>'.$list['name'].'</td>';
                $html .= '<td>'.$list['barcode'].'</td>';
                $html .= '<td>'.$list['sell'].'</td>';
                $html .= '<td class="text-info">'.$list['deductQty'].'</td>';
                $html .= '<td class="text-warning">'.$list['amount'].'</td>';
                $html .= '<td>';
                $html .= '<button type="button" role="button" class="btn btn-sm text-light" data="'.$list['id'].'" onclick="deleteSalesList(this)"><i class="fas fa-trash fa-sm"></i></button>';
                $html .= '</td>';
                $html .= '<td>';
                $html .= '<input type="checkbox" data="'.$list['id'].'" name="delivery" '.$delivery_status.' onchange="updateDeliveryStatus(this)">';
                $html .= '</td>';
                $html .= '</tr>';
            }
            return response()->json([
                'data' => $html,
                'limit' => 'Item ('.count($sessionList).'/10)',
                'totalAmount' => number_format($totalAmount, 2),
                'discount' => $percent,
                'totalAmountDiscounted' => number_format($totalAmountDiscounted, 2),
            ], 200);
        }
        return response()->json([
            'error' => '<td>No List Found</td>',
            'limit' => 'Item (0/10)',
            'totalAmount' => 00.00,
            'discount' => $percent,
            'totalAmountDiscounted' => number_format($totalAmountDiscounted, 2),
        ], 410);
    }

    public function updateDiscount($value)
    {
        if(Auth::user()->role == 'Admin'){
            $sessionList = session('salesItem');
            if(count($sessionList) > 0){
                if((int)$value > 100 || (int)$value < 0){
                    return response()->json([
                        'error' => 'Discount must be between 0 - 100'
                    ], 422);
                }
                $discount = session()->get('salesItemDiscount');
                if(isset($discount)) {
                    $discount['discount'] = (int)$value;
                    session()->put('salesItemDiscount', $discount);
                    return response()->json([
                        'message' => $value . '% Discount applied'
                    ], 200);
                }
                return response()->json([
                    'error' => 'Something went wrong.'
                ], 404);
            }
            return response()->json([
                'error' => 'Add item to apply discount'
            ], 422);
        }
        return response()->json([
            'error' => 'Fobidden'
        ], 422);
    }

    public function updateDeliveryStatus($id)
    {
        $list = session()->get('salesItem');
        if(isset($list[$id])) {
            if($list[$id]['delivery_status'] == 'Delivered'){
                $list[$id]['delivery_status'] = 'For Delivery';
            }else{
                $list[$id]['delivery_status'] = 'Delivered';
            }
            session()->put('salesItem', $list);
            return response()->json([
                'message' => 'Delivery status updated.'
            ], 200);
        }
        return response()->json([
            'error' => 'Something went wrong.'
        ], 404);
    }

    public function deleteSalesList($id)
    {
        $list = session()->get('salesItem');
        if(isset($list[$id])) {
            unset($list[$id]);
            session()->put('salesItem', $list);
            return response()->json([
                'message' => 'List Deleted.'
            ], 200);
        }
        return response()->json([
            'error' => 'List not Found.'
        ], 404);
    }

    public function submitSalesList(Request $request)
    {
        $request->validate([
            '_token' => 'required',
            'form_number' => 'required',
            'custom_date' => 'required',
        ]);

        $lists = session()->get('salesItem');
        $discount = session('salesItemDiscount');
        $percent = $discount['discount'];

        $user_who_tranfer = Auth::user()->name;
        $user_id_who_tranfer = Auth::id();
        $form_number = $request->form_number;
        $custom_date = $request->custom_date;

        $totalAmountDiscounted = 0;
        $totalAmount = 0;
        $listCount = count($lists);
        if(count($lists) > 0){
            foreach($lists as $list){
                $data = Items::find($list['id']);
                $totalAmount = ((float)$list['sell'] * (int)$list['deductQty']);
                $totalAmountDiscounted = (float)$totalAmount - ((float)$totalAmount * ((int)$percent / 100));

                if($data->exists()){
                    if($list['delivery_status'] != 'For Delivery'){
                        Items::where('id', $data->id)->update([
                            'item_quantity' => (int)$data->item_quantity - (int)$list['deductQty'],
                        ]);
                        
                        Sales::create([
                            'sales_amount' => $totalAmountDiscounted,
                            'transaction_number' => $form_number,
                            'custom_date' => $custom_date,
                            'proccessed_by' => $user_who_tranfer,
                        ]);
                    }

                    // store items here
                    Delivery::create([
                        'user_id' => $user_id_who_tranfer,
                        'user_name' => $user_who_tranfer,
                        'item_id' => $data->id,
                        'item_name' => $data->item_name,
                        'item_category' => $data->item_category,
                        'item_sub_category' => $data->item_sub_category,
                        'item_quantity_deduct' => $list['deductQty'],
                        'item_description' => $data->item_description,
                        'item_barcode' => $data->item_barcode,
                        'item_price' => $list['sell'],
                        'total_amount' => $list['amount'],
                        'form_number' => $form_number,
                        'custom_date' => $custom_date,
                        'delivery_status' => $list['delivery_status'],
                        'totalAmount_discounted' => $totalAmountDiscounted
                    ]);

                    unset($lists[$list['id']]);
                }
            }
            if(Auth::user()->role == 'Admin'){
                $discount['discount'] = 0;
                session()->put('salesItemDiscount', $discount);
            }
            
            session()->put('salesItem', $lists);
            return response()->json([
                'message' => $listCount . ' items being processed.'
            ], 200);
        }
        return response()->json([
            'error' => 'Your list is empty'
        ], 410);
    }
}
