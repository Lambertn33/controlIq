<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthServices
{
    public  static function checkIfUserIsAuthenticated()
    {
        return Auth::check() ? [
            'isAuthenticated' => true,
            'user' => Auth::user(),
        ] : [
            'isAuthenticated' => false,
            'user' => null,
        ];
    }

    public static function isUserAdmin()
    {
        return Auth::check() ? Auth::user()->role === User::ADMIN : false;
    }
}