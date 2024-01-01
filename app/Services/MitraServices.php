<?php

namespace App\Services;

use App\Exceptions\WebException;
use App\Models\Mitra;
use Illuminate\Support\Facades\DB;

class MitraServices
{
    private Mitra $mitra;

    public function __construct()
    {
        $this->mitra = new Mitra();
    }

    public function fetchAllMitra()
    {
        return Mitra::all();
    }

    public function addMitra($request)
    {
        DB::beginTransaction();

        try {
            $isCreated = $this->mitra->create([
                'nama_mitra' => $request['nama_mitra']
            ]);

            if (isset($isCreated)) {
                DB::commit();
                return [
                    'status' => true,
                    'code' => 201,
                    'message' => "Berhasil menambah mitra"
                ];
            }
        } catch (\Exception $e) {
            throw new WebException($e->getMessage());
        }
    }

    public function updateMitra($request, $id)
    {
        try {
            $mitra = $this->mitra->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new WebException($e->getMessage());
        }

        DB::beginTransaction();

        try {
            $update = $mitra->update([
                'nama_mitra' => $request['nama_mitra']
            ]);

            if ($update) {
                DB::commit();
                return [
                    'status' => true,
                    'code' => 200,
                    'message' => "Berhasil memperbarui mitra"
                ];
            }
        } catch (\Exception $e) {
            throw new WebException($e->getMessage());
        }
    }

    public function deleteMitra($id){
        try {
            $mitra = $this->mitra->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new WebException($e->getMessage());
        }

        DB::beginTransaction();
        if (isset($mitra)) {
            $delete = $mitra->delete();
            if ($delete) {
                DB::commit();
                return back()->with('message', 'Berhasil menghapus mitra');
            }
            throw new WebException('Gagal menghapus mitra, terjadi kesalahan');
        }
        throw new WebException('Gagal menghapus mitra, mitra tidak ditemukan');
    }
}
