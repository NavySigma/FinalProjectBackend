<?php

namespace App\Http\Controllers;

use App\Models\PenyewaanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyewaanDetailController extends Controller
{
    public function index()
    {
        try {
            $detail = PenyewaanDetail::with(['penyewaan', 'alat'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get penyewaan detail data',
                'data' => $detail->isEmpty() ? null : $detail,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There error in Internal Server',
                'data' => null,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $detail = PenyewaanDetail::with(['penyewaan', 'alat'])->find($id);
            if (! $detail) {
                return response()->json(['success' => false, 'message' => 'Detail penyewaan tidak ditemukan!', 'data' => null], 404);
            }

            return response()->json(['success' => true, 'message' => 'Successfully get penyewaan detail data', 'data' => $detail], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There error in Internal Server',
                'data' => null,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'penyewaan_detail_penyewaan_id' => 'required|integer|exists:penyewaan,penyewaan_id',
                'penyewaan_detail_alat_id' => 'required|integer|exists:alat,alat_id',
                'penyewaan_detail_jumlah' => 'required|integer|min:1',
                'penyewaan_detail_subharga' => 'required|integer|min:0',
            ], [
                'penyewaan_detail_penyewaan_id.required' => 'Penyewaan wajib dipilih',
                'penyewaan_detail_penyewaan_id.exists' => 'Penyewaan tidak ditemukan',
                'penyewaan_detail_alat_id.required' => 'Alat wajib dipilih',
                'penyewaan_detail_alat_id.exists' => 'Alat tidak ditemukan',
                'penyewaan_detail_jumlah.required' => 'Jumlah wajib diisi',
                'penyewaan_detail_jumlah.integer' => 'Jumlah harus berupa angka',
                'penyewaan_detail_jumlah.min' => 'Jumlah minimal 1',
                'penyewaan_detail_subharga.required' => 'Sub harga wajib diisi',
                'penyewaan_detail_subharga.integer' => 'Sub harga harus berupa angka',
                'penyewaan_detail_subharga.min' => 'Sub harga tidak boleh negatif',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan data detail penyewaan!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $detail = PenyewaanDetail::create($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan data detail penyewaan!', 'data' => $detail], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There error in Internal Server',
                'data' => null,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $detail = PenyewaanDetail::find($id);
            if (! $detail) {
                return response()->json(['success' => false, 'message' => 'Detail penyewaan tidak ditemukan!', 'data' => null], 404);
            }

            $validator = Validator::make($request->all(), [
                'penyewaan_detail_penyewaan_id' => 'sometimes|required|integer|exists:penyewaan,penyewaan_id',
                'penyewaan_detail_alat_id' => 'sometimes|required|integer|exists:alat,alat_id',
                'penyewaan_detail_jumlah' => 'sometimes|required|integer|min:1',
                'penyewaan_detail_subharga' => 'sometimes|required|integer|min:0',
            ], [
                'penyewaan_detail_penyewaan_id.exists' => 'Penyewaan tidak ditemukan',
                'penyewaan_detail_alat_id.exists' => 'Alat tidak ditemukan',
                'penyewaan_detail_jumlah.integer' => 'Jumlah harus berupa angka',
                'penyewaan_detail_jumlah.min' => 'Jumlah minimal 1',
                'penyewaan_detail_subharga.integer' => 'Sub harga harus berupa angka',
                'penyewaan_detail_subharga.min' => 'Sub harga tidak boleh negatif',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah data detail penyewaan!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $detail->update($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data detail penyewaan!', 'data' => $detail], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There error in Internal Server',
                'data' => null,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $detail = PenyewaanDetail::find($id);
            if (! $detail) {
                return response()->json(['success' => false, 'message' => 'Detail penyewaan tidak ditemukan!', 'data' => null], 404);
            }

            $detail->delete();

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus data detail penyewaan!', 'data' => null], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There error in Internal Server',
                'data' => null,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
