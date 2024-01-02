<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_action',
        'tgl_action',
        'biaya',
        'diskon',
        'bayar',
        'status',
        'keterangan',
    ];

    protected $table = 'transaksi';


    public function pemasangan()
    {
        return $this->hasMany(Pemasangan::class);
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }

    public function ubahPaket()
    {
        return $this->hasMany(UbahPaket::class);
    }
}
