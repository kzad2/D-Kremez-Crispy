<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pergerakan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk')->onDelete('cascade');
            $table->enum('jenis_referensi', ['laporan', 'manual', 'retur', 'rusak']);
            $table->bigInteger('id_referensi');
            $table->timestamp('tanggal_pergerakan')->useCurrent();
            $table->integer('jumlah_perubahan');
            $table->text('catatan')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pergerakan_stok');
    }
};
