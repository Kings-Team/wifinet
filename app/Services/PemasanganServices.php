<?php

namespace App\Services;

use App\Exceptions\WebException;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Pemasangan;
use App\Models\Transaksi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemasanganServices
{
  private Pemasangan $pemasangan;
  private Transaksi $transaksi;

  public function __construct()
  {
    $this->pemasangan = new Pemasangan();
    $this->transaksi = new Transaksi();
  }


  public function fetchAllPemasangan()
  {
    $userMitraId = auth()->user()->mitra_id;
    $roleAdmin = auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra);
    $roleSales = auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra);
    $username = auth()->user()->name;
    $paket = Paket::all();
    $sales = User::role('sales ' . auth()->user()->mitra->nama_mitra)->get();
    $teknisi = User::role('teknisi ' . auth()->user()->mitra->nama_mitra)->get();

    if ($roleAdmin || $roleSales) {
      $pemasangan = Pemasangan::with(['paket', 'transaksi'])
        ->whereHas('mitra', function ($query) use ($userMitraId) {
          $query->where('id', $userMitraId);
        })
        ->when($roleSales, function ($query) use ($username) {
          $query->where('user_survey', $username);
        })
        ->orderByDesc('id')
        ->get();
    } else {
      $pemasangan = Pemasangan::whereHas('transaksi', function ($query) use ($username) {
        $query->where('user_action', $username);
      })
        ->whereHas('mitra', function ($query) use ($userMitraId) {
          $query->where('id', $userMitraId);
        })
        ->orderByDesc('id')
        ->with(['paket', 'transaksi'])
        ->get();

      $pemasangan->load('pelanggan');
    }

    return compact('pemasangan', 'paket', 'sales', 'teknisi');
  }



  public function addPemasangan($request)
  {
    $userMitraId = auth()->user()->mitra_id;
    $roleAdmin = auth()->user()->hasRole('admin ' . auth()->user()->mitra->nama_mitra);
    $roleSales = auth()->user()->hasRole('sales ' . auth()->user()->mitra->nama_mitra);
    $username = auth()->user()->name;

    DB::beginTransaction();
    try {
      $idTransaksi = $this->transaksi->create([])->id;

      if ($roleAdmin || $roleSales) {
        $isCreated = $this->pemasangan->create([
          'nik' => $request['nik'],
          'nama' => $request['nama'],
          'alamat' => $request['alamat'],
          'mitra_id' => $userMitraId,
          'telepon' => $request['telepon'],
          'paket_id' => $request['paket_id'],
          'user_survey' => $roleSales ? $username : null,
          'status_survey' => 'belum survey',
          'transaksi_id' => $idTransaksi,
        ]);

        if ($isCreated) {
          DB::commit();
          return [
            'status' => true,
            'code' => 201,
            'message' => "Berhasil menambah data pemasangan",
          ];
        }
      }

      throw new WebException('Gagal menambah data pemasangan');
    } catch (\Exception $e) {
      throw new WebException($e->getMessage());
    }
  }

  public function updatePemasangan($request, $id)
  {
    try {
      $pemasangan = $this->pemasangan->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($pemasangan)) {
      try {
        $update = $pemasangan->update([
          'nama' => $request['nama'],
          'nik' => $request['nik'],
          'alamat' => $request['alamat'],
          'telepon' => $request['telepon'],
          'paket_id' => $request['paket_id'],
        ]);
        if ($update) {
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil mengupdate data pemasangan",
          ];
        }
        throw new WebException('Gagal mengupdate data pemasangan');
      } catch (\Throwable $th) {
        throw new WebException($th->getMessage());
      }
    }
  }

  public function assignmentSales($request, $id)
  {
    try {
      $pemasangan = $this->pemasangan->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($pemasangan)) {
      try {
        $update = $pemasangan->update([
          'user_survey' => $request['user_survey'],
        ]);
        if ($update) {
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil assignment data pemasangan ke sales",
          ];
        }
        throw new WebException('Gagal mengupdate data pemasangan');
      } catch (\Throwable $th) {
        throw new WebException($th->getMessage());
      }
    }
  }

  public function updateSurvey($request, $id)
  {
    try {
      $pemasangan = $this->pemasangan->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($pemasangan)) {
      try {
        if ($pemasangan->status_survey === 'berhasil survey' || $pemasangan->status_survey === 'gagal survey') {
          throw new WebException('Gagal mengupdate data, status survey sudah diupdate');
        }
        $update = $pemasangan->update([
          'status_survey' => $request['status_survey'],
          'keterangan_survey' => $request['keterangan_survey'],
        ]);
        if ($update) {
          $transaksi = Transaksi::find($pemasangan->transaksi_id);
          if (!isset($transaksi)) {
            throw new WebException('Data transaksi tidak ditemukan');
          }
          $transaksi->update([
            'tgl_action' => $request['tgl_action'],
          ]);
          if ($request['status_survey'] === 'berhasil survey') {
            $passwordPppoe = rand(10000000, 99999999);

            Pelanggan::create([
              'mitra_id' => $pemasangan->mitra_id,
              'paket_id' => $pemasangan->paket_id,
              'pemasangan_id' => $pemasangan->id,
              'password_pppoe' => $passwordPppoe,
            ]);
          }
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil mengupdate data survey pemasangan",
          ];
        }
        throw new WebException('Gagal mengupdate data pemasangan');
      } catch (\Throwable $th) {
        throw new WebException($th->getMessage());
      }
    }
  }
  public function assignmentTeknisi($request, $id)
  {
    try {
      $pemasangan = $this->pemasangan->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($pemasangan)) {
      try {
        if ($pemasangan->status_survey === 'belum survey') {
          throw new WebException('Gagal mengupdate data, status survey belum diupdate');
        }
        $transaksi = Transaksi::find($pemasangan->transaksi_id);
        if (!isset($transaksi)) {
          throw new WebException('Data transaksi tidak ditemukan');
        }
        $update = $transaksi->update([
          'user_action' => $request['user_action'],
        ]);
        if ($update) {
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil assignment data pemasangan ke teknisi",
          ];
        }
        throw new WebException('Gagal mengupdate data pemasangan');
      } catch (\Throwable $th) {
        throw new WebException($th->getMessage());
      }
    }
  }

  public function statusInstalasi($request, $id)
  {
    try {
      $pemasangan = $this->pemasangan->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($pemasangan)) {
      try {
        if ($pemasangan->status_instalasi === 'berhasil instalasi' || $pemasangan->status_instalasi === 'gagal instalasi') {
          throw new WebException('Gagal mengupdate data, status instalasi sudah diupdate');
        }
        $update = $pemasangan->update([
          'status_instalasi' => $request['status_instalasi'],
        ]);
        if ($update) {
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil update status instalasi",
          ];
        }
        throw new WebException('Gagal mengupdate status instalasi');
      } catch (\Throwable $th) {
        throw new WebException($th->getMessage());
      }
    }
  }

  public function statusAktivasi($request, $id)
  {
    try {
      $pemasangan = $this->pemasangan->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($pemasangan)) {
      try {
        if ($pemasangan->status_aktivasi === 'berhasil aktivasi' || $pemasangan->status_aktivasi === 'gagal aktivasi') {
          throw new WebException('Gagal mengupdate data, status aktivasi sudah diupdate');
        }
        $update = $pemasangan->update([
          'status_aktivasi' => $request['status_aktivasi'],
        ]);
        if ($update) {
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil update status aktivasi",
          ];
        }
        throw new WebException('Gagal mengupdate status aktivasi');
      } catch (\Throwable $th) {
        throw new WebException($th->getMessage());
      }
    }
  }

  public function pembayaran($request, $id)
  {
    try {
      $pemasangan = $this->pemasangan->findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }
    DB::beginTransaction();
    if (isset($pemasangan)) {
      try {
        $transaksi = Transaksi::find($pemasangan->transaksi_id);
        if (!isset($transaksi)) {
          throw new WebException('Data transaksi tidak ditemukan');
        }
        if ($transaksi->status === 'lunas') {
          throw new WebException('Gagal melakukan pembayaran, pembayaran sudah pernah dilakukan');
        }
        if ($request['bayar'] < $request['biaya']) {
          throw new WebException('Jumlah pembayaran kurang dari total biaya');
        }
        $update = $transaksi->update([
          'biaya' => $request['biaya'],
          'bayar' => $request['bayar'],
          'diskon' => $request['diskon'],
          'status' => $request['status'],
          'keterangan' => $request['keterangan'],
        ]);
        if ($update) {
          DB::commit();
          return [
            'status' => true,
            'code' => 200,
            'message' => "Berhasil update pembayaran",
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
      $pemasangan = Pemasangan::findOrFail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      throw new WebException($e->getMessage());
    }

    DB::beginTransaction();
    try {
      $customer = $pemasangan->pelanggan;
      if (!isset($customer)) {
        throw new WebException('Data Pelanggan tidak ditemukan');
      }
      $pdf = Pdf::loadView('pages.nota.invoice-pemasangan', ['customer' => $customer, 'pemasangan' => $pemasangan]);
      $pdf->setPaper(array(0, 0, 250, 500), 'portrait');
      $filename = $customer->no_pelanggan . '_' . $pemasangan->nama . '.pdf';
      // $pdf->save(storage_path('invoices') . '/' . $filename);

      DB::commit();

      return $pdf->download($filename);
    } catch (\Exception $e) {
      throw new WebException($e->getMessage());
    }
  }
}
