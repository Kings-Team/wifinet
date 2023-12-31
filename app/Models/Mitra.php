<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mitra',
    ];

    protected $table = 'mitra';


    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function pemasangan()
    {
        return $this->hasMany(Pemasangan::class);
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
