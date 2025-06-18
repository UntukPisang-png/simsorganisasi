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
        Schema::create('nota_dinas', function (Blueprint $table) {
            $table->id();
            $table->text('kepada');
            $table->text('dari');
            $table->text('tembusan');
            $table->date('tgl_surat');
            $table->string('no_surat');
            $table->string('sifat');
            $table->string('lampiran');
            $table->string('perihal');
            $table->text('isi_surat');
            $table->text('detail_surat');
            $table->text('penutup');
            $table->text('paraf');
            $table->string('jabatan_ttd');
            $table->string('nama_ttd');
            $table->string('pangkat');
            $table->string('golongan');
            $table->string('nip_ttd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_dinas');
    }
};
