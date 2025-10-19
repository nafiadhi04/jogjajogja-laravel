<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Penginapan extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $guarded = ['id'];
    protected $appends = ['viewable_map_url'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($penginapan) {
            if (!$penginapan->slug) {
                $penginapan->slug = Str::slug($penginapan->nama);
            }
        });
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

    // Scope untuk filter kategori
    public function scopeKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    // Scope untuk filter periode
    public function scopePeriode($query, $periode)
    {
        if ($periode) {
            return $query->where('periode', $periode);
        }
        return $query;
    }

    // Scope untuk search
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('alamat', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }
        return $query;
    }

    protected function extractEmbedTokens(string $str): array
    {
        // mengembalikan array associative ['lat'=>..., 'lon'=>..., 'meters'=>..., 'name'=>..., 'place_id'=>...]
        $out = ['lat' => null, 'lon' => null, 'meters' => null, 'name' => null, 'place_token' => null];

        // 1) cari !2d (lon) dan !3d (lat) — preferensi untuk embed format
        if (preg_match('/!2d(-?\d+\.\d+)!3d(-?\d+\.\d+)/', $str, $m)) {
            $out['lon'] = $m[1];
            $out['lat'] = $m[2];
        } elseif (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $str, $m)) {
            // kadang ada !3d...!4d...
            $out['lat'] = $m[1];
            $out['lon'] = $m[2];
        } elseif (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $str, $m)) {
            $out['lat'] = $m[1];
            $out['lon'] = $m[2];
        }

        // 2) cari !1d (angka jarak/scale yang muncul di embed pb)
        if (preg_match('/!1d([0-9\.]+)/', $str, $m1)) {
            // ini sering berisi angka seperti 4524.9207 yang merepresentasikan "extent" pada embed.
            $out['meters'] = round(floatval($m1[1]));
        }

        // 3) cari nama tempat token !2s... atau !1s...
        if (preg_match('/!2s([^!]+)/', $str, $m2)) {
            $name = urldecode($m2[1]);
            $name = str_replace('+', ' ', $name);
            $out['name'] = trim($name);
        } elseif (preg_match('/!1s([^!]+)/', $str, $m3)) {
            $name = urldecode($m3[1]);
            $name = str_replace('+', ' ', $name);
            $out['name'] = trim($name);
        }

        // 4) tempat id token (contoh 0x2e70...:0x86...) kadang di !1s pertama — simpan sebagai place_token jika ada
        if (preg_match('/!1s([^!]+)/', $str, $m4)) {
            $out['place_token'] = urldecode($m4[1]);
        }

        return $out;
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
