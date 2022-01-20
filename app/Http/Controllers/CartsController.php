<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartsController extends Controller
{
    public function add_to_cart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer'
        ]);

        $Product_object = Product::where('id', $request->input('product_id'))->first();

        if($Product_object != null)
        {
            $CartObject = Cart::where('user_id', $request->user()->id)->where('product_id', $request->input('product_id'));

            if($CartObject->exists())
            {
                    return response()->json([
                        'message' => 'Product Already Exist in Your Cart'
                    ], 422);
            }

            $Stock = $Product_object->stock;

            if($request->input('quantity') <= 0)
            {
                return response()->json([
                    'message' => 'Quantity Should be Bigger Than 0'
                ], 422);
            }

            if($Stock - $request->input('quantity') <= 0)
            {
                return response()->json([
                   'message' => 'Too Much Quantity'
                ], 422);
            }

            $Final_info = [
                'user_id' => $request->user()->id,
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity')
            ];

            Cart::create($Final_info);

            return response()->json([
                'message' => 'Product Added to Cart Successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Product Not Found'
        ], 404);
    }

    public function carts(Request $request)
    {
        $Cart_object = Cart::where('user_id', $request->user()->id)->get();

        if(count($Cart_object) > 0)
        {
            $Carts = [];

            foreach ($Cart_object as $cart)
            {
                $Carts[] = Product::find($cart['product_id']);
            }

            return response()->json([
                'carts' => $Carts,
                'count' => count($Carts)
            ],200);
        }

        return response()->json([
            'message' => 'Cart is Empty'
        ], 404);
    }

    public function delete_cart(Request $request,$id)
    {
        $Cart_object = Cart::where('user_id', $request->user()->id)->where('product_id', $id);
        if($Cart_object->exists())
        {
            $Cart_object->delete();

            return response()->json([
                'message' => 'Cart Deleted Successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Cart Not Found'
        ], 404);
    }
}
