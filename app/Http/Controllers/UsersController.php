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
            'name' => 'required|max:55|unique:App\Models\User',
            'lastname' => 'required|max:100',
            'phoneNumber' => 'required|numeric|unique:App\Models\User',
            'password' => 'required|max:400'
        ]);

        $register_data = [
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'phoneNumber' => $request->input('phoneNumber'),
            'password' => Hash::make($request->input('password'))
        ];

        if (!\Check_PhoneNum_ir($register_data['phoneNumber'])) {
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
            'password' => 'required|max:400'
        ]);

        $login_data = [
            'name' => $request->input('name'),
            'password' => $request->input('password')
        ];

        $User_object = User::where('name', $login_data['name'])->first();

        if ($User_object != null) {
            if (Hash::check($login_data['password'], $User_object->password)) {
                $Access_Token = $User_object->createToken('User-info')->plainTextToken;

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

    public function show(Request $request) //test
    {
        return $request->user();
    }

    public function edit_profile(Request $request)
    {
        $request->validate([
            'name' => 'max:50|unique:App\Models\User',
            'lastname' => 'max:100',
            'password' => 'max:400'
        ]);

        $User_id = $request->user()->id;

        $New_Data = [];

        (!empty($request->input('name')) && ($New_Data['name'] = $request->input('name')));
        (!empty($request->input('lastname')) && ($New_Data['lastname'] = $request->input('lastname')));
        (!empty($request->input('password')) && ($New_Data['password'] = Hash::make($request->input('password'))));

        User::where('id', $User_id)->update($New_Data);

        return response()->json([
            'message' => 'update was successful'
        ], 200);
    }

    public function all_users()
    {
        $Users = User::get();

        if (count($Users) > 0) {
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

        if (count($Data) > 0) {
            return response()->json([
                'Data' => $Data,
                'Count' => count($Data)
            ], 200);
        }

        return response()->json([
            'message' => 'No Users found'
        ], 404);
    }

    public function edit_user(Request $request, $id)
    {
        $request->validate([
            'name' => 'max:55|unique:App\Models\User',
            'lastname' => 'max:100',
            'phoneNumber' => 'numeric|unique:App\Models\User',
            'password' => 'max:400'
        ]);

        $User_object = User::where('id', $id);

        if ($User_object->exists()) {
            $New_Data = [];

            (!empty($request->input('name')) && ($New_Data['name'] = $request->input('name')));
            (!empty($request->input('lastname')) && ($New_Data['lastname'] = $request->input('lastname')));
            (!empty($request->input('phoneNumber')) && ($New_Data['phoneNumber'] = $request->input('phoneNumber')));
            (!empty($request->input('password')) && ($New_Data['password'] = Hash::make($request->input('password'))));

            if (isset($New_Data['phoneNumber']) && (!\Check_PhoneNum_ir($request->input('phoneNumber')))) {
                return response()->json([
                    'message' => 'Phone number should not be more or less than 11 char & should be IR'
                ], 422);
            }

            $User_object->update($New_Data);

            return response()->json([
                'message' => 'Update Was Successful'
            ], 200);
        }

        return response()->json([
            'message' => 'User Not Found'
        ], 404);
    }
}
