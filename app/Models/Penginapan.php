<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penginapan extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $guarded = ['id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    // Relasi ke User (Author)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Fasilitas (Many-to-Many)
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_penginapan');
    }

    // Relasi ke Gambar (One-to-Many)
    public function gambar()
    {
        return $this->hasMany(GambarPenginapan::class);
    }
}