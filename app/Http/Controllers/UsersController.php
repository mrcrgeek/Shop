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

    public function login(Request $request)
    {
        $request->validate([
           'name' => 'required|max:50',
           'password' => 'required'
        ]);

        $login_data = [
            'name' => $request->input('name'),
            'password' => $request->input('password')
        ];

        $User_object = User::where('name', $login_data['name'])->first();

        if($User_object != null)
        {
            if(Hash::check($login_data['password'], $User_object->password))
            {
                $Access_Token = $User_object->createToken('User-info')->accessToken;

                return response()->json([
                   'message' => 'Login was successful',
                    'Token' => $Access_Token
                ], 200);
            }
        }

        return response()->json([
            'message' => 'username or password is wrong'
        ], 403);
    }
}
