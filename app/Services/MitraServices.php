<?php

namespace App\Services;

use App\Models\Mitra;
use Illuminate\Support\Facades\DB;

class MitraServices
{
    private Mitra $mitra;

    public function __construct()
    {
        $this->mitra = new Mitra();
    }

    public function fetchAll()
    {
        $data = $this->mitra->all();
        return compact('data');
    }

    public function add($request)
    {
        DB::beginTransaction();

        try {
            $isCreated = $this->mitra->create([
                'nama_mitra' => $request['nama_mitra']
            ]);

            if (isset($isCreated)) {
                DB::commit();
            }

        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }

        return true;
    }

    public function update($request, $id)
    {
        try {
            $mitra = $this->mitra->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return false;
        }

        DB::beginTransaction();

        try {
            $update = $mitra->update([
                'nama_mitra' => $request['nama_mitra']
            ]);

            if ($update) {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();

            return false;
        }

        return true;
    }
}
