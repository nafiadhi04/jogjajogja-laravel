<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Pastikan ini ada
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Nonaktifkan pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Hapus data lama dengan truncate
        Fasilitas::truncate();

        // 3. Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Siapkan data baru
        $fasilitas = [
            ['nama' => 'Wi-Fi'],
            ['nama' => 'AC'],
            ['nama' => 'Kolam Renang'],
            ['nama' => 'Parkir Mobil'],
            ['nama' => 'Restoran'],
            ['nama' => 'Resepsionis 24 Jam'],
            ['nama' => 'Akses Kursi Roda'],
            ['nama' => 'Pusat Kebugaran (Gym)'],
            ['nama' => 'Spa'],
            ['nama' => 'Antar-Jemput Bandara'],
            ['nama' => 'Sarapan Gratis'],
            ['nama' => 'Dapur'],
        ];

        // 5. Masukkan data baru ke dalam tabel fasilitas
        Fasilitas::insert($fasilitas);
    }
}
