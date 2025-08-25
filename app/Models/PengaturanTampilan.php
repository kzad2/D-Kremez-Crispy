<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanTampilan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_tampilan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama_tema',
        'path_logo',
        'warna_utama',
        'latar',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];
}
