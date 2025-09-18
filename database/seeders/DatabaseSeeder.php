<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key untuk mengizinkan truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus semua data lama dari tabel users
        // Ini akan dipindahkan ke seeder yang relevan jika diperlukan
        User::truncate();

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // Panggil semua seeder yang kita butuhkan di sini
        $this->call([
            UserSeeder::class,      // <-- Panggil UserSeeder yang baru
            FasilitasSeeder::class,
            // Anda bisa tambahkan seeder lain di sini nanti
        ]);
    }
}
