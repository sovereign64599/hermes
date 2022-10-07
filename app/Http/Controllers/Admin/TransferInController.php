<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        // dd($checkCategories);
        return view('admin.items.transfer-in', compact(['getSubCategories']));
    }
    
    public function getItems(){
        $items = Items::orderBy('created_at', 'desc')->get();
        if($items->count() > 0){
            $html = '';
            foreach($items as $item){
                $quantity = ($item->item_quantity == 0) ? '<small class="text-danger">Out of Stock</small>' : $item->item_quantity;
                $img = empty($item->item_photo) ? asset('img/default_item_photo.jpg') : asset('img/item_photo/'.$item->item_photo.'');
                $html .= '<tr><td>';
                $html .='<a href="'. $img .'" target="_blank"><img class="img-fluid item-photo" src="'. $img .'" alt="'.$item->item_name.'"></a>';
                $html .='</td>';
                $html .='<td>'.$item->item_name.'</td>';
                $html .='<td>'.$item->item_category.'</td>';
                $html .='<td>'.$item->item_sub_category.'</td>';
                $html .='<td>'.$quantity.'</td>';
                $html .='<td>'.$item->item_barcode.'</td>';
                $html .='<td>'.$item->item_description.'</td>';
                $html .='<td>'.$item->item_cost.'</td>';
                $html .='<td>'.$item->item_sell.'</td>';
                $html .='<td>';
                $html .='<div class="d-flex gap-1">';
                $html .='<button data="'.$item->id.'" class="btn bg-secondary text-light btn-sm" onclick="editItem(this)"><i class="fas fa-pencil-alt mr-1 fa-sm"></i><small>Edit</small></button>';
                $html .='<button data="'.$item->id.'" class="btn text-light btn-sm" onclick="deleteItem(this)"><i class="fas fa-trash mr-1 fa-sm"></i><small>Delete</small></button>';
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

    public function checkItems(Request $request){
        $checkItem = Items::where('item_name', $request->item_name)
        ->where('item_category', $request->item_category)
        ->where('item_sub_category', $request->item_sub_category)
        ->where('item_barcode', $request->item_barcode)->first();
        if($checkItem){
            return true;
        }else{
            return false;
        }
    }

    public function filter($input){
        $items = Items::where('item_name', 'like', '%'.ucfirst($input).'%')
                ->orWhere('item_name', 'like', '%'.$input.'%')
                ->orWhere('item_name', 'like', '%'.strtolower($input).'%')
                ->orWhere('item_category', 'like', '%'.ucfirst($input).'%')
                ->orWhere('item_category', 'like', '%'.$input.'%')
                ->orWhere('item_sub_category', 'like', '%'.ucfirst($input).'%')
                ->orWhere('item_sub_category', 'like', '%'.$input.'%')
                ->orWhere('item_barcode', 'like', '%'.$input.'%')
                ->orderBy('created_at', 'desc')->get();
        if($items->count() > 0){
            $html = '';
            foreach($items as $item){
                $img = empty($item->item_photo) ? asset('img/default_item_photo.png') : asset('img/item_photo/'.$item->item_photo.'');
                $html .= '<tr><td>';
                $html .='<img class="img-fluid item-photo" src="'. $img .'" alt="img1">';
                $html .='</td>';
                $html .='<td>'.$item->item_name.'</td>';
                $html .='<td>'.$item->item_category.'</td>';
                $html .='<td>'.$item->item_sub_category.'</td>';
                $html .='<td>'.$item->item_quantity.'</td>';
                $html .='<td>'.$item->item_barcode.'</td>';
                $html .='<td>'.$item->item_description.'</td>';
                $html .='<td>'.$item->item_cost.'</td>';
                $html .='<td>'.$item->item_sell.'</td>';
                $html .='<td>';
                $html .='<div class="d-flex gap-1">';
                $html .='<button data="'.$item->id.'" class="btn text-light" onclick="editItem(this)">Edit</button>';
                $html .='<button data="'.$item->id.'" class="btn text-light" onclick="deleteItem(this)">Delete</button>';
                $html .='</div>';
                $html .='</td></tr>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => '<tr><td>No Item available</td></tr>',
        ], 410);
    }

    public function collectSubCategory($id){
        $subCategories = SubCategory::where('category_id', $id)->get();
        if($subCategories->count() > 0){
            $html = '';
            foreach($subCategories as $subCategory){
                $html .= '<option>'.$subCategory->sub_category_name.'</option>';
            }
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => '<option>No sub category found</option>',
        ], 404);
    }

    public function store(Request $request){
        $request->validate([
            'item_name' => 'required',
            'item_category' => 'required',
            'item_sub_category' => 'required',
            'item_quantity' => 'required|numeric',
            'item_barcode' => 'required|numeric',
            'item_description' => 'required|string',
            'item_cost' => 'required|numeric',
            'item_sell' => 'required|numeric',
            'item_notes' => 'max:300',
            'item_photo' => 'mimes:jpg,png',
        ]);

        $like = scandir('img/item_photo');
        foreach ($like as $thisFile) {
            $rs = Items::where('item_photo', $thisFile)->first();
            if (!$rs) {
                if($thisFile != "." and $thisFile != ".."){
                        unlink ('img/item_photo/' . $thisFile);
                }
            }
        }
        if($this->checkItems($request)){
            // return response(json_encode(['status' => 400, 'message' => ucfirst($request->item_name). ' exist. Update anyway.']));
            return response()->json([
                'error' => ucfirst($request->item_name). ucfirst($request->item_name). ' exist. Update anyway.',
            ], 409);
        }

        if(!empty($request->item_photo)){
            $item_photo = uniqid() . '.' . $request->item_photo->extension();
        }else{
            $item_photo = NULL;
        }
        $quantity = $request->item_quantity <= 0 ? 0 : $request->item_quantity;
        $barcode = empty($request->item_barcode) ? str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT) : $request->item_barcode;
        $storeItems = Items::create([
            'item_name' => ucfirst($request->item_name),
            'item_category' => ucfirst($request->item_category),
            'item_sub_category' => ucfirst($request->item_sub_category),
            'item_quantity' => $quantity,
            'item_barcode' => $barcode,
            'item_description' => ucfirst($request->item_description),
            'item_cost' => $request->item_cost,
            'item_sell' => $request->item_sell,
            'item_notes' => $request->item_notes,
            'item_photo' => $item_photo,
        ]);
        // str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT)
        if($storeItems){
            if(!empty($request->item_photo)){
                $request->item_photo->move(public_path('img/item_photo'), $item_photo);
            }
            return response()->json([
                'message' => ucfirst($request->item_name). ' Added Successfully.',
            ], 200);
        }
    }

    public function editItems(String $id){
        if($checkItem = Items::where('id', $id)->first()){
            $html = '<input type="hidden" name="_token" value="'.csrf_token().'">';
            $html .= '<input type="hidden" name="item_id" value="'.$checkItem->id.'">';
            $html .= '<div class="row">';
            $html .= '<div class="form-group">';
            $html .= '<input type="text" name="item_name" class="form-control" value="'.$checkItem->item_name.'" placeholder="Item Name">';
            $html .= '</div>';
            $html .= '<div class="col-lg-6">';
            $html .= '<div class="form-group">';
            $html .= '<select class="form-select" name="item_category">';
            $html .= '<option selected>'.$checkItem->item_category.'</option>';
            $html .= '<option value="1">One</option>';
            $html .= '<option value="2">Two</option>';
            $html .= '<option value="3">Three</option>';
            $html .= '</select></div></div>';
            $html .= '<div class="col-lg-6">';
            $html .= '<div class="form-group">';
            $html .= '<select class="form-select" name="item_sub_category">';
            $html .= '<option selected>'.$checkItem->item_sub_category.'</option>';
            $html .= '<option value="1">One</option>';
            $html .= '<option value="2">Two</option>';
            $html .= '<option value="3">Three</option>';
            $html .= '</select></div></div>';
            $html .= '<div class="col-lg-6">';
            $html .= '<div class="form-group">';
            $html .= '<input type="number" name="item_quantity" value="'.$checkItem->item_quantity.'" class="form-control" placeholder="Item Quantity">';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col-lg-6">';
            $html .= '<div class="form-group">';
            $html .= '<input type="number" name="item_barcode" value="'.$checkItem->item_barcode.'" class="form-control" placeholder="Item Bar code">';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col-lg-6">';
            $html .= '<div class="form-group">';
            $html .= '<input type="number" name="item_cost" value="'.$checkItem->item_cost.'" class="form-control" placeholder="Cost">';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col-lg-6">';
            $html .= '<div class="form-group">';
            $html .= '<input type="number" name="item_sell" value="'.$checkItem->item_sell.'" class="form-control" placeholder="Sell">';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<textarea name="item_description" value="'.$checkItem->item_description.'" rows="3" class="form-control" placeholder="Description">'.$checkItem->item_description.'</textarea>';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<label class="text-light" for="item_img">Item photo (Optional)</label>';
            $html .= '<input type="file" name="item_photo" class="form-control" id="item_img" placeholder="Cost">';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<label class="text-light">Add Notes (Optional)</label>';
            $html .= '<textarea name="item_notes" rows="3" class="form-control" value="No Notes" placeholder="Add notes">'.$checkItem->item_notes.'</textarea>';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<button type="submit" class="btn text-light">Update Items</button>';
            $html .= '</div>';
            $html .= '</div>';
            return response()->json([
                'data' => $html,
            ], 200);
        }
        return response()->json([
            'errors' => $id . ' not found',
        ], 404);
    }

    public function update(Request $request){
        $request->validate([
            'item_name' => 'required',
            'item_category' => 'required',
            'item_sub_category' => 'required',
            'item_quantity' => 'required|numeric|min:0',
            'item_barcode' => 'required|numeric',
            'item_description' => 'required|string',
            'item_cost' => 'required|numeric',
            'item_sell' => 'required|numeric',
            'item_notes' => 'max:300',
            'item_photo' => 'mimes:jpg,png',
        ]);

        $like = scandir('img/item_photo');
        foreach ($like as $thisFile) {
            $rs = Items::where('item_photo', $thisFile)->first();
            if (!$rs) {
                if($thisFile != "." and $thisFile != ".."){
                        unlink ('img/item_photo/' . $thisFile);
                }
            }
        }

        $item  = Items::where('id', $request->item_id)->first();

        if(!empty($request->item_photo)){
            $item_photo = uniqid() . '.' . $request->item_photo->extension();
        }else{
            $item_photo = $item->item_photo;
        }

        $quantity = ($request->item_quantity <= 0) ? 0 : $request->item_quantity;
        $checkUp = Items::where('id', $request->item_id)->firstOrFail();
        if($checkUp){
            $updateItem = $checkUp->update([
                'item_name' => ucfirst($request->item_name),
                'item_category' => ucfirst($request->item_category),
                'item_sub_category' => ucfirst($request->item_sub_category),
                'item_quantity' => $quantity,
                'item_barcode' => ucfirst($request->item_barcode),
                'item_description' => $request->item_description,
                'item_cost' => $request->item_cost,
                'item_sell' => $request->item_sell,
                'item_notes' => $request->item_notes,
                'item_photo' => $item_photo,
            ]);
    
            if($updateItem){
                if(!empty($request->item_photo)){
                    $request->item_photo->move(public_path('img/item_photo'), $item_photo);
                }
                return response(json_encode(['status' => 200, 'message' => ucfirst($request->item_name). ' Updated Successfully.']));
            }
        }
    }

    public function updateExistItem(Request $request){
        $request->validate([
            'item_name' => 'required',
            'item_category' => 'required',
            'item_sub_category' => 'required',
            'item_quantity' => 'required|numeric|min:0',
            'item_barcode' => 'required|numeric',
            'item_description' => 'required|string',
            'item_cost' => 'required|numeric',
            'item_sell' => 'required|numeric',
            'item_notes' => 'max:300',
            'item_photo' => 'mimes:jpg,png',
        ]);

        $like = scandir('img/item_photo');
        foreach ($like as $thisFile) {
            $rs = Items::where('item_photo', $thisFile)->first();
            if (!$rs) {
                if($thisFile != "." and $thisFile != ".."){
                        unlink ('img/item_photo/' . $thisFile);
                }
            }
        }

        $item  = Items::where('item_name', $request->item_name)
        ->where('item_category', $request->item_category)
        ->where('item_sub_category', $request->item_sub_category)
        ->where('item_barcode', $request->item_barcode)->first();

        if(!empty($request->item_photo)){
            $item_photo = uniqid() . '.' . $request->item_photo->extension();
        }else{
            $item_photo = $item->item_photo;
        }

        $quantity = ($request->item_quantity <= 0) ? 0 : $request->item_quantity;

        $item->increment('item_quantity', $quantity);

        $updateItem = Items::where('item_name', $request->item_name)
            ->where('item_category', $request->item_category)
            ->where('item_sub_category', $request->item_sub_category)
            ->where('item_barcode', $request->item_barcode)->update([
            'item_name' => ucfirst($request->item_name),
            'item_category' => ucfirst($request->item_category),
            'item_sub_category' => ucfirst($request->item_sub_category),
            'item_barcode' => ucfirst($request->item_barcode),
            'item_description' => $request->item_description,
            'item_cost' => $request->item_cost,
            'item_sell' => $request->item_sell,
            'item_notes' => $request->item_notes,
            'item_photo' => $item_photo,
        ]);

        if($updateItem){
            if(!empty($request->item_photo)){
                $request->item_photo->move(public_path('img/item_photo'), $item_photo);
            }
            return response(json_encode(['status' => 200, 'message' => ucfirst($request->item_name). ' Updated Successfully.']));
        }
    }

    public function destroy(String $id){
        $item = Items::where('id', $id)->firstOrFail();
        $delete = $item->delete();
        if($delete){
            return response(json_encode(['status' => 200, 'message' => 'Item Deleted.']));
        }
    }

}
