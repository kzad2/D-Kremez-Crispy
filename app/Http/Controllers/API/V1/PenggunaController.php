<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    /**
     * Get all users with pagination
     * GET /API/V1/pengguna
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $pengguna = Pengguna::paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pengguna
        ]);
    }

    /**
     * Create new user
     * POST /API/V1/pengguna
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:pengguna',
            'kata_sandi' => 'required|string|min:6',
            'peran' => 'required|in:pelanggan,karyawan,admin,pemilik',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'peran' => $request->peran,
            'aktif' => $request->get('aktif', true)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $pengguna
        ], 201);
    }

    /**
     * Get specific user
     * GET /API/V1/pengguna/{id}
     */
    public function show($id)
    {
        $pengguna = Pengguna::with(['laporanPenjualan', 'testimoni'])->find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pengguna
        ]);
    }

    /**
     * Update user
     * PUT /API/V1/pengguna/{id}
     */
    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'string|max:100',
            'email' => 'string|email|max:150|unique:pengguna,email,' . $id,
            'kata_sandi' => 'string|min:6',
            'peran' => 'in:pelanggan,karyawan,admin,pemilik',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        if (isset($data['kata_sandi'])) {
            $data['kata_sandi'] = Hash::make($data['kata_sandi']);
        }

        $pengguna->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $pengguna
        ]);
    }

    /**
     * Delete user
     * DELETE /API/V1/pengguna/{id}
     */
    public function destroy($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Toggle user status
     * PUT /API/V1/pengguna/{id}/toggle-status
     */
    public function toggleStatus($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $pengguna->update(['aktif' => !$pengguna->aktif]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully',
            'data' => $pengguna
        ]);
    }

    /**
     * Get users by role
     * GET /API/V1/pengguna/role/{role}
     */
    public function getByRole($role)
    {
        $pengguna = Pengguna::where('peran', $role)->get();

        return response()->json([
            'success' => true,
            'data' => $pengguna
        ]);
    }
}

