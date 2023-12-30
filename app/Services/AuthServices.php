<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServices
{
    public function login($email, $password)
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }
}
