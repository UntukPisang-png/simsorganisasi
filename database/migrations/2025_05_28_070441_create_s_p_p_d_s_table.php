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
        Schema::create('s_p_p_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('perintah_dari');
            $table->text('maksud');
            $table->string('berangkat');
            $table->string('tujuan');
            $table->string('angkutan');
            $table->string('lama_perjadin');
            $table->date('tgl_berangkat');
            $table->date('tgl_kembali');
            $table->text('pengikut');
            $table->date('tgl_lahir');
            $table->text('keterangan_pengikut');
            $table->string('bebasbiaya_instansi');
            $table->string('bebasbiaya_akun');
            $table->text('keterangan');
            $table->date('tgl_surat');
            $table->string('jabatan_ttd');
            $table->string('nama_ttd');
            $table->string('pangkat_ttd');
            $table->string('nip_ttd');
            $table->text('catatan_lembar2');
            $table->foreignId('pegawai_id');
            $table->foreignId('surat_tugas_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('s_p_p_d_s');
    }
};
