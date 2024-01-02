<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_paket',
        'iuran',
        'instalasi',
        'biaya_kolektor',
    ];

    protected $table = 'paket';

    public function pemasangan()
    {
        return $this->hasMany(Pemasangan::class);
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }

    public function ubahpaket()
    {
        return $this->hasMany(UbahPaket::class, 'paket_baru_id');
    }
}
