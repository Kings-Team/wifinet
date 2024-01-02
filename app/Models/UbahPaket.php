<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbahPaket extends Model
{
    use HasFactory;

    protected $table = 'ubah_paket';

    protected $fillable = [
        'pelanggan_id',
        'paket_lama',
        'paket_baru_id',
        'transaksi_id',
        'status_visit',
        'status_proses',
        'keterangan_proses',
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_baru_id', 'id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }
}
