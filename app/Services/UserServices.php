<?php

namespace App\Services;

use App\Models\User;

class UserServices
{
    public static function getAllUsers()
    {
        return User::all();
    }

    public static function getUserByName(string $name)
    {
        return User::where('name', 'like', '%' . $name . '%')->get();
    }
}