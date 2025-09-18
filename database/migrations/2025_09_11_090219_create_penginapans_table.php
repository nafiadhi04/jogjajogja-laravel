<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penginapans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique(); // Untuk URL friendly
            $table->text('deskripsi');
            $table->string('lokasi')->nullable();
            $table->string('tipe')->default('villa'); // villa, hotel, homestay, resort
            $table->string('kota')->default('Yogyakarta'); // Untuk filter kota
            $table->decimal('harga_per_malam', 10, 2)->nullable();
            $table->string('periode_harga')->default('malam'); // malam, hari, minggu, bulan
            $table->string('gambar')->nullable(); // Tetap pakai field gambar existing
            $table->string('thumbnail')->nullable(); // Tambahan untuk thumbnail
            $table->json('fasilitas')->nullable(); // Untuk list fasilitas
            $table->integer('kapasitas')->default(2); // Jumlah orang
            $table->decimal('rating', 3, 1)->default(4.5); // Rating 1-5
            $table->integer('jumlah_review')->default(0); // Jumlah review
            $table->integer('views')->default(0); // Counter view
            $table->boolean('is_rekomendasi')->default(false); // Badge rekomendasi
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penginapans');
    }
};