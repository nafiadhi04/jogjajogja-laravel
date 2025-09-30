<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GambarWisata extends Model
{
    use HasFactory;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Wisata.
     * Ini memberitahu Laravel bahwa setiap gambar galeri dimiliki oleh
     * satu data wisata.
     */
    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }
}

