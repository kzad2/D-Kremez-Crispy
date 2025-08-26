<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimoniController extends Controller
{
    /**
    * Get all testimonials with pagination
    * GET /API/V1/testimoni
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $testimoni = Testimoni::with(['pelanggan', 'moderator'])
                              ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $testimoni
        ]);
    }

    /**
    * Create new testimonial
    * POST /API/V1/testimoni
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pelanggan' => 'required|exists:pengguna,id',
            'konten' => 'required|string',
            'penilaian' => 'required|integer|min:1|max:5',
            'dimoderasi_oleh' => 'required|exists:pengguna,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['dimoderasi_pada'] = now();

        $testimoni = Testimoni::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial created successfully',
            'data' => $testimoni->load(['pelanggan', 'moderator'])
        ], 201);
    }

    /**
    * Get specific testimonial
    * GET /API/V1/testimoni/{id}
     */
    public function show($id)
    {
        $testimoni = Testimoni::with(['pelanggan', 'moderator'])->find($id);

        if (!$testimoni) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $testimoni
        ]);
    }

    /**
    * Update testimonial
    * PUT /API/V1/testimoni/{id}
     */
    public function update(Request $request, $id)
    {
        $testimoni = Testimoni::find($id);

        if (!$testimoni) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'konten' => 'string',
            'penilaian' => 'integer|min:1|max:5',
            'status' => 'in:menunggu,disetujui,ditolak'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimoni->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Testimonial updated successfully',
            'data' => $testimoni->load(['pelanggan', 'moderator'])
        ]);
    }

    /**
    * Delete testimonial
    * DELETE /API/V1/testimoni/{id}
     */
    public function destroy($id)
    {
        $testimoni = Testimoni::find($id);

        if (!$testimoni) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        $testimoni->delete();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully'
        ]);
    }

    /**
    * Moderate testimonial
    * PUT /API/V1/testimoni/{id}/moderate
     */
    public function moderate(Request $request, $id)
    {
        $testimoni = Testimoni::find($id);

        if (!$testimoni) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:disetujui,ditolak',
            'dimoderasi_oleh' => 'required|exists:pengguna,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimoni->update([
            'status' => $request->status,
            'dimoderasi_oleh' => $request->dimoderasi_oleh,
            'dimoderasi_pada' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial moderated successfully',
            'data' => $testimoni->load(['pelanggan', 'moderator'])
        ]);
    }

    /**
    * Get testimonials by status
    * GET /API/V1/testimoni/status/{status}
     */
    public function getByStatus($status)
    {
        $testimoni = Testimoni::with(['pelanggan', 'moderator'])
                              ->where('status', $status)
                              ->get();

        return response()->json([
            'success' => true,
            'data' => $testimoni
        ]);
    }

    /**
    * Get testimonials by user
    * GET /API/V1/testimoni/user/{userId}
     */
    public function getByUser($userId)
    {
        $testimoni = Testimoni::with(['moderator'])
                              ->where('id_pelanggan', $userId)
                              ->get();

        return response()->json([
            'success' => true,
            'data' => $testimoni
        ]);
    }
}
