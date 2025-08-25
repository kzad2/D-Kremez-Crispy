<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

//bahasa indonesia

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Jalankan migrasi.
//      */
//     public function up(): void
//     {
//         Schema::create('pengguna', function (Blueprint $table) {
//             $table->id(); // BIGINT AUTO_INCREMENT PRIMARY KEY
//             $table->string('nama', 100); // VARCHAR(100) NOT NULL
//             $table->string('email', 150)->unique(); // VARCHAR(150) NOT NULL UNIQUE
//             $table->string('kata_sandi', 255); // VARCHAR(255) NOT NULL
//             $table->enum('peran', ['pelanggan', 'karyawan', 'admin', 'pemilik']); // ENUM
//             $table->boolean('aktif')->default(true); // BOOLEAN DEFAULT true
//             $table->timestamp('dibuat_pada')->useCurrent(); // TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//             $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate(); // TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
//         });

//         Schema::create('token_reset_kata_sandi', function (Blueprint $table) {
//             $table->string('email')->primary();
//             $table->string('token');
//             $table->timestamp('dibuat_pada')->nullable();
//         });

//         Schema::create('sesi', function (Blueprint $table) {
//             $table->string('id')->primary();
//             $table->foreignId('id_pengguna')->nullable()->index();
//             $table->string('alamat_ip', 45)->nullable();
//             $table->text('agen_pengguna')->nullable();
//             $table->longText('muatan');
//             $table->integer('aktivitas_terakhir')->index();
//         });
//     }

//     /**
//      * Batalkan migrasi.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('pengguna');
//         Schema::dropIfExists('token_reset_kata_sandi');
//         Schema::dropIfExists('sesi');
//     }
// };
