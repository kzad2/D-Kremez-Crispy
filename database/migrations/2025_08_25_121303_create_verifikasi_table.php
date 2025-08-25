<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_laporan')->constrained('laporan_penjualan')->onDelete('cascade');
            $table->foreignId('id_admin')->constrained('pengguna')->onDelete('cascade');
            $table->enum('status', ['disetujui', 'ditolak']);
            $table->text('alasan')->nullable();
            $table->timestamp('diverifikasi_pada')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('verifikasi');
    }
};
