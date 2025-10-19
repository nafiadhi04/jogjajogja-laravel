<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengubah nilai default untuk kolom 'role' menjadi 'silver'
            // untuk semua pengguna baru yang mendaftar.
            $table->string('role')->default('silver')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan nilai default ke 'member' jika migrasi di-rollback
            $table->string('role')->default('member')->change();
        });
    }
};