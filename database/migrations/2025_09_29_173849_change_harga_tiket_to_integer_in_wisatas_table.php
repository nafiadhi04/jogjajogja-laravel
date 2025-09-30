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
        Schema::table('wisatas', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi INTEGER
            $table->integer('harga_tiket')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wisatas', function (Blueprint $table) {
            // Mengembalikan ke tipe desimal jika migrasi di-rollback
            $table->decimal('harga_tiket', 10, 2)->default(0)->change();
        });
    }
};