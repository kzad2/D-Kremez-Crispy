<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    use HasFactory;

    protected $table = 'laporan_penjualan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_pengguna',
        'tanggal_laporan',
        'total_omzet',
        'catatan',
        'status',
        'dikirim_pada',
        'disetujui_pada',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'total_omzet' => 'decimal:2',
        'dikirim_pada' => 'datetime',
        'disetujui_pada' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function detailLaporan()
    {
        return $this->hasMany(DetailLaporanPenjualan::class, 'id_laporan');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'id_laporan');
    }
}
