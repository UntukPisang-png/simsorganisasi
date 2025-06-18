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
        Schema::create('telaahan_stafs', function (Blueprint $table) {
            $table->id();
            $table->text('kepada');
            $table->text('dari');
            $table->date('tgl_surat');
            $table->string('no_surat');
            $table->string('lampiran');
            $table->string('perihal');
            $table->text('persoalan');
            $table->text('praanggapan');
            $table->text('fakta');
            $table->text('analisis');
            $table->text('saran');
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
        Schema::dropIfExists('telaahan_stafs');
    }
};
