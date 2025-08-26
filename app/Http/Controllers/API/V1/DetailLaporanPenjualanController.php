<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\DetailLaporanPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailLaporanPenjualanController extends Controller
{
    /**
     * Get all sales report details
     * GET /API/V1/detail-laporan-penjualan
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $details = DetailLaporanPenjualan::with(['laporan', 'produk'])
                                         ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }

    /**
     * Create new sales report detail
     * POST /API/V1/detail-laporan-penjualan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_laporan' => 'required|exists:laporan_penjualan,id',
            'id_produk' => 'required|exists:produk,id',
            'jumlah_terjual' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $detail = DetailLaporanPenjualan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sales report detail created successfully',
            'data' => $detail->load(['laporan', 'produk'])
        ], 201);
    }

    /**
     * Get specific sales report detail
     * GET /API/V1/detail-laporan-penjualan/{id}
     */
    public function show($id)
    {
        $detail = DetailLaporanPenjualan::with(['laporan', 'produk'])->find($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report detail not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $detail
        ]);
    }

    /**
     * Update sales report detail
     * PUT /API/V1/detail-laporan-penjualan/{id}
     */
    public function update(Request $request, $id)
    {
        $detail = DetailLaporanPenjualan::find($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report detail not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jumlah_terjual' => 'integer|min:1',
            'harga_satuan' => 'numeric|min:0',
            'total_harga' => 'numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $detail->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sales report detail updated successfully',
            'data' => $detail->load(['laporan', 'produk'])
        ]);
    }

    /**
     * Delete sales report detail
     * DELETE /API/V1/detail-laporan-penjualan/{id}
     */
    public function destroy($id)
    {
        $detail = DetailLaporanPenjualan::find($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report detail not found'
            ], 404);
        }

        $detail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sales report detail deleted successfully'
        ]);
    }

    /**
     * Get details by report ID
     * GET /API/V1/detail-laporan-penjualan/laporan/{laporanId}
     */
    public function getByLaporan($laporanId)
    {
        $details = DetailLaporanPenjualan::with(['produk'])
                                         ->where('id_laporan', $laporanId)
                                         ->get();

        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }

    /**
     * Get details by product ID
     * GET /API/V1/detail-laporan-penjualan/produk/{produkId}
     */
    public function getByProduk($produkId)
    {
        $details = DetailLaporanPenjualan::with(['laporan'])
                                         ->where('id_produk', $produkId)
                                         ->get();

        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }
}

