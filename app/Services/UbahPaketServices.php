<?php

namespace App\Services;

use App\Exceptions\WebException;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\UbahPaket;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
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
      // dd($gagal);

      $teknisi = User::role('teknisi ' . auth()->user()->mitra->nama_mitra)->get();
      $pelanggan = Pelanggan::with('paket')->get();
      $paket = Paket::all();
      return compact('ubahpaket', 'berhasil', 'gagal', 'teknisi', 'pelanggan', 'paket');
    } catch (\Exception $e) {
      error_log($e->getMessage());
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function addUbahPaket($request)
  {
    DB::beginTransaction();
    try {
      $pelangganId = $request['pelanggan_id'];

      $existingUbahPaket = UbahPaket::where('pelanggan_id', $pelangganId)
        ->where(function ($query) {
          $query->whereNull('status_visit');
        })
        ->exists();

      if ($existingUbahPaket) {
        throw new WebException('Gagal menambah data ubah paket. Pelanggan masih memiliki pengajuan ubah paket yang belum diproses');
      }

      $idTransaksi = Transaksi::create([])->id;

      $pelanggan = Pelanggan::findOrFail($pelangganId);
      $namaPaketLama = $pelanggan->paket->jenis_paket;
      $paketIdBaru = $request['paket_baru_id'];
      $isCreated = $this->ubahpaket->create([
        'transaksi_id' => $idTransaksi,
        'pelanggan_id' => $pelangganId,
        'paket_lama' => $namaPaketLama,
        'paket_baru_id' => $paketIdBaru,
      ]);

      $pelangganUpdateResult = $pelanggan->update([
        'paket_id' => $paketIdBaru
      ]);

      if ($isCreated && $pelangganUpdateResult) {
        DB::commit();

        return [
          'status' => true,
          'code' => 201,
          'message' => "Berhasil menambah data ubah paket",
        ];
      }
      throw new WebException('Gagal menambah data ubah paket');
    } catch (\Exception $e) {
      throw new WebException($e->getMessage());
    }
  }

  public function visit($request, $id)
  {
    try {
      $ubahpaket = $this->ubahpaket->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    try {
      if ($ubahpaket->status_visit === 'Perlu' || $ubahpaket->status_visit === 'Tidak Perlu') {
        throw new WebException('Gagal mengupdate data, status visit sudah diupdate');
      }
      $update = $ubahpaket->update([
        'status_visit' => $request['status_visit']
      ]);
      if ($update) {
        DB::commit();
        return [
          'status' => true,
          'code' => 200,
          'message' => "Berhasil mengupdate status visit",
        ];
      }
      throw new WebException('Gagal mengupdate status visit');
    } catch (\Throwable $th) {
      throw new WebException($th->getMessage());
    }
  }

  public function proses($request, $id)
  {
    try {
      $ubahpaket = $this->ubahpaket->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    try {
      if ($ubahpaket->status_proses === 'Berhasil' || $ubahpaket->status_proses === 'Gagal') {
        throw new WebException('Gagal mengupdate data, status proses sudah diupdate');
      }
      $update = $ubahpaket->update([
        'keterangan_proses' => $request['keterangan_proses'],
        'status_proses' => $request['status_proses']
      ]);
      if ($update) {
        DB::commit();
        return [
          'status' => true,
          'code' => 200,
          'message' => "Berhasil mengupdate status proses",
        ];
      }
      throw new WebException('Gagal mengupdate status proses');
    } catch (\Throwable $th) {
      throw new WebException($th->getMessage());
    }
  }
  public function pembayaran($request, $id)
  {

    $username = auth()->user()->name;
    try {
      $ubahpaket = $this->ubahpaket->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($ubahpaket)) {
      try {
        $transaksi = Transaksi::find($ubahpaket->transaksi_id);
        if (!isset($transaksi)) {
          throw new WebException('Data transaksi tidak ditemukan');
        }
        if ($transaksi->status === 'lunas') {
          throw new WebException('Gagal melakukan pembayaran, pembayaran sudah pernah dilakukan');
        }
        if ($request['bayar'] < $request['biaya']) {
          throw new WebException('Jumlah pembayaran kurang dari total biaya');
        }
        $isUpdate = $transaksi->update([
          'user_action' => $username,
          'tgl_action' => $request['tgl_action'],
          'status' => $request['status'],
          'biaya' => $request['biaya'],
          'diskon' => $request['diskon'],
          'bayar' => $request['bayar'],
          'keterangan' => $request['keterangan'],
        ]);
        if ($isUpdate) {
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil mengupdate pembayaran",
          ];
        }
      } catch (\Throwable $th) {
        throw new WebException($th->getMessage());
      }
    }
  }
  public function cetakNota($id)
  {
    try {
      $ubahpaket = $this->ubahpaket->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }

    DB::beginTransaction();
    try {
      $customer = $ubahpaket->pelanggan;
      if (!isset($customer)) {
        throw new WebException('Data Pelanggan tidak ditemukan');
      }
      $pdf = Pdf::loadView('pages.nota.invoice-ubah-paket', ['customer' => $customer, 'ubahpaket' => $ubahpaket]);
      $pdf->setPaper(array(0, 0, 250, 500), 'portrait');
      $filename = $customer->no_pelanggan . '_' . $customer->pemasangan->nama . '.pdf';
      // $pdf->save(storage_path('invoices') . '/' . $filename);

      DB::commit();

      return $pdf->download($filename);
    } catch (\Exception $e) {
      throw new WebException($e->getMessage());
    }
  }
}
