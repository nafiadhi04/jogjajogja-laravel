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
        Schema::table('penginapans', function (Blueprint $table) {
            // Tambahkan kolom kategori dan periode jika belum ada
            if (!Schema::hasColumn('penginapans', 'kategori')) {
                $table->string('kategori')->nullable()->after('tipe');
            }
            if (!Schema::hasColumn('penginapans', 'periode')) {
                $table->string('periode')->nullable()->after('kategori');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penginapans', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'periode']);
        });
    }
};