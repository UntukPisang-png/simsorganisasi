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
        Schema::create('surat_dinas', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->date('tgl_surat');
            $table->string('sifat');
            $table->string('lampiran');
            $table->string('perihal');
            $table->string('kepada');
            $table->string('tempat');
            $table->text('isi_surat');
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
        Schema::dropIfExists('surat_dinas');
    }
};
