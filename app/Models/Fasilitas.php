<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    public function penginapan()
    {
        return $this->belongsToMany(Penginapan::class, 'fasilitas_penginapans');
    }

    // Tambahkan relasi ini
    public function wisatas()
    {
        return $this->belongsToMany(Wisata::class, 'fasilitas_wisata');
    }
} 