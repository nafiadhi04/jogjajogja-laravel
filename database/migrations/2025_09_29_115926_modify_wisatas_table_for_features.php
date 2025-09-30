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
            // ==========================================================
            // PERBAIKAN UTAMA: Baris yang mencoba membuat 'user_id' dihapus
            // karena kolom tersebut sudah ada di tabel Anda.
            // ==========================================================
            
            // 1. Menambahkan kolom-kolom baru yang dibutuhkan
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
            $table->dropColumn(['status', 'catatan_revisi', 'tipe', 'kota', 'views']);
            $table->renameColumn('harga_tiket', 'harga');
            $table->renameColumn('thumbnail', 'gambar');
        });
    }
};
