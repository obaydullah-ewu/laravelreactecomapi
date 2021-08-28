<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'meta_title' => 'required|max:191',
            'brand' => 'required|max:20',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:4',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails())
        {
            return response()->json([
               'status' => 422,
               'errors' => $validator->messages()
            ]);
        }else {
            $product = new Product();
            $product->category_id = $request['category_id'];
            $product->slug = $request['slug'];
            $product->name = $request['name'];
            $product->description = $request['description'];

            $product->meta_title = $request['meta_title'];
            $product->meta_keyword = $request['meta_keyword'];
            $product->meta_descrip = $request['meta_descrip'];

            $product->brand = $request['brand'];
            $product->selling_price = $request['selling_price'];
            $product->original_price = $request['original_price'];
            $product->qty = $request['qty'];

            $product->featured = $request['featured'] == true ? '1':'0';
            $product->popular = $request['popular'] == true ? '1':'0';
            $product->status = $request['status'] == true ? '1':'0';

            if ($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.'.$extension;
                $file->move('uploads/product/',$filename);
                $product->image = 'uploads/product/'.$filename;
            }

            $product->save();

            return response()->json([
                'status' => 200,
                'message' => "Product Added Successfully"
            ]);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        if ($product)
        {
            return response()->json([
                'status'=>200,
                'product'=>$product,
            ]);
        }else{
            return response()->json([
               'status'=>404,
               'message'=>'No Product Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'meta_title' => 'required|max:191',
            'brand' => 'required|max:20',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:4',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ]);
        }else {
            $product = Product::find($id);
            if ($product)
            {
                $product->category_id = $request['category_id'];
                $product->slug = $request['slug'];
                $product->name = $request['name'];
                $product->description = $request['description'];

                $product->meta_title = $request['meta_title'];
                $product->meta_keyword = $request['meta_keyword'];
                $product->meta_descrip = $request['meta_descrip'];

                $product->brand = $request['brand'];
                $product->selling_price = $request['selling_price'];
                $product->original_price = $request['original_price'];
                $product->qty = $request['qty'];

                $product->featured = $request['featured'];
                $product->popular = $request['popular'];
                $product->status = $request['status'];

                if ($request->hasFile('image'))
                {
                    $path = $product->image;
                    if (File::exists($path))
                    {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.'.$extension;
                    $file->move('uploads/product/',$filename);
                    $product->image = 'uploads/product/'.$filename;
                }

                $product->update();

                return response()->json([
                    'status' => 200,
                    'message' => "Product Updated Successfully"
                ]);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => "No Product Found"
                ]);
            }

        }
    }
}
