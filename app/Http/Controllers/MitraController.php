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
        $data = $this->mitraServices->fetchAll();
        return view('mitra', $data);
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

        $request =  $this->mitraServices->add($data);

        if (!$request) {
            Alert::error('Error', 'Gagal menambah mitra');
            return redirect()->back()->withErrors('Failed to add Mitra');
        }
        Alert::success("Berhasil", "Berhasil menambah mitra");
        return redirect()->route('user')->with('success', 'Mitra added successfully');
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

        $updateResult = $this->mitraServices->update($data, $id);

        if (!$updateResult) {
            return redirect()->back()->withErrors('Failed to update Mitra');
        }

        return redirect()->route('mitra.index')->with('success', 'Mitra updated successfully');
    }

}
