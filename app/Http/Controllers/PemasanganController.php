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
        $this->middleware(['permission:read pemasangan'], ['only' => ['index']]);
        $this->middleware(['permission:create pemasangan|read pemasangan'], ['only' => ['store']]);
        $this->middleware(['permission:update pemasangan|read pemasangan'], ['only' => ['update', 'assignment', 'updateSurvey', 'assignmentTeknisi', 'updateInstalasi', 'updateAktivasi', 'updatePembayaran', 'invoice']]);
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

        $result = $this->pemasanganServices->addPemasangan($data);

        return redirect()->route('pemasangan')->with('message', $result['message']);
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

        $result = $this->pemasanganServices->updatePemasangan($data, $id);

        return redirect()->route('pemasangan')->with('message', $result['message']);
    }

    public function assignment(Request $request, $id)
    {
        $rules = [
            'user_survey' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $result = $this->pemasanganServices->assignmentSales($data, $id);

        return redirect()->route('pemasangan')->with('message', $result['message']);
    }

    public function updateSurvey(Request $request, $id)
    {
        $rules = [
            'status_survey' => ['required', Rule::in(['berhasil survey', 'gagal survey'])],
            'keterangan_survey' => 'required',
            'tgl_action' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $result = $this->pemasanganServices->updateSurvey($data, $id);

        return redirect()->route('pemasangan')->with('message', $result['message']);
    }

    public function assignmentTeknisi(Request $request, $id)
    {
        $rules = [
            'user_action' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $result = $this->pemasanganServices->assignmentTeknisi($data, $id);

        return redirect()->route('pemasangan')->with('message', $result['message']);
    }

    public function updateInstalasi(Request $request, $id)
    {
        $rules = [
            'status_instalasi' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $result = $this->pemasanganServices->statusInstalasi($data, $id);

        return redirect()->route('pemasangan')->with('message', $result['message']);
    }

    public function updateAktivasi(Request $request, $id)
    {
        $rules = [
            'status_aktivasi' => 'required',
        ];
        $data = $this->validate($request, $rules);

        $result = $this->pemasanganServices->statusAktivasi($data, $id);

        return redirect()->route('pemasangan')->with('message', $result['message']);
    }

    public function updatePembayaran(Request $request, $id)
    {
        $rules = [
            'biaya' => 'required',
            'bayar' => 'required',
            'diskon' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
        ];

        $data = $this->validate($request, $rules);

        $result = $this->pemasanganServices->pembayaran($data, $id);

        return redirect()->route('pemasangan')->with('message', $result['message']);
    }

    public function invoice($id)
    {
        return $this->pemasanganServices->cetakNota($id);
    }
}
