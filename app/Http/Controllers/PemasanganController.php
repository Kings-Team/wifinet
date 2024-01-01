<?php

namespace App\Http\Controllers;

use App\Services\PemasanganServices;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PemasanganController extends Controller
{
    private PemasanganServices $pemasanganServices;

    public function __construct()
    {
        $this->middleware(['isAuth']);
        $this->pemasanganServices = new PemasanganServices();
    }

    public function index()
    {
        $result = $this->pemasanganServices->fetchAllPemasangan();

        return view('pages.pemasangan', $result);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'nik' => 'required|max:16',
            'alamat' => 'required',
            'telepon' => 'required|max:15',
            'paket_id' => 'required|exists:paket,id',
        ];

        $data = $this->validate($request, $rules);

        $request = $this->pemasanganServices->addPemasangan($data);

        return redirect()->route('pemasangan')->with('message', $request['message']);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required',
            'nik' => 'required|max:16',
            'alamat' => 'required',
            'telepon' => 'required|max:15',
            'paket_id' => 'required|exists:paket,id',
        ];
        $data = $this->validate($request, $rules);

        $request = $this->pemasanganServices->updatePemasangan($data, $id);

        return redirect()->route('pemasangan')->with('message', $request['message']);
    }

    public function assignment(Request $request, $id)
    {
        $rules = [
            'user_survey' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $request = $this->pemasanganServices->assignmentSales($data, $id);

        return redirect()->route('pemasangan')->with('message', $request['message']);
    }

    public function updateSurvey(Request $request, $id)
    {
        $rules = [
            'status_survey' => ['required', Rule::in(['berhasil survey', 'gagal survey'])],
            'keterangan_survey' => 'required',
            'tgl_action' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $request = $this->pemasanganServices->updateSurvey($data, $id);

        return redirect()->route('pemasangan')->with('message', $request['message']);
    }

    public function assignmentTeknisi(Request $request, $id)
    {
        $rules = [
            'user_action' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $request = $this->pemasanganServices->assignmentTeknisi($data, $id);

        return redirect()->route('pemasangan')->with('message', $request['message']);
    }
}
