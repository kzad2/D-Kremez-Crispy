<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PergerakanStok extends Model
{
    use HasFactory;

    protected $table = 'pergerakan_stok';

    public $timestamps = false;

    protected $fillable = [
        'id_produk',
        'jenis_referensi',
        'id_referensi',
        'tanggal_pergerakan',
        'jumlah_perubahan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pergerakan' => 'datetime',
        'jumlah_perubahan' => 'integer',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
