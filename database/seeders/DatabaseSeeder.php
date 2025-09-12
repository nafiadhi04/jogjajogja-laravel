<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Import DB

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
        User::truncate();

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // === BAGIAN UNTUK MEMBUAT DATA BARU ===

        // 1. Buat user admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin', // Pastikan Anda punya kolom 'role' di tabel users
        ]);

        // 2. Buat user member biasa
        User::factory()->create([
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'role' => 'member', // Sesuaikan 'member' jika nama role Anda berbeda
        ]);

        // 3. Panggil seeder lain yang kita butuhkan
        $this->call([
            FasilitasSeeder::class,
            // Anda bisa tambahkan seeder lain di sini nanti
        ]);
    }
}