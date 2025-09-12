<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $fillable = ['nama'];

    public function wisatas()
    {
        return $this->belongsToMany(Wisata::class, 'fasilitas_wisata');
    }

    public function penginapans()
    {
        return $this->belongsToMany(Penginapan::class, 'fasilitas_penginapan');
    }
}

