<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|alpha_num|unique:App\Models\User',
            'lastname' => 'required',
            'phoneNumber' => 'required|numeric|unique:App\Models\User',
            'password' => 'required'
        ]);

        $register_data = [
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'phoneNumber' => $request->input('phoneNumber'),
            'password' => Hash::make($request->input('password'))
        ];

        if(!\Check_PhoneNum_ir($register_data['phoneNumber']))
        {
            return response()->json([
                'message' => 'Phone number should not be more or less than 11 char & should be IR'
            ], 422);
        }

        User::create($register_data);

        return response()->json([
           'message' => 'register was successful'
        ], 200);
    }
}
