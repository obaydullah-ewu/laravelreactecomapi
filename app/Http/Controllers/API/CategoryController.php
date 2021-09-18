<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }
    public function allCategory()
    {
        $category = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }


    public function edit($id)
    {
        $category = Category::find($id);
        if ($category)
        {
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'No Category ID Found'
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }else{
            $category = new Category();
            $category->meta_title = $request['meta_title'];
            $category->meta_keyword = $request['meta_keyword'];
            $category->meta_description = $request['meta_description'];
            $category->slug = $request['slug'];
            $category->name = $request['name'];
            $category->description = $request['description'];
            $category->status = $request['status'] == true ? '1':'0';
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Category Added Successfully'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        }else{
            $category = Category::find($id);
            if ($category)
            {
                $category->meta_title = $request['meta_title'];
                $category->meta_keyword = $request['meta_keyword'];
                $category->meta_description = $request['meta_description'];
                $category->slug = $request['slug'];
                $category->name = $request['name'];
                $category->description = $request['description'];
                $category->status = $request['status'] == true ? '1':'0';
                $category->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Category Updated Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Category ID Found '
                ]);
            }

        }
    }
    public function destroy($id)
    {
        $category = Category::find($id);
        $products = Product::whereCategoryId($id)->get();
        if (count($products) > 0){
            return response()->json([
                'status' => 401,
                'message' => 'This category has many products, You can not delete'
            ]);
        }
        if ($category)
        {
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category Deleted Successfully'
            ]);
        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'No Category ID Found '
            ]);
        }

    }
}
