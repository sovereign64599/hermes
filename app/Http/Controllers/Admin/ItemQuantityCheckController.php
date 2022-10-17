<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemQuantityCheckController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $collection = collect(SubCategory::get());
        $unique = $collection->unique(['category_name']);

        $unique->values()->all();
        $getSubCategories = $unique;
        $items = Items::orderBy('created_at', 'desc')->paginate(3);
        return view('admin.items.item-quantity-check', compact(['getSubCategories', 'items']));
    }

    public function filterItemsAvailable(Request $request)
    {

        if(isset($request->category) && isset($request->subCategory) && isset($request->code)){
            $items = Items::where('item_category', $request->category)->where('item_sub_category', $request->subCategory)->where('item_barcode', 'like', '%'.$request->code.'%')->orWhere('item_description', 'like', '%'.$request->code.'%')->orderBy('updated_at', 'desc')->get();
        }else if(isset($request->category) && isset($request->subCategory)){
            $items = Items::where('item_category', $request->category)->where('item_sub_category', $request->subCategory)->orderBy('updated_at', 'desc')->get();
        }else if(isset($request->code)){
            $items = Items::where('item_barcode', 'like', '%'.$request->code.'%')->orWhere('item_description', 'like', '%'.$request->code.'%')->orderBy('updated_at', 'desc')->get();
        }else{
            $items = Items::orderBy('updated_at', 'desc')->get();
        }    

        if($items->count() > 0){
            $html = '';
            foreach($items as $item){
                $quantity = ($item->item_quantity == 0) ? '<small class="text-danger">Out of Stock</small>' : $item->item_quantity;
                $description = empty($item->item_description) ? 'No item Description' : $item->item_description;
                $html .= '<tr>';
                $html .='<td>'.$item->item_name.'</td>';
                $html .='<td>'.$item->item_category.'</td>';
                $html .='<td>'.$item->item_sub_category.'</td>';
                $html .='<td>'.$item->item_barcode.'</td>';
                $html .='<td>'.$item->item_cost.'</td>';
                $html .='<td>'.$item->item_sell.'</td>';
                $html .='<td>'.$quantity.'</td>';
                $html .='<td>'.mb_strimwidth($description, 0, 10, "...").'</td>';
                $html .='<td>';
                $html .='<div class="d-flex gap-1">';
                $html .='<a data="'.$item->id.'" onclick="viewItem(this)" class="btn bg-info btn-sm text-light"><i class="fas fa-eye fa-sm"></i></a>';
                $html .='</div>';
                $html .='</td></tr>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => '<tr><td>No Item available<td><tr>',
        ], 410);
    }
    
    public function checkSubCategory($id){
        $subCategories = SubCategory::where('category_id', $id)->get();
        if($subCategories->count() > 0){
            $html = '';
            foreach($subCategories as $subCategory){
                $html .= '<option value="'.$subCategory->sub_category_name.'">'.$subCategory->sub_category_name.'</option>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => '<option>No sub category found</option>',
        ], 404);
    }
}
