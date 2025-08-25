<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_laporan_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_laporan')->constrained('laporan_penjualan')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah_terjual');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('total_harga', 14, 2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_laporan_penjualan');
    }
};
