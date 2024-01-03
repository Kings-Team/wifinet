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
        $result = $this->userServices->fetchAllUser();

        return view('pages.user', $result);
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


        $data = $this->validate($request, $rules);

        $request = $this->userServices->addUser($data);

        return redirect()->route('user')->with('message', $request['message']);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|min:8',
            'mitra_id' => 'required|exists:mitra,id',
            'name_role' => 'required',
            'permissions' => 'required'
        ];

        $data = $this->validate($request, $rules);

        $request = $this->userServices->updateUser($data, $id);

        return redirect()->route('user')->with('message', $request['message']);
    }

    public function delete($id)
    {
        return $this->userServices->deleteUser($id);
    }
}
