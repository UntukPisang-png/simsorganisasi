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
        Schema::create('biaya_riils', function (Blueprint $table) {
            $table->id();
            $table->longText('pengeluaran');
            $table->foreignId('s_p_p_d_id');
            $table->foreignId('biaya_perjadin_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_riils');
    }
};
