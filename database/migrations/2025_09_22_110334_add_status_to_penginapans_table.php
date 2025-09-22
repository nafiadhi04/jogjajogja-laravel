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
            // Menambahkan kolom status setelah 'user_id'
            // Opsi: 'verifikasi', 'diterima', 'revisi'
            $table->string('status')->after('user_id')->default('verifikasi');

            // Menambahkan catatan revisi (opsional)
            $table->text('catatan_revisi')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penginapans', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_revisi']);
        });
    }
};