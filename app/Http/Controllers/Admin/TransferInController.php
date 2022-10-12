<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ItemsExport;
use App\Http\Controllers\Controller;
use App\Imports\ItemCategory;
use App\Imports\ItemImport;
use App\Imports\ItemSubCategory;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Items;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class TransferInController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $collection = collect(SubCategory::get());
        $unique = $collection->unique(['category_name']);

        $unique->values()->all();
        $getSubCategories = $unique;
        $items = Items::orderBy('created_at', 'desc')->paginate(3);
        return view('admin.items.transfer-in', compact(['getSubCategories', 'items']));
    }

    public function collectItemName($input){
        $items = Items::where('item_name', 'like', '%'.ucfirst($input).'%')
                ->orWhere('item_name', 'like', '%'.$input.'%')
                ->orWhere('item_name', 'like', '%'.strtolower($input).'%')->get();

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

    public function addList(Request $request)
    {
        dd($request->all());
    }
}
