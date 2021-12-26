<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable,HasFactory;

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
