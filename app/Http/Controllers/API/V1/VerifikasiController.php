<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifikasiController extends Controller
{
    /**
    * Get all verifications with pagination
    * GET /API/V1/verifikasi
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $verifikasi = Verifikasi::with(['laporan', 'admin'])
                                ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $verifikasi
        ]);
    }

    /**
    * Create new verification
    * POST /API/V1/verifikasi
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_laporan' => 'required|exists:laporan_penjualan,id',
            'id_admin' => 'required|exists:pengguna,id',
            'status' => 'required|in:disetujui,ditolak',
            'alasan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $verifikasi = Verifikasi::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Verification created successfully',
            'data' => $verifikasi->load(['laporan', 'admin'])
        ], 201);
    }

    /**
    * Get specific verification
    * GET /API/V1/verifikasi/{id}
     */
    public function show($id)
    {
        $verifikasi = Verifikasi::with(['laporan', 'admin'])->find($id);

        if (!$verifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Verification not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $verifikasi
        ]);
    }

    /**
    * Update verification
    * PUT /API/V1/verifikasi/{id}
     */
    public function update(Request $request, $id)
    {
        $verifikasi = Verifikasi::find($id);

        if (!$verifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Verification not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'in:disetujui,ditolak',
            'alasan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $verifikasi->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Verification updated successfully',
            'data' => $verifikasi->load(['laporan', 'admin'])
        ]);
    }

    /**
    * Delete verification
    * DELETE /API/V1/verifikasi/{id}
     */
    public function destroy($id)
    {
        $verifikasi = Verifikasi::find($id);

        if (!$verifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Verification not found'
            ], 404);
        }

        $verifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Verification deleted successfully'
        ]);
    }

    /**
    * Get verifications by report ID
    * GET /API/V1/verifikasi/laporan/{laporanId}
     */
    public function getByLaporan($laporanId)
    {
        $verifikasi = Verifikasi::with(['admin'])
                                ->where('id_laporan', $laporanId)
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $verifikasi
        ]);
    }

    /**
    * Get verifications by admin ID
    * GET /API/V1/verifikasi/admin/{adminId}
     */
    public function getByAdmin($adminId)
    {
        $verifikasi = Verifikasi::with(['laporan'])
                                ->where('id_admin', $adminId)
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $verifikasi
        ]);
    }
}
