<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{   
    private UserServices $userServices;

    public function __construct()
    {
        $this->middleware(['isAuth', 'role:route']);
        $this->userServices = new UserServices();
    }

    public function index()
    {
        $result = $this->userServices->fetchAllUser();

        $data = $result['data'];
        $users = $result['user'];
        $mitra = $result['mitra'];
        $permission = $result['permission'];
        $role = $result['role'];

        return view('pages.user', compact('data', 'users', 'mitra', 'permission', 'role'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'mitra_id' => 'required|exists:mitra,id',
            'name_role' => 'required',
            'permissions' => 'required'
        ];

        $msg = [
            'required' => ':attribute is required'
        ];

        $data = $this->validate($request, $rules, $msg);

        $request = $this->userServices->addUser($data);

        return redirect()->route('user')->with('message', $request['message']);

    }

    public function delete($id)
    {
        return $this->userServices->deleteUser($id);
    }
}
