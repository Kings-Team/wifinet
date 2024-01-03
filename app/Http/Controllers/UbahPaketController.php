<?php

namespace App\Http\Controllers;

use App\Services\UbahPaketServices;
use Illuminate\Http\Request;

class UbahPaketController extends Controller
{
    private UbahPaketServices $ubahPaketServices;

    public function __construct()
    {
        $this->middleware(['isAuth']);
        $this->middleware(['permission:read ubah paket'], ['only' => ['index']]);
        $this->middleware(['permission:create ubah paket|read ubah paket'], ['only' => ['store']]);
        $this->middleware(['permission:update ubah paket|read ubah paket'], ['only' => ['update']]);
        $this->ubahPaketServices = new UbahPaketServices();
    }

    public function index()
    {
        $result = $this->ubahPaketServices->fetchAllUbahPaket();

        return view('pages.ubah-paket', $result);
    }

    public function store(Request $request)
    {
        $rules = [
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'paket_baru_id' => 'required|exists:paket,id',
        ];

        $data = $this->validate($request, $rules);

        $result = $this->ubahPaketServices->addUbahPaket($data);

        return redirect()->route('ubah-paket')->with('message', $result['message']);
    }

    public function visit(Request $request, $id)
    {
        $rules = [
            'status_visit' => 'required|in:Perlu,Tidak Perlu',
        ];

        $data = $this->validate($request, $rules);

        $result = $this->ubahPaketServices->visit($data, $id);

        return redirect()->route('ubah-paket')->with('message', $result['message']);
    }

    public function proses(Request $request, $id)
    {
        $rules = [
            'status_proses' => 'required|in:Berhasil,Gagal',
            'keterangan_proses' => 'nullable',
        ];

        $data = $this->validate($request, $rules);

        $result = $this->ubahPaketServices->proses($data, $id);

        return redirect()->route('ubah-paket')->with('message', $result['message']);
    }
    public function updatePembayaran(Request $request, $id)
    {
        $rules = [
            'tgl_action' => 'required|date',
            'biaya' => 'required',
            'bayar' => 'required',
            'diskon' => 'required',
            'status' => 'required',
            'keterangan' => 'nullable',
        ];

        $data = $this->validate($request, $rules);

        $result = $this->ubahPaketServices->pembayaran($data, $id);

        return redirect()->route('ubah-paket')->with('message', $result['message']);
    }
    public function invoice($id)
    {
        return $this->ubahPaketServices->cetakNota($id);
    }
}
