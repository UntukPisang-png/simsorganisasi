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
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->string('sifat');
            $table->string('tindakan');
            $table->string('catatan');
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->onDelete('cascade'); // Relasi ke tabel SuratMasuk;
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade'); // Relasi ke tabel Pegawai;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
