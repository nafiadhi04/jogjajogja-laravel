<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wisatas', function (Blueprint $table) {
            
            // SOLUSI: Menambahkan kolom user_id yang hilang (foreign key ke tabel users).
            // Ini harus dilakukan sebelum menambahkan kolom 'status' yang menggunakannya sebagai referensi.
            if (!Schema::hasColumn('wisatas', 'user_id')) {
                // Tambahkan foreignId yang otomatis menjadi unsignedBigInteger dan constrained()
                // untuk membuat foreign key ke tabel 'users' (secara default).
                $table->foreignId('user_id')->constrained()->after('id');
            }
            
            // 1. Menambahkan kolom-kolom baru yang dibutuhkan
            // Baris ini sekarang aman karena 'user_id' sudah ada
            $table->string('status')->after('user_id')->default('verifikasi');
            $table->text('catatan_revisi')->after('status')->nullable();
            $table->string('tipe')->after('deskripsi'); // Contoh: Alam, Budaya, Kuliner
            $table->string('kota')->after('tipe');
            $table->unsignedInteger('views')->default(0)->after('lokasi');

            // 2. Mengubah nama kolom yang sudah ada agar konsisten
            $table->renameColumn('harga', 'harga_tiket');
            $table->renameColumn('gambar', 'thumbnail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wisatas', function (Blueprint $table) {
            // Logika untuk mengembalikan perubahan jika diperlukan
            
            // Hapus foreign key dan kolom user_id
            if (Schema::hasColumn('wisatas', 'user_id')) {
                // Periksa apakah foreign key ada sebelum dihapus
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            $table->dropColumn(['status', 'catatan_revisi', 'tipe', 'kota', 'views']);
            $table->renameColumn('harga_tiket', 'harga');
            $table->renameColumn('thumbnail', 'gambar');
        });
    }
};
