<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{   
    private UserServices $userServices;

    public function __construct()
    {
        $this->middleware(['isAuth']);
        $this->userServices = new UserServices();
    }

    public function index()
    {
        $result = $this->userServices->fetchAll();

        $data = $result['data'];
        $users = $result['user'];
        $mitra = $result['mitra'];

        return view('pages.user', compact('data', 'users', 'mitra'));
    }

}
