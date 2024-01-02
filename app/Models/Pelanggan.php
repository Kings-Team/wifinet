<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'inc_no',
        'no_pelanggan',
        'username_pppoe',
        'password_pppoe',
        'tgl_pasang',
        'tgl_isolir',
        'status_aktif',
        'aktivasi_router',
        'aktivasi_olt',
        'cara_bayar',
        'paket_id',
        'onu_id',
        'port_id',
        'odp_port_id',
        'router_id',
        'olt_id',
        'pemasangan_id',
        'mitra_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pelanggan) {
            $noPelanggan = $pelanggan->mitra_id . now()->year . static::generateRandomString(5);
            $pelanggan->no_pelanggan = $noPelanggan;
            $pelanggan->username_pppoe = $noPelanggan;
        });
    }

    protected static function generateRandomString($length = 5)
    {
        $characters = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public function pemasangan()
    {
        return $this->belongsTo(Pemasangan::class, 'pemasangan_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
}
