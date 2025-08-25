<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama',
        'kode_produk',
        'harga',
        'satuan',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    public function detailLaporanPenjualan()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'id_produk');
    }

    public function pergerakanStok()
    {
        return $this->hasMany(PergerakanStok::class, 'id_produk');
    }
}
