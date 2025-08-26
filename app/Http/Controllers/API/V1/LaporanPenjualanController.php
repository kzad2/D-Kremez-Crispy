<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    /**
     * Get all sales reports with pagination
     * GET /API/V1/laporan-penjualan
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $laporan = LaporanPenjualan::with(['pengguna', 'detailLaporan'])
                                   ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Create new sales report
     * POST /API/V1/laporan-penjualan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pengguna' => 'required|exists:pengguna,id',
            'tanggal_laporan' => 'required|date',
            'total_omzet' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'status' => 'in:draf,dikirim,ditandai,disetujui,ditolak'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['dikirim_pada'] = now();
        $data['disetujui_pada'] = now();

        $laporan = LaporanPenjualan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Sales report created successfully',
            'data' => $laporan->load(['pengguna'])
        ], 201);
    }

    /**
     * Get specific sales report
     * GET /API/V1/laporan-penjualan/{id}
     */
    public function show($id)
    {
        $laporan = LaporanPenjualan::with(['pengguna', 'detailLaporan.produk', 'verifikasi'])
                                   ->find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Update sales report
     * PUT /API/V1/laporan-penjualan/{id}
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanPenjualan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tanggal_laporan' => 'date',
            'total_omzet' => 'numeric|min:0',
            'catatan' => 'nullable|string',
            'status' => 'in:draf,dikirim,ditandai,disetujui,ditolak'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $laporan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Sales report updated successfully',
            'data' => $laporan->load(['pengguna'])
        ]);
    }

    /**
     * Delete sales report
     * DELETE /API/V1/laporan-penjualan/{id}
     */
    public function destroy($id)
    {
        $laporan = LaporanPenjualan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report not found'
            ], 404);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sales report deleted successfully'
        ]);
    }

    /**
     * Update report status
     * PUT /API/V1/laporan-penjualan/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $laporan = LaporanPenjualan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draf,dikirim,ditandai,disetujui,ditolak'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = ['status' => $request->status];

        if ($request->status === 'dikirim') {
            $updateData['dikirim_pada'] = now();
        } elseif ($request->status === 'disetujui') {
            $updateData['disetujui_pada'] = now();
        }

        $laporan->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Report status updated successfully',
            'data' => $laporan
        ]);
    }

    /**
     * Get reports by user
     * GET /API/V1/laporan-penjualan/user/{userId}
     */
    public function getByUser($userId)
    {
        $laporan = LaporanPenjualan::with(['detailLaporan'])
                                   ->where('id_pengguna', $userId)
                                   ->get();

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Get reports by date range
     * GET /API/V1/laporan-penjualan/date-range/{startDate}/{endDate}
     */
    public function getByDateRange($startDate, $endDate)
    {
        $laporan = LaporanPenjualan::with(['pengguna', 'detailLaporan'])
                                   ->whereBetween('tanggal_laporan', [$startDate, $endDate])
                                   ->get();

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Get report summary
     * GET /API/V1/laporan-penjualan/{id}/summary
     */
    public function getSummary($id)
    {
        $laporan = LaporanPenjualan::with(['detailLaporan.produk'])->find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Sales report not found'
            ], 404);
        }

        $summary = [
            'total_items' => $laporan->detailLaporan->count(),
            'total_quantity' => $laporan->detailLaporan->sum('jumlah_terjual'),
            'total_omzet' => $laporan->total_omzet,
            'products' => $laporan->detailLaporan->map(function($detail) {
                return [
                    'produk' => $detail->produk->nama,
                    'jumlah' => $detail->jumlah_terjual,
                    'harga_satuan' => $detail->harga_satuan,
                    'total' => $detail->total_harga
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}

