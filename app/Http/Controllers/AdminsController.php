<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'password' => 'required:max:100'
        ]);

        $admin_data = [
            'name' => $request->input('name'),
            'password' => $request->input('password')
        ];

        $Admin_object = Admin::where('name', $admin_data['name'])->first();

        if ($Admin_object != null) {
            if (Hash::check($admin_data['password'], $Admin_object->password)) {
                $AccessToken = $Admin_object->createToken('Admin-Token')->plainTextToken;

                return response()->json([
                    'message' => 'Login was successful',
                    'Token' => $AccessToken
                ], 200);
            }
        }

        return Response()->json([
            'message' => 'Admin Name or Password is Wrong'
        ], 403);
    }

    public function show(Request $request) //test
    {
        return $request->user();
    }

    public function all_users()
    {
        $Users = User::get();

        if(count($Users) > 0)
        {
            return response()->json([
                'Data' => $Users,
                'Count' => count($Users)
            ]);
        }

        return response()->json([
            'message' => 'No Users found'
        ], 404);
    }

    public function User_Paginate(Request $request)
    {
        $request->validate([
            'skip' => 'numeric',
            'limit' => 'numeric'
        ]);

        $Data = User::skip(\Set_Default_Value($request->input('skip'), 0))
            ->take(\Set_Default_Value($request->input('limit'), '10'))
            ->get();

        if(count($Data) > 0)
        {
            return response()->json([
                'Data' => $Data,
                'Count' => count($Data)
            ], 200);
        }

        return response()->json([
            'message' => 'No Users found'
        ], 404);
    }

    public function edit_user(Request $request,$id)
    {
        $request->validate([
            'name' => 'max:55|unique:App\Models\User',
            'lastname' => 'max:100',
            'phoneNumber' => 'numeric|unique:App\Models\User',
            'password' => 'max:400'
        ]);

        $User_object = User::where('id', $id);

        if($User_object->exists()) {
            $New_Data = [];

            (!empty($request->input('name')) && ($New_Data['name'] = $request->input('name')));
            (!empty($request->input('lastname')) && ($New_Data['lastname'] = $request->input('lastname')));
            (!empty($request->input('phoneNumber')) && ($New_Data['phoneNumber'] = $request->input('phoneNumber')));
            (!empty($request->input('password')) && ($New_Data['password'] = Hash::make($request->input('password'))));

            if (isset($New_Data['phoneNumber']) && (!\Check_PhoneNum_ir($request->input('phoneNumber'))))
            {
                return response()->json([
                    'message' => 'Phone number should not be more or less than 11 char & should be IR'
                ], 422);
            }

            $User_object->update($New_Data);

            return response()->json([
                'message' => 'Update Was Successful'
            ],200);
        }

        return response()->json([
            'message' => 'User Not Found'
        ], 404);
    }

    public function add_category(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:App\Models\Category|max:50'
        ]);

        Category::create([
            'category_name' => $request->input('category_name')
        ]);

        return response()->json([
            'message' => 'Category Added Successfully'
        ], 200);
    }

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

        if($Category_Object != null)
        {
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

    public function update_product(Request $request,$id)
    {
        $request->validate([
            'title' => 'max:60|unique:App\Models\Product',
            'description' => 'max:150',
            'price' => 'integer',
            'img' => 'mimes:jpg,png,jpeg|max:10240',
            'stock' => 'integer',
        ]);

        $Product_object = Product::where('id', $id);

        if($Product_object->exists())
        {
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
        ]);
    }
}
