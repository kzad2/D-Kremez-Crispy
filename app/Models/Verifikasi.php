<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi';

    public $timestamps = false;

    protected $fillable = [
        'id_laporan',
        'id_admin',
        'status',
        'alasan',
        'diverifikasi_pada',
    ];

    protected $casts = [
        'diverifikasi_pada' => 'datetime',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanPenjualan::class, 'id_laporan');
    }

    public function admin()
    {
        return $this->belongsTo(Pengguna::class, 'id_admin');
    }
}
