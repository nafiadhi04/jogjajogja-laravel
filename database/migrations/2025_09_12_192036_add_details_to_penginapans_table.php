<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('penginapans', function (Blueprint $table) {
            // Tambahkan relasi ke user (author) setelah 'id'
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');

            // Hapus harga_per_malam yang kurang fleksibel
            $table->dropColumn('harga_per_malam');

            // Tambahkan kolom baru yang lebih detail setelah 'lokasi'
            $table->string('tipe')->after('lokasi'); // hotel, villa, guesthouse
            $table->string('kota')->after('tipe');
            $table->integer('harga')->after('kota');
            $table->string('periode_harga')->after('harga'); // Harian, Mingguan, Bulanan

            // Ganti 'gambar' menjadi 'thumbnail' untuk gambar utama
            $table->renameColumn('gambar', 'thumbnail');

            // Tambahkan kolom views
            $table->unsignedInteger('views')->default(0)->after('thumbnail');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penginapans', function (Blueprint $table) {
            //
        });
    }
};
