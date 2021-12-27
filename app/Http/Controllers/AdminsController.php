<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Admin;
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

        if($Admin_object != null)
        {
            if(Hash::check($admin_data['password'], $Admin_object->password))
            {
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
}
