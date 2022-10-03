<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('admin.categories.category');
    }

    public function getCategory(){
        $categories = Category::orderBy('created_at', 'desc')->get();
        if($categories->count() > 0){
            $html = '';
            foreach($categories as $category){
                $html .='<tr>';
                $html .='<td>'.$category->category_name.'</td>';
                $html .='<td>'.$category->category_description.'</td>';
                $html .='<td>'.$category->created_at->diffForHumans().'</td>';
                $html .='<td>'.$category->updated_at->diffForHumans().'</td>';
                $html .='<td>';
                $html .='<div class="d-flex gap-1">';
                $html .='<button data="'.$category->id.'" class="btn bg-secondary text-light btn-sm" onclick="editCategory(this)"><i class="fas fa-pencil-alt mr-1 fa-sm"></i><small>Edit</small></button>';
                $html .='<button data="'.$category->id.'" class="btn text-light btn-sm" onclick="deleteCategory(this)"><i class="fas fa-trash mr-1 fa-sm"></i><small>Delete</small></button>';
                $html .='</div>';
                $html .='</td></tr>';
            }
            return response(json_encode(['status' => 200, 'data' => $html]));
        }else{
            return response(json_encode(['status' => 200, 'data' => '<tr><td>No Category Found</td></tr>']));
        }
    }

    public function store(Request $request){
        $request->validate([
            'category_name' => 'required|unique:categories',
        ],[
            'category_name.unique'=> $request->category_name . ' is already created',
        ]);

        $storeCategory = Category::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);

        if($storeCategory){
            return response(json_encode(['status' => 200, 'message' => ucfirst($request->category_name). ' Created Successfully.']));
        }
    }

    public function editCategory(String $id){
        $checkCategory = Category::findOrFail($id);
        if($checkCategory){
            $html = '<input type="hidden" name="_token" value="'.csrf_token().'">';
            $html .= '<input type="hidden" name="category_id" value="'.$checkCategory->id.'">';
            $html .= '<div class="form-group">';
            $html .= '<label><small>Category Name</small></label>';
            $html .= '<input type="text" name="category_name" value="'.$checkCategory->category_name.'" class="form-control" placeholder="Category name">';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<label><small>Category Description (Optional)</small></label>';
            $html .= '<textarea name="category_description" class="form-control" rows="3">'.$checkCategory->category_description.'</textarea>';
            $html .= '</div>';
            $html .= '<div class="form-group">';
            $html .= '<button type="submit" class="btn text-light">Update Category</button>';
            $html .= '</div>';
            echo $html;
        }
    }

    public function updateCategory(Request $request){
        $request->validate([
            'category_name' => 'required',
        ]);

        if(Category::where('category_name', $request->category_name)->where('id', '!=', $request->category_id)->exists()){
            return response(json_encode(['status' => 500, 'message' => ucfirst($request->category_name). ' Exist.']));
            exit();
        }
        $updateCategory = Category::where('id', $request->category_id)->update([
            'category_name' => ucfirst($request->category_name),
            'category_description' => ucfirst($request->category_description),
        ]);

        if($updateCategory){
            return response(json_encode(['status' => 200, 'message' => ucfirst($request->category_name). ' Updated Successfully.']));
        }
    }

    public function filter($input){
        $categories = Category::where('category_name', 'like', '%'.ucfirst($input).'%')
                ->orWhere('category_name', 'like', '%'.$input.'%')
                ->orWhere('category_description', 'like', '%'.$input.'%')
                ->orWhere('category_description', 'like', '%'.ucfirst($input).'%')
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
            return response(json_encode(['status' => 200, 'data' => '<tr><td>No Category Found</td></tr>']));
        }
    }

    public function deleteCategory(String $id){
        $category = Category::where('id', $id)->firstOrFail();
        $delete = $category->delete();
        if($delete){
            return response(json_encode(['status' => 200, 'message' => 'Item Deleted.']));
        }
    }
}   
