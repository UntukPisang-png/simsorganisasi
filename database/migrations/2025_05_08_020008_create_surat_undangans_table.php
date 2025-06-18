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
        Schema::create('surat_undangans', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat');
            $table->string('lampiran');
            $table->string('perihal');
            $table->date('tgl_surat');
            $table->string('kepada');
            $table->string('di');
            $table->text('isi_surat');
            $table->date('tgl_undangan');
            $table->string('tempat_undangan');
            $table->time('waktu_undangan');
            $table->text('penutup');
            $table->string('nama_ttd');
            $table->string('jabatan_ttd');
            $table->string('nip_ttd');
            $table->foreignId('surat_keluar_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_undangans');
    }
};
