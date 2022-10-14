<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Items;
use App\Models\SubCategory;
use App\Models\TransferInRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferInController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $sessionList = session()->get('itemList', []);
        session()->put('itemList', $sessionList);
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

    public function collectData(String $id)
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
            'error' => 'something went wrong.',
        ], 410);
    }

    public function addList(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'addedQty' => 'required|numeric|min:1',
        ],
        [
            'addedQty.required' => 'Quantity is required.',
            'addedQty.numeric' => 'Quantity must be a digit.',
        ]);

        $items = Items::find($request->id);
        if($items){
            $sessionList = session()->get('itemList', []);
            if(count($sessionList) != 10){
                if(isset($sessionList[$request->id])){
                    return response()->json([
                        'error' => $items->item_name . ' already listed.'
                    ], 409);
                }
    
                $sessionList[$request->id] = [
                    'id' => $request->id,
                    'name' => $items->item_name,
                    'category' => $items->item_category,
                    'subCategory' => $items->item_sub_category,
                    'barcode' => $items->item_barcode,
                    'cost' => $items->item_cost,
                    'sell' => $items->item_sell,
                    'quantity' => $items->item_quantity,
                    'addedQty' => $request->addedQty,
                    'notes' => $request->notes,
                ];
    
                session()->put('itemList', $sessionList);
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

    public function getList()
    {
        $sessionList = session('itemList');
        if(!empty($sessionList)){
            $html = '';
            foreach ($sessionList as $list) {
                $html .= '<tr>';
                $html .= '<td>'.$list['name'].'</td>';
                $html .= '<td>'.$list['category'].'</td>';
                $html .= '<td>'.$list['subCategory'].'</td>';
                $html .= '<td>'.$list['barcode'].'</td>';
                $html .= '<td>'.$list['quantity'].'</td>';
                $html .= '<td class="text-warning">'.$list['addedQty'].'</td>';
                $html .= '<td>';
                $html .= '<button type="button" role="button" class="btn btn-sm text-light" data="'.$list['id'].'" onclick="deleteList(this)"><i class="fas fa-trash fa-sm"></i></button>';
                $html .= '</td>';
                $html .= '</tr>';
            }
            return response()->json([
                'data' => $html,
                'limit' => 'Item ('.count($sessionList).'/10)',
            ], 200);
        }
        return response()->json([
            'error' => '<td>No List Found</td>',
            'limit' => 'Item (0/10)',
        ], 410);
    }

    public function deleteList($id)
    {
        $list = session()->get('itemList');
        if(isset($list[$id])) {
            unset($list[$id]);
            session()->put('itemList', $list);
            return response()->json([
                'message' => 'list Deleted.'
            ], 200);
        }
        return response()->json([
            'error' => 'list not Found.'
        ], 404);
    }

    public function submitList(Request $request)
    {
        $request->validate([
            '_token' => 'required',
            'form_number' => 'required',
            'custom_date' => 'required',
        ]);

        $lists = session()->get('itemList');
        $user_who_tranfer = Auth::user()->name;
        $user_id_who_tranfer = Auth::id();
        $form_number = $request->form_number;
        $custom_date = $request->custom_date;
        $listCount = count($lists);
        if(count($lists) > 0){
            foreach($lists as $list){
                $data = Items::find($list['id']);
                if($data->exists()){
                    //increment quantity here
                    Items::where('id', $data->id)->increment('item_quantity', $list['addedQty']);
                    // store items here
                    TransferInRecord::create([
                        'user_id' => $user_id_who_tranfer,
                        'user_name' => $user_who_tranfer,
                        'item_name' => $data->item_name,
                        'item_category' => $data->item_category,
                        'item_sub_category' => $data->item_sub_category,
                        'item_quantity' => $data->item_quantity,
                        'item_quantity_added' => $list['addedQty'],
                        'item_description' => $data->item_description,
                        'item_barcode' => $data->item_barcode,
                        'item_description' => $data->item_name,
                        'item_cost' => (float)$data->item_cost,
                        'item_sell' => (float)$data->item_sell,
                        'item_photo' => $data->item_photo,
                        'form_number' => $form_number,
                        'custom_date' => $custom_date,
                        'notes' => $list['notes']
                    ]);
                    unset($lists[$list['id']]);
                }
            }
            session()->put('itemList', $lists);
            return response()->json([
                'message' => $listCount . ' Item transfered In by ' . $user_who_tranfer
            ], 200);
        }
        return response()->json([
            'error' => 'your list is empty'
        ], 410);
    }
}
