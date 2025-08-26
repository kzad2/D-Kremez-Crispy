<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\PengaturanTampilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengaturanTampilanController extends Controller
{
    /**
     * Get all display settings with pagination
     * GET /API/V1/pengaturan-tampilan
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $pengaturan = PengaturanTampilan::paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pengaturan
        ]);
    }

    /**
     * Create new display setting
     * POST /API/V1/pengaturan-tampilan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tema' => 'nullable|string|max:100',
            'path_logo' => 'nullable|string|max:255',
            'warna_utama' => 'nullable|string|max:50',
            'latar' => 'nullable|string',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengaturan = PengaturanTampilan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Display setting created successfully',
            'data' => $pengaturan
        ], 201);
    }

    /**
     * Get specific display setting
     * GET /API/V1/pengaturan-tampilan/{id}
     */
    public function show($id)
    {
        $pengaturan = PengaturanTampilan::find($id);

        if (!$pengaturan) {
            return response()->json([
                'success' => false,
                'message' => 'Display setting not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pengaturan
        ]);
    }

    /**
     * Update display setting
     * PUT /API/V1/pengaturan-tampilan/{id}
     */
    public function update(Request $request, $id)
    {
        $pengaturan = PengaturanTampilan::find($id);

        if (!$pengaturan) {
            return response()->json([
                'success' => false,
                'message' => 'Display setting not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_tema' => 'nullable|string|max:100',
            'path_logo' => 'nullable|string|max:255',
            'warna_utama' => 'nullable|string|max:50',
            'latar' => 'nullable|string',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengaturan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Display setting updated successfully',
            'data' => $pengaturan
        ]);
    }

    /**
     * Delete display setting
     * DELETE /API/V1/pengaturan-tampilan/{id}
     */
    public function destroy($id)
    {
        $pengaturan = PengaturanTampilan::find($id);

        if (!$pengaturan) {
            return response()->json([
                'success' => false,
                'message' => 'Display setting not found'
            ], 404);
        }

        $pengaturan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Display setting deleted successfully'
        ]);
    }

    /**
     * Activate specific theme (deactivate others)
     * PUT /API/V1/pengaturan-tampilan/{id}/activate
     */
    public function activate($id)
    {
        $pengaturan = PengaturanTampilan::find($id);

        if (!$pengaturan) {
            return response()->json([
                'success' => false,
                'message' => 'Display setting not found'
            ], 404);
        }

        // Deactivate all themes first
        PengaturanTampilan::query()->update(['aktif' => false]);

        // Activate the selected theme
        $pengaturan->update(['aktif' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Theme activated successfully',
            'data' => $pengaturan
        ]);
    }

    /**
     * Get active display setting
     * GET /API/V1/pengaturan-tampilan/active
     */
    public function getActive()
    {
        $pengaturan = PengaturanTampilan::where('aktif', true)->first();

        if (!$pengaturan) {
            return response()->json([
                'success' => false,
                'message' => 'No active theme found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pengaturan
        ]);
    }
}
