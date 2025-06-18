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
        Schema::create('laporan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('no_laporan');
            $table->text('umum');
            $table->text('landasan');
            $table->text('maksud');
            $table->text('kegiatan');
            $table->text('hasil');
            $table->text('kesimpulan');
            $table->text('penutup');
            $table->text('paraf');
            $table->string('jabatan_ttd');
            $table->string('nama_ttd');
            $table->string('nip_ttd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatans');
    }
};
