<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
        'password',
    ];



    // password mutator
    public function setKataSandiAttribute($value)
    {
        if (\Illuminate\Support\Str::startsWith($value, '$2y$')) {
            $this->attributes['kata_sandi'] = $value; // already hashed
        } else {
            $this->attributes['kata_sandi'] = bcrypt($value);
        }
    }


    // relationships
    public function laporan()
    {
        return $this->hasMany(LaporanPenjualan::class, 'id_pengguna');
    }


    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'id_pelanggan');
    }


    public function verifikasiAdmin()
    {
        return $this->hasMany(Verifikasi::class, 'id_admin');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
