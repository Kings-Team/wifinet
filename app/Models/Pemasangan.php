<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasangan extends Model
{
    use HasFactory;

    protected $table = 'pemasangan';

    protected $fillable = [
        'no_pendaftaran',
        'nama',
        'nik',
        'alamat',
        'telepon',
        'user_survey',
        'status_survey',
        'keterangan_survey',
        'status_instalasi',
        'status_aktivasi',
        'paket_id',
        'transaksi_id',
        'mitra_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pemasangan) {
            $pemasangan->no_pendaftaran = 'IDP' . now()->year . static::generateRandomString(5);
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

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'pemasangan_id');
    }
}
