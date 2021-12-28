<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
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
}
