<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Items;
use App\Models\SubCategory;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $categories = Category::get();
        return view('admin.categories.subcategory', compact(['categories']));
    }

    public function getSubCategory(){
        $subCategories = SubCategory::orderBy('created_at', 'desc')->get();
        if($subCategories->count() > 0){
            $html = '';
            foreach($subCategories as $subCategory){
                $html .='</tr>';
                $html .='<td>'.$subCategory->category_name.'</td>';
                $html .='<td>'.$subCategory->sub_category_name.'</td>';
                $html .='<td>'.$subCategory->created_at->diffForHumans().'</td>';
                $html .='<td>'.$subCategory->updated_at->diffForHumans().'</td>';
                $html .='<td>';
                $html .='<div class="d-flex gap-1">';
                $html .='<button data="'.$subCategory->id.'" class="btn bg-secondary text-light btn-sm" onclick="editSubCategory(this)"><i class="fas fa-pencil-alt mr-1 fa-sm"></i><small>Edit</small></button>';
                $html .='<button data="'.$subCategory->id.'" class="btn text-light btn-sm" onclick="deleteCategory(this)"><i class="fas fa-trash mr-1 fa-sm"></i><small>Delete</small></button>';
                $html .='</div>';
                $html .='</td></tr>';
            }
            return response(json_encode(['status' => 200, 'data' => $html]));
        }else{
            return response(json_encode(['status' => 200, 'data' => '<tr><td>No Sub Category Found</td></tr>']));
        }
    }

    public function store(Request $request){
        
        $request->validate([
            'category_name' => 'required',
            'sub_category_name' => 'required',
        ]);
        
        if(str_contains($request->category_name, '|')){
            $collection = explode('|', $request->category_name);
            $category_id = $collection[1];
            $category_name = $collection[0];

            $checkCategory = Category::where('category_name', $category_name)->where('id', $category_id)->firstOrFail();
            if($checkCategory){
                $check = SubCategory::where('category_name', $category_name)->where('sub_category_name', $request->sub_category_name)->exists();
                if($check){
                    return response(json_encode(['status' => 422, 'message' => ucfirst($category_name). ' with '.$request->sub_category_name .' already exist.']));
                }else{
                    $storeCategory = SubCategory::create([
                        'category_id' => $category_id,
                        'category_name' => $category_name,
                        'sub_category_name' => $request->sub_category_name,
                    ]);
            
                    if($storeCategory){
                        return response(json_encode(['status' => 200, 'message' => ucfirst($request->sub_category_name). ' Created Successfully.']));
                    }
                }
            }
        }else{
            return response(json_encode(['status' => 500, 'message' => 'Something went wrong. Please reload page.']));
        }
    }

    public function editSubCategory(String $id){
        $subCategory = SubCategory::findOrFail($id);
        if($subCategory){
            $html = '<input type="hidden" name="_token" value="'.csrf_token().'">';
            $html .= '<input type="hidden" name="sub_category_id" value="'.$subCategory->id.'">';
            $html .= '<div class="form-group">';
            $html .= '<label><small>Category Name</small></label>';
            $html .= '<input type="text" name="category_name" value="'.$subCategory->category_name.'" class="form-control" placeholder="Category name">';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<label><small>Sub Category</label>';
            $html .= '<input type="text" name="sub_category_name" value="'.$subCategory->sub_category_name.'" class="form-control"  placeholder="Sub Category name">';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<button type="submit" class="btn text-light">Update Sub Category</button>';
            $html .= '</div>';
            echo $html;
        }
    }

    public function updateSubCategory(Request $request){
        $request->validate([
            'category_name' => 'required',
            'sub_category_name' => 'required',
        ]);

        if(SubCategory::where('sub_category_name', $request->category_name)->where('id', '!=', $request->sub_category_id)->exists()){
            return response(json_encode(['status' => 500, 'message' => ucfirst($request->category_name). ' with '.$request->sub_category_name .' already exist.']));
            exit();
        }
        $cat = SubCategory::where('id', $request->sub_category_id)->first();
        $oldCategory = Category::where('id', $cat->category_id)->first();
        $updateCategory = SubCategory::where('id', $request->sub_category_id)->update([
            'sub_category_name' => ucfirst($request->sub_category_name),
        ]);
        

        if($updateCategory){
            SubCategory::where('category_id', $cat->category_id)->update([
                'category_name' => ucfirst($request->category_name),
            ]);
            Category::where('id', $cat->category_id)->update([
                'category_name' => ucfirst($request->category_name)
            ]);
            
            Items::where('item_category', $oldCategory->category_name)
            ->where('item_sub_category', $cat->sub_category_name)->update([
                'item_category' => $request->category_name,
                'item_sub_category' => $request->sub_category_name
            ]);

            return response(json_encode(['status' => 200, 'message' => ucfirst($request->sub_category_name). ' Updated Successfully.']));
        }
    }

    public function filter($input){
        $categories = SubCategory::where('category_name', 'like', '%'.ucfirst($input).'%')
                ->orWhere('category_name', 'like', '%'.$input.'%')
                ->orWhere('sub_category_name', 'like', '%'.$input.'%')
                ->orWhere('sub_category_name', 'like', '%'.ucfirst($input).'%')
                ->orderBy('created_at', 'desc')->get();
      
        if($categories->count() > 0){
            $html = '';
            foreach($categories as $category){
                $html .='</tr>';
                $html .='<td>'.$category->category_name.'</td>';
                $html .='<td>'.$category->category_description.'</td>';
                $html .='<td>'.$category->created_at->diffForHumans().'</td>';
                $html .='<td>'.$category->updated_at->diffForHumans().'</td>';
                $html .='<td>';
                $html .='<div class="d-flex gap-1">';
                $html .='<button data="'.$category->id.'" class="btn text-light" onclick="editCategory(this)">Edit</button>';
                $html .='<button data="'.$category->id.'" class="btn text-light" onclick="deleteCategory(this)">Delete</button>';
                $html .='</div>';
                $html .='</td></tr>';
            }
            return response(json_encode(['status' => 200, 'data' => $html]));
        }else{
            return response(json_encode(['status' => 200, 'data' => '<tr><td>No Sub Category Found</td></tr>']));
        }
    }
    // 14-02-000086 14-02-000082

    public function deleteSubCategory(String $id){
        $category = SubCategory::where('id', $id)->firstOrFail();
        $delete = $category->delete();
        if($delete){
            Items::where('item_sub_category', $category->sub_category_name)->where('item_category', $category->category_name)->update([
                'item_sub_category' => 'No Sub Category'
            ]);
            return response(json_encode(['status' => 200, 'message' => 'Sub Category Deleted.']));
        }
    }
}
