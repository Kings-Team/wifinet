<?php

namespace App\Http\Controllers;

use App\Services\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private AuthServices $authServices;

    public function __construct()
    {
        $this->authServices = new AuthServices();
    }


    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $data = $this->validate($request, $rules);
        $request->session()->regenerate();

        if ($this->authServices->login($data['email'], $data['password'])) {
            return redirect()->route('dashboard');
        }

        return redirect()->back()->withInput()->withErrors('Gagal Masuk, Periksa kembali email dan password anda');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
