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
}
