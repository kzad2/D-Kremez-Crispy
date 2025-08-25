<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimoni';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_pelanggan',
        'konten',
        'penilaian',
        'status',
        'dimoderasi_oleh',
        'dimoderasi_pada',
    ];

    protected $casts = [
        'penilaian' => 'integer',
        'dimoderasi_pada' => 'datetime',
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pengguna::class, 'id_pelanggan');
    }

    public function moderator()
    {
        return $this->belongsTo(Pengguna::class, 'dimoderasi_oleh');
    }
}
