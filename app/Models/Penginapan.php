<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penginapan extends Model
{
    protected $fillable = ['nama', 'deskripsi', 'lokasi', 'harga_per_malam', 'gambar'];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_penginapan');
    }
}

