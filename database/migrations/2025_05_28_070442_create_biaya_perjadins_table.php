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
        Schema::create('biaya_perjadins', function (Blueprint $table) {
            $table->id();
            $table->string('uang_harian');
            $table->longText('penginapan');
            $table->longText('transportasi');
            $table->foreignId('s_p_p_d_id');
            $table->foreignId('pegawai_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_perjadins');
    }
};
