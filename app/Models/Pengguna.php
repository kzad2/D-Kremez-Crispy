<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
        'aktif',
    ];

    protected $hidden = [
        'kata_sandi',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    public function laporanPenjualan()
    {
        return $this->hasMany(LaporanPenjualan::class, 'id_pengguna');
    }

    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'id_pelanggan');
    }

    public function moderasiTestimoni()
    {
        return $this->hasMany(Testimoni::class, 'dimoderasi_oleh');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'id_admin');
    }
}
