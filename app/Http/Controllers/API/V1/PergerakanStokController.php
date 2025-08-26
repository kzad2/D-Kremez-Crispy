<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\PergerakanStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PergerakanStokController extends Controller
{
    /**
     * Get all stock movements with pagination
     * GET /API/V1/pergerakan-stok
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $pergerakan = PergerakanStok::with(['produk'])
                                    ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pergerakan
        ]);
    }

    /**
     * Create new stock movement
     * POST /API/V1/pergerakan-stok
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_produk' => 'required|exists:produk,id',
            'jenis_referensi' => 'required|in:laporan,manual,retur,rusak',
            'id_referensi' => 'required|integer',
            'jumlah_perubahan' => 'required|integer',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pergerakan = PergerakanStok::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stock movement created successfully',
            'data' => $pergerakan->load(['produk'])
        ], 201);
    }

    /**
     * Get specific stock movement
     * GET /API/V1/pergerakan-stok/{id}
     */
    public function show($id)
    {
        $pergerakan = PergerakanStok::with(['produk'])->find($id);

        if (!$pergerakan) {
            return response()->json([
                'success' => false,
                'message' => 'Stock movement not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pergerakan
        ]);
    }

    /**
     * Update stock movement
     * PUT /API/V1/pergerakan-stok/{id}
     */
    public function update(Request $request, $id)
    {
        $pergerakan = PergerakanStok::find($id);

        if (!$pergerakan) {
            return response()->json([
                'success' => false,
                'message' => 'Stock movement not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jenis_referensi' => 'in:laporan,manual,retur,rusak',
            'id_referensi' => 'integer',
            'jumlah_perubahan' => 'integer',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pergerakan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stock movement updated successfully',
            'data' => $pergerakan->load(['produk'])
        ]);
    }

    /**
     * Delete stock movement
     * DELETE /API/V1/pergerakan-stok/{id}
     */
    public function destroy($id)
    {
        $pergerakan = PergerakanStok::find($id);

        if (!$pergerakan) {
            return response()->json([
                'success' => false,
                'message' => 'Stock movement not found'
            ], 404);
        }

        $pergerakan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stock movement deleted successfully'
        ]);
    }

    /**
     * Get stock movements by product ID
     * GET /API/V1/pergerakan-stok/produk/{produkId}
     */
    public function getByProduk($produkId)
    {
        $pergerakan = PergerakanStok::where('id_produk', $produkId)
                                    ->orderBy('tanggal_pergerakan', 'desc')
                                    ->get();

        return response()->json([
            'success' => true,
            'data' => $pergerakan
        ]);
    }

    /**
     * Get stock movements by type
     * GET /API/V1/pergerakan-stok/jenis/{jenis}
     */
    public function getByJenis($jenis)
    {
        $pergerakan = PergerakanStok::with(['produk'])
                                    ->where('jenis_referensi', $jenis)
                                    ->get();

        return response()->json([
            'success' => true,
            'data' => $pergerakan
        ]);
    }

    /**
     * Get stock summary for a product
     * GET /API/V1/pergerakan-stok/summary/{produkId}
     */
    public function getStokSummary($produkId)
    {
        $pergerakan = PergerakanStok::where('id_produk', $produkId)->get();

        $totalMasuk = $pergerakan->where('jumlah_perubahan', '>', 0)->sum('jumlah_perubahan');
        $totalKeluar = abs($pergerakan->where('jumlah_perubahan', '<', 0)->sum('jumlah_perubahan'));
        $stokAkhir = $totalMasuk - $totalKeluar;

        $summary = [
            'total_masuk' => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'stok_akhir' => $stokAkhir,
            'total_transaksi' => $pergerakan->count(),
            'by_type' => [
                'laporan' => $pergerakan->where('jenis_referensi', 'laporan')->sum('jumlah_perubahan'),
                'manual' => $pergerakan->where('jenis_referensi', 'manual')->sum('jumlah_perubahan'),
                'retur' => $pergerakan->where('jenis_referensi', 'retur')->sum('jumlah_perubahan'),
                'rusak' => $pergerakan->where('jenis_referensi', 'rusak')->sum('jumlah_perubahan')
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}
