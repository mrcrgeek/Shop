<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCategory;

class ProductsController extends Controller
{
    public function add_product(Request $request)
    {
        $request->validate([
            'title' => 'required|max:60|unique:App\Models\Product',
            'description' => 'required|max:150',
            'price' => 'required|integer',
            'img' => 'required|mimes:jpg,png,jpeg|max:10240',
            'stock' => 'required|integer',
            'category_name' => 'required|max:50'
        ]);

        $Category_Object = Category::where('category_name', $request->input('category_name'))->first();

        if ($Category_Object != null) {
            $Img_path = $request->file('img')->store('/uploads');

            $Product_data = [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'img' => $Img_path,
                'stock' => $request->input('stock'),
            ];

            $Product_Object = Product::create($Product_data);

            $Product_Category_Object = ProductCategory::create([
                'product_id' => $Product_Object->id,
                'category_id' => $Category_Object->id
            ]);

            return response()->json([
                'message' => 'Product Added Successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Category Not Found'
        ], 404);
    }

    public function update_product(Request $request, $id)
    {
        $request->validate([
            'title' => 'max:60|unique:App\Models\Product',
            'description' => 'max:150',
            'price' => 'integer',
            'img' => 'mimes:jpg,png,jpeg|max:10240',
            'stock' => 'integer',
        ]);

        $Product_object = Product::where('id', $id);

        if ($Product_object->exists()) {
            $New_Data = [];

            (!empty($request->input('title')) && ($New_Data['title'] = $request->input('title')));
            (!empty($request->input('description')) && ($New_Data['description'] = $request->input('description')));
            (!empty($request->input('price')) && ($New_Data['price'] = $request->input('price')));
            (!empty($request->file('img')) && ($New_Data['img'] = $request->file('img')->store('/uploads')));
            (!empty($request->input('stock')) && ($New_Data['stock'] = $request->input('stock')));

            $Product_object->update($New_Data);

            return response()->json([
                'message' => 'Product Updated Successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Product Not Found'
        ], 404);
    }

    public function delete_product($id)
    {
        $Product_Object = Product::where('id', $id);


        if($Product_Object->exists())
        {
            $Product_Object->delete();
            ProductCategory::where('product_id', $id)->delete();

            return response()->json([
                'message' => 'Product Deleted Successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Product Not Found'
        ], 404);
    }

    public function products(Request $request)
    {
        $request->validate([
            'from' => 'date',
            'to' => 'date'
        ]);

        $Products = Product::query();

        if($request->has('category'))
        {
            $Category_Object = Category::where('category_name', $request->input('category'))->first();

            if($Category_Object != null)
            {
                $Products = $Category_Object->find($Category_Object->id)->products();
            }
            else
            {
                return response()->json([
                    'message' => 'category not found'
                ], 404);
            }
        }

        if($request->has('from') && $request->has('to'))
        {
            $Products->where('created_at', '>=' , $request->input('from'))->where('created_at', '<=' , $request->input('to'))->get();
        }

        if($request->has('stock'))
        {
            if($request->input('stock') == 'available')
            {
                $Products->where('stock', '>' , '0');
            }
            else if($request->input('stock') == 'unavailable')
            {
                $Products->where('stock', '<' , '1');
            }
        }

        if(empty($Products))
        {
            return response()->json([
               'message' => 'No Products Found'
            ], 404);
        }

        return response()->json($Products->get(), 200);
    }
}
