<?php

namespace App\Traits;

use App\Models\User;
use App\Http\Controllers\UsersController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Traits
{
    use HasFactory;

    static function update_user($new_data,$id)
    {
        $Final_data = [];

        (!empty($new_data['name']) && ($Final_data['name'] = $new_data['name']));
        (!empty($new_data['lastname']) && ($Final_data['lastname'] = $new_data['lastname']));
        (!empty($new_data['phoneNumber']) && ($Final_data['phoneNumber'] = $new_data['phoneNumber']));
        (!empty($new_data['password']) && ($Final_data['password'] = Hash::make($new_data['password'])));

        User::where('id', $id)->update($Final_data);
    }

    static function show_user_profile($id)
    {
        $Find_User = User::where('id', $id);

        if($Find_User->exists())
        {
            return response()->json($Find_User->first(), 200);
        }

        return response()->json([
            'message' => 'User Not Found'
        ], 404);
    }
}
