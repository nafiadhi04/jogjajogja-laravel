<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gambar_wisatas', function (Blueprint $table) {
            // Menambahkan kolom-kolom yang hilang
            $table->foreignId('wisata_id')->after('id')->constrained('wisatas')->onDelete('cascade');
            $table->string('path_gambar')->after('wisata_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gambar_wisatas', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropForeign(['wisata_id']);
            $table->dropColumn(['wisata_id', 'path_gambar']);
        });
    }
};
