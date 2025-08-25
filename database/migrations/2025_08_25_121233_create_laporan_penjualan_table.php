<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->constrained('pengguna')->onDelete('cascade');
            $table->date('tanggal_laporan');
            $table->decimal('total_omzet', 14, 2);
            $table->text('catatan')->nullable();
            $table->enum('status', ['draf', 'dikirim', 'ditandai', 'disetujui', 'ditolak'])->default('draf');
            $table->timestamp('dikirim_pada');
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_penjualan');
    }
};
