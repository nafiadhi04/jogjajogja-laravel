<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    use HasFactory;

    /**
     * Melindungi dari mass assignment.
     * Kolom 'id' tidak boleh diisi secara manual.
     */
    protected $guarded = ['id'];

    /**
     * Memberitahu Laravel untuk menggunakan kolom 'slug'
     * saat mencari data dari URL.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relasi ke User (Author).
     * Setiap data wisata dimiliki oleh satu user.
     * withDefault() mencegah error jika user terkait telah dihapus.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'User Dihapus'
        ]);
    }

    /**
     * Relasi ke GambarWisata.
     * Setiap data wisata bisa memiliki banyak gambar galeri.
     */
    public function gambar()
    {
        return $this->hasMany(GambarWisata::class);
    }

    /**
     * Relasi ke Fasilitas (Many-to-Many).
     * Setiap data wisata bisa memiliki banyak fasilitas.
     */
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_wisata');
    }

    public function getViewableMapUrlAttribute(): string
    {
        // Jika latitude dan longitude ada di database, buat URL dari sana
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}";
        }

        // Jika tidak ada, kembalikan '#' sebagai fallback agar tidak error
        return '#';
    }
}

