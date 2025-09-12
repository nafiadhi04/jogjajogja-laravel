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
        Schema::table('penginapans', function (Blueprint $table) {
            // Mengubah tipe kolom 'lokasi' menjadi TEXT
            $table->text('lokasi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penginapans', function (Blueprint $table) {
            // Mengembalikan tipe kolom jika migration di-rollback
            $table->string('lokasi', 255)->nullable()->change();
        });
    }
};