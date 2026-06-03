<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyewaanController extends Controller
{
    public function index()
    {
        try {
            $penyewaan = Penyewaan::with(['pelanggan', 'penyewaanDetail.alat'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get penyewaan data',
                'data' => $penyewaan->isEmpty() ? null : $penyewaan,
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
            $penyewaan = Penyewaan::with(['pelanggan', 'penyewaanDetail.alat'])->find($id);
            if (! $penyewaan) {
                return response()->json(['success' => false, 'message' => 'Penyewaan tidak ditemukan!', 'data' => null], 404);
            }

            return response()->json(['success' => true, 'message' => 'Successfully get penyewaan data', 'data' => $penyewaan], 200);
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
                'penyewaan_pelanggan_id' => 'required|integer|exists:pelanggan,pelanggan_id',
                'penyewaan_tglsewa' => 'required|date',
                'penyewaan_tglkembali' => 'required|date|after_or_equal:penyewaan_tglsewa',
                'penyewaan_sttspembayaran' => 'sometimes|in:Lunas,Belum Dibayar,DP',
                'penyewaan_sttskembali' => 'sometimes|in:Sudah Kembali,Belum Kembali',
                'penyewaan_totalharga' => 'required|integer|min:0',
            ], [
                'penyewaan_pelanggan_id.required' => 'Pelanggan wajib dipilih',
                'penyewaan_pelanggan_id.exists' => 'Pelanggan tidak ditemukan',
                'penyewaan_tglsewa.required' => 'Tanggal sewa wajib diisi',
                'penyewaan_tglsewa.date' => 'Format tanggal sewa tidak valid',
                'penyewaan_tglkembali.required' => 'Tanggal kembali wajib diisi',
                'penyewaan_tglkembali.date' => 'Format tanggal kembali tidak valid',
                'penyewaan_tglkembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal sewa',
                'penyewaan_sttspembayaran.in' => 'Status pembayaran harus Lunas, Belum Dibayar, atau DP',
                'penyewaan_sttskembali.in' => 'Status kembali harus Sudah Kembali atau Belum Kembali',
                'penyewaan_totalharga.required' => 'Total harga wajib diisi',
                'penyewaan_totalharga.integer' => 'Total harga harus berupa angka',
                'penyewaan_totalharga.min' => 'Total harga tidak boleh negatif',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan data penyewaan!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $penyewaan = Penyewaan::create($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan data penyewaan!', 'data' => $penyewaan], 201);
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
            $penyewaan = Penyewaan::find($id);
            if (! $penyewaan) {
                return response()->json(['success' => false, 'message' => 'Penyewaan tidak ditemukan!', 'data' => null], 404);
            }

            $validator = Validator::make($request->all(), [
                'penyewaan_pelanggan_id' => 'sometimes|required|integer|exists:pelanggan,pelanggan_id',
                'penyewaan_tglsewa' => 'sometimes|required|date',
                'penyewaan_tglkembali' => 'sometimes|required|date|after_or_equal:penyewaan_tglsewa',
                'penyewaan_sttspembayaran' => 'sometimes|in:Lunas,Belum Dibayar,DP',
                'penyewaan_sttskembali' => 'sometimes|in:Sudah Kembali,Belum Kembali',
                'penyewaan_totalharga' => 'sometimes|required|integer|min:0',
            ], [
                'penyewaan_pelanggan_id.exists' => 'Pelanggan tidak ditemukan',
                'penyewaan_tglsewa.date' => 'Format tanggal sewa tidak valid',
                'penyewaan_tglkembali.date' => 'Format tanggal kembali tidak valid',
                'penyewaan_tglkembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal sewa',
                'penyewaan_sttspembayaran.in' => 'Status pembayaran harus Lunas, Belum Dibayar, atau DP',
                'penyewaan_sttskembali.in' => 'Status kembali harus Sudah Kembali atau Belum Kembali',
                'penyewaan_totalharga.integer' => 'Total harga harus berupa angka',
                'penyewaan_totalharga.min' => 'Total harga tidak boleh negatif',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah data penyewaan!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $penyewaan->update($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data penyewaan!', 'data' => $penyewaan], 200);
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
            $penyewaan = Penyewaan::find($id);
            if (! $penyewaan) {
                return response()->json(['success' => false, 'message' => 'Penyewaan tidak ditemukan!', 'data' => null], 404);
            }

            $penyewaan->delete();

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus data penyewaan!', 'data' => null], 200);
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
