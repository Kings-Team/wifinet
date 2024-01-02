<?php

namespace App\Services;

use App\Models\UbahPaket;
use Illuminate\Support\Facades\DB;


class UbahPaketServices
{
  private UbahPaket $ubahpaket;

  public function __construct()
  {
    $this->ubahpaket = new UbahPaket();
  }


  public function fetchAllUbahPaket()
  {
    try {
      $userMitraId = auth()->user()->mitra_id;
      $roleAdmin = auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra);
      $roleSales = auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra);

      // dd($userMitraId);
      // Aktifkan log query
      DB::enableQueryLog();

      $ubahpaket = UbahPaket::where(function ($query) use ($userMitraId, $roleAdmin, $roleSales) {
        $query->when($roleAdmin || $roleSales, function ($subQuery) use ($userMitraId) {
          $subQuery->whereHas('pelanggan', function ($subQuery) use ($userMitraId) {
            $subQuery->where('mitra_id', $userMitraId);
          })
            ->whereHas('transaksi', function ($subQuery) {
              $subQuery->where('status', 'belum lunas');
            })
            ->where(function ($subQuery) {
              $subQuery->where('status_proses', '!=', 'Gagal')
                ->orWhereNull('status_proses');
            });
        })
          ->when(!($roleAdmin || $roleSales), function ($subQuery) use ($userMitraId) {
            $subQuery->where('status_visit', 'Perlu')
              ->whereHas('pelanggan', function ($subQuery) use ($userMitraId) {
                $subQuery->where('mitra_id', $userMitraId);
              })
              ->whereHas('transaksi', function ($subQuery) {
                $subQuery->where('status', 'belum lunas');
              })
              ->where(function ($subQuery) {
                $subQuery->where('status_proses', '!=', 'Gagal')
                  ->orWhereNull('status_proses');
              });
          });
      })
        ->with(['pelanggan', 'paket'])
        ->orderByDesc('id')
        ->get();


      // Dapatkan log query
      $queries = DB::getQueryLog();
      // dd($queries);

      $berhasil = UbahPaket::where('status_proses', 'Berhasil')
        ->whereHas('pelanggan', function ($subQuery) use ($userMitraId) {
          $subQuery->where('mitra_id', $userMitraId);
        })
        ->whereHas('transaksi', function ($subQuery) {
          $subQuery->where('status', 'lunas');
        })
        ->with(['pelanggan', 'paket'])
        ->orderByDesc('id')
        ->get();

      $gagal = UbahPaket::where('status_proses', 'Gagal')
        ->whereHas('pelanggan', function ($subQuery) use ($userMitraId) {
          $subQuery->where('mitra_id', $userMitraId);
        })
        ->with(['pelanggan', 'paket'])
        ->orderByDesc('id')
        ->get();

      // Kembalikan hasil
      return compact('ubahpaket', 'berhasil', 'gagal');
    } catch (\Exception $e) {
      error_log($e->getMessage());
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
