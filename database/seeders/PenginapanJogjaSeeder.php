<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penginapan;
use App\Models\User;

class PenginapanJogjaSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada user
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }

        $penginapans = [
            [
                'user_id' => $user->id,
                'nama' => 'Villa Tamu Ibu',
                'slug' => 'villa-tamu-ibu',
                'deskripsi' => 'Villa mewah dengan kolam renang pribadi dan pemandangan yang indah di kawasan sejuk Kaliurang',
                'lokasi' => 'Jl. Kaliurang KM 12, Sleman, Kawasan sejuk dengan udara pegunungan',
                'tipe' => 'Villa',
                'kategori' => 'villa',
                'periode' => 'hari',
                'kota' => 'Yogyakarta',
                'harga' => 2000000,
                'periode_harga' => 'hari',
                'thumbnail' => 'villa-tamu-ibu.jpg',
                'views' => 46
            ],
            [
                'user_id' => $user->id,
                'nama' => 'Marina Villa',
                'slug' => 'marina-villa',
                'deskripsi' => 'Villa modern dengan kolam renang dan gazebo dekat pantai Parangtritis',
                'lokasi' => 'Jl. Parangtritis KM 8, Bantul, Dekat pantai Parangtritis',
                'tipe' => 'Villa',
                'kategori' => 'villa',
                'periode' => 'malam',
                'kota' => 'Yogyakarta',
                'harga' => 470000,
                'periode_harga' => 'malam',
                'thumbnail' => 'marina-villa.jpg',
                'views' => 61
            ],
            [
                'user_id' => $user->id,
                'nama' => 'Homestay Sukunan',
                'slug' => 'homestay-sukunan',
                'deskripsi' => 'Homestay nyaman dengan konsep co-living modern dekat UGM',
                'lokasi' => 'Jl. Sukunan, Banyuraden, Gamping, Sleman',
                'tipe' => 'Homestay',
                'kategori' => 'homestay',
                'periode' => 'hari',
                'kota' => 'Yogyakarta',
                'harga' => 800000,
                'periode_harga' => 'hari',
                'thumbnail' => 'homestay-sukunan.jpg',
                'views' => 24
            ]
        ];

        foreach ($penginapans as $penginapan) {
            Penginapan::create($penginapan);
        }
    }
}