<?php

namespace App\Http\Controllers;

use App\Services\MitraServices;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MitraController extends Controller
{
    private MitraServices $mitraServices;

    public function __construct()
    {
        $this->middleware(['role:route', 'isAuth']);
        $this->mitraServices = new MitraServices();
    }

    public function index()
    {
        $data = $this->mitraServices->fetchAllMitra();
        return view('mitra', ['data', $data]);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_mitra' => 'required'
        ];

        $msg = [
            'required' => ':attribute is required'
        ];

        $data = $this->validate($request, $rules, $msg);

        $request =  $this->mitraServices->addMitra($data);

        return redirect()->route('user')->with('message', $request['message']);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama_mitra' => 'required',
        ];

        $msg = [
            'required' => ':attribute is required'
        ];

        $data = $this->validate($request, $rules, $msg);

        $request = $this->mitraServices->updateMitra($data, $id);

        return redirect()->route('user')->with('message', $request['message']);
    }

    public function delete($id)
    {
        return $this->mitraServices->deleteMitra($id);
    }
}
