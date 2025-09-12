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
        Schema::create('gambar_penginapans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penginapan_id')->constrained('penginapans')->onDelete('cascade');
            $table->string('path_gambar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_penginapans');
    }
};
