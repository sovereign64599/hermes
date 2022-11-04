<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Items;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $deliveries = Delivery::all();
        if(isset($_GET['form_number'])){
            $deliveries = Delivery::where('form_number', $_GET['form_number'])->get();
        }
        
        $groupFormNumber = Delivery::select('form_number')->groupBy('form_number')->get();
        return view('admin.delivery', compact(['deliveries', 'groupFormNumber']));
    }

    public function collectFormNumber(Request $request)
    {
        if(isset($request->_token) && isset($request->form_number)){
            $formNumbers = Delivery::select('form_number')->groupBy('form_number')->where('form_number', 'like', '%'.$request->form_number.'%')->get();
        }else{
            $formNumbers = Delivery::select('form_number')->groupBy('form_number')->get();
        }

        if($formNumbers->count() > 0){
            $html = '';
            foreach($formNumbers as $row){
                $html .= '<a href="?form_number='. $row->form_number.'">';
                $html .= '<li '. (isset($_GET['form_number']) && $_GET['form_number'] == $row->form_number ? 'class="active"' : ' ') .'># '. $row->form_number .'</li>';
                $html .= '</a>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => 'No Form found',
        ], 410);
    }

    //Item 4b57 Item e4c8 Item 4832

    public function getDeliveryCount()
    {
        $count = Delivery::count();

        return response()->json([
            'count' => $count,
        ], 200);
    }

    // delivery actions
    public function updateToDelivered($id)
    {
        $delivery = Delivery::find($id);
        if($delivery){
            $delivery->update([
                'delivery_status' => 'Delivered'
            ]);

            $total = $delivery->totalAmount_discounted;

            $storeSales = Sales::create([
                'sales_amount' => $total,
                'transaction_number' => $delivery->form_number,
                'custom_date' => $delivery->custom_date,
                'proccessed_by' => $delivery->user_name,
            ]);
            if($storeSales){
                return back()->with('success', $total . ' Added to your sales.');
            }
        }
    }

    public function updateToForDeliver($id)
    {
        $delivery = Delivery::find($id);
        if($delivery){

            $items = Items::where('id', $delivery->item_id)->decrement('item_quantity', (int)$delivery->item_quantity_deduct);
            if($items){
                $delivery->update([
                    'delivery_status' => 'For Delivery'
                ]);
                return back()->with('success', 'Item is now For Delivery');
            }
        }
    }
    // 06040256 04060659	 12010256

    public function updateToCancelled($id)
    {
        $delivery = Delivery::find($id);
        if($delivery){
            $delivery->update([
                'delivery_status' => 'Cancelled'
            ]);

            $updateItem = Items::where('id', $delivery->item_id)->increment('item_quantity', (int)$delivery->item_quantity_deduct);
            if($updateItem){
                return back()->with('success', 'Item Cancelled.');
            }
        }
    }
}
