<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat user admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            // Menggunakan Hash::make untuk mengenkripsi password
            'password' => Hash::make('123'),
        ]);

        // 2. Buat user member biasa
        User::factory()->create([
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'role' => 'member',
            'password' => Hash::make('123'),
        ]);
    }
}