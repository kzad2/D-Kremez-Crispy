<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\PenggunaController;
use App\Http\Controllers\API\V1\ProdukController;
use App\Http\Controllers\API\V1\LaporanPenjualanController;
use App\Http\Controllers\API\V1\DetailLaporanPenjualanController;
use App\Http\Controllers\API\V1\PergerakanStokController;
use App\Http\Controllers\API\V1\TestimoniController;
use App\Http\Controllers\API\V1\VerifikasiController;
use App\Http\Controllers\API\V1\PengaturanTampilanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// All routes under API V1
Route::prefix('V1')->group(function () {

    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    });

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {

        // Pengguna Routes
        Route::apiResource('pengguna', PenggunaController::class);
        Route::put('pengguna/{id}/toggle-status', [PenggunaController::class, 'toggleStatus']);
        Route::get('pengguna/role/{role}', [PenggunaController::class, 'getByRole']);

        // Produk Routes
        Route::apiResource('produk', ProdukController::class);
        Route::put('produk/{id}/toggle-status', [ProdukController::class, 'toggleStatus']);
        Route::get('produk/search/{keyword}', [ProdukController::class, 'search']);
        Route::get('produk/active', [ProdukController::class, 'getActive']);

        // Laporan Penjualan Routes
        Route::apiResource('laporan-penjualan', LaporanPenjualanController::class);
        Route::put('laporan-penjualan/{id}/status', [LaporanPenjualanController::class, 'updateStatus']);
        Route::get('laporan-penjualan/user/{userId}', [LaporanPenjualanController::class, 'getByUser']);
        Route::get('laporan-penjualan/date-range/{startDate}/{endDate}', [LaporanPenjualanController::class, 'getByDateRange']);
        Route::get('laporan-penjualan/{id}/summary', [LaporanPenjualanController::class, 'getSummary']);

        // Detail Laporan Penjualan Routes
        Route::apiResource('detail-laporan-penjualan', DetailLaporanPenjualanController::class);
        Route::get('detail-laporan-penjualan/laporan/{laporanId}', [DetailLaporanPenjualanController::class, 'getByLaporan']);
        Route::get('detail-laporan-penjualan/produk/{produkId}', [DetailLaporanPenjualanController::class, 'getByProduk']);

        // Pergerakan Stok Routes
        Route::apiResource('pergerakan-stok', PergerakanStokController::class);
        Route::get('pergerakan-stok/produk/{produkId}', [PergerakanStokController::class, 'getByProduk']);
        Route::get('pergerakan-stok/jenis/{jenis}', [PergerakanStokController::class, 'getByJenis']);
        Route::get('pergerakan-stok/summary/{produkId}', [PergerakanStokController::class, 'getStokSummary']);

        // Testimoni Routes
        Route::apiResource('testimoni', TestimoniController::class);
        Route::put('testimoni/{id}/moderate', [TestimoniController::class, 'moderate']);
        Route::get('testimoni/status/{status}', [TestimoniController::class, 'getByStatus']);
        Route::get('testimoni/user/{userId}', [TestimoniController::class, 'getByUser']);

        // Verifikasi Routes
        Route::apiResource('verifikasi', VerifikasiController::class);
        Route::get('verifikasi/laporan/{laporanId}', [VerifikasiController::class, 'getByLaporan']);
        Route::get('verifikasi/admin/{adminId}', [VerifikasiController::class, 'getByAdmin']);

        // Pengaturan Tampilan Routes
        Route::apiResource('pengaturan-tampilan', PengaturanTampilanController::class);
        Route::put('pengaturan-tampilan/{id}/activate', [PengaturanTampilanController::class, 'activate']);
        Route::get('pengaturan-tampilan/active', [PengaturanTampilanController::class, 'getActive']);
    });

});
