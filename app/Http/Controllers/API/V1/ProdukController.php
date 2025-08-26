<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Get all products with pagination
     * GET /API/V1/produk
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $produk = Produk::paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $produk
        ]);
    }

    /**
     * Create new product
     * POST /API/V1/produk
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:150',
            'kode_produk' => 'required|string|max:100|unique:produk',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $produk = Produk::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $produk
        ], 201);
    }

    /**
     * Get specific product
     * GET /API/V1/produk/{id}
     */
    public function show($id)
    {
        $produk = Produk::with(['detailLaporanPenjualan', 'pergerakanStok'])->find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $produk
        ]);
    }

    /**
     * Update product
     * PUT /API/V1/produk/{id}
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'string|max:150',
            'kode_produk' => 'string|max:100|unique:produk,kode_produk,' . $id,
            'harga' => 'numeric|min:0',
            'satuan' => 'string|max:50',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $produk->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $produk
        ]);
    }

    /**
     * Delete product
     * DELETE /API/V1/produk/{id}
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    /**
     * Toggle product status
     * PUT /API/V1/produk/{id}/toggle-status
     */
    public function toggleStatus($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $produk->update(['aktif' => !$produk->aktif]);

        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully',
            'data' => $produk
        ]);
    }

    /**
     * Search products by name or code
     * GET /API/V1/produk/search/{keyword}
     */
    public function search($keyword)
    {
        $produk = Produk::where('nama', 'LIKE', "%{$keyword}%")
                       ->orWhere('kode_produk', 'LIKE', "%{$keyword}%")
                       ->get();

        return response()->json([
            'success' => true,
            'data' => $produk
        ]);
    }

    /**
     * Get active products only
     * GET /API/V1/produk/active
     */
    public function getActive()
    {
        $produk = Produk::where('aktif', true)->get();

        return response()->json([
            'success' => true,
            'data' => $produk
        ]);
    }
}
