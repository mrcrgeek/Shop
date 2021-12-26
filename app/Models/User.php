<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class User extends Model
{
    use HasApiTokens,HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'lastname',
        'phoneNumber',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
