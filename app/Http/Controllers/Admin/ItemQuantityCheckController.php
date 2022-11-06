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
        return view('admin.items.item-quantity-check', compact(['getSubCategories']));
    }

    public function filterItemsAvailable(Request $request)
    {

        $items = Items::when($request->code, function ($query, $code) {
            return $query->where('item_barcode', 'like', "%{$code}%");
        })->when($request->description, function ($query, $description) {
            return $query->where('item_description', 'like', "%{$description}%");
        })->when($request->description && $request->code, function ($query) use ($request) {
            return $query->where('item_description', 'like', '%'.$request->description.'%')->where('item_barcode', 'LIKE', '%'.$request->code.'%');
        })->when($request->category && $request->subCategory, function ($query) use ($request) {
            return $query->where('item_category', 'like', '%'.$request->category.'%')->where('item_sub_category', 'LIKE', '%'.$request->subCategory.'%');
        })->when($request->category && $request->subCategory && $request->code, function ($query) use ($request) {
            return $query->where('item_category', $request->category)->where('item_sub_category', $request->subCategory)->where('item_barcode', 'LIKE', '%'.$request->code.'%');
        }, function ($query) {
            return $query->orderByDesc('updated_at');
        })->paginate(10, ['*'], 'page', $request->page);

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
                $html .='<td>'.number_format((float)$item->item_cost, 2).'</td>';
                $html .='<td>'.number_format((float)$item->item_sell, 2).'</td>';
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
                'pagination' => $items
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
