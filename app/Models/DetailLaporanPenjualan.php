<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporanPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_laporan_penjualan';

    public $timestamps = false;

    protected $fillable = [
        'id_laporan',
        'id_produk',
        'jumlah_terjual',
        'harga_satuan',
        'total_harga',
    ];

    protected $casts = [
        'jumlah_terjual' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanPenjualan::class, 'id_laporan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
