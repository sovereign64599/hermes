<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $html = '';
        foreach($items as $item){
            $img = empty($item->item_photo) ? asset('img/default_item_photo.png') : asset('img/item_photo/'.$item->item_photo.'');
            $html .= '<div class="col-lg-3">';
            $html .= '<div class="card text-center">';
            $html .= '<div class="card-header">';
            $html .= '<img class="t-o-item-img img-fluid" src="'.$img.'" alt="test">';
            $html .= '</div>';
            $html .= '<div class="card-body p-2 pb-4">';
            $html .= '<h6>$'.$item->item_sell.'</h6>';
            $html .= '<p>'.$item->item_name.'</p>';
            $html .= '<p>'.$item->item_description.'</p>';
            $html .= '<small><strong>Bar Code</strong>: '.$item->item_barcode.'</small>';
            $html .= '</div>';
            $html .= '<div class="card-footer d-flex align-items-center justify-content-center border-0 p-0">';
            $html .= '<button class="btn-block h-100 mt-0 border-0 bg-transparent text-light p-1">View</button><span></span>';
            $html .= '<button class="btn-block h-100 mt-0 border-0 bg-tertiary text-light p-1">Add Item</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return response(json_encode(['status' => 200, 'data' => $html]));
    }
}
