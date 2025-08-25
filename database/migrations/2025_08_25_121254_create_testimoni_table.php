<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->constrained('pengguna')->onDelete('cascade');
            $table->text('konten');
            $table->tinyInteger('penilaian');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('dimoderasi_oleh')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamp('dimoderasi_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimoni');
    }
};
