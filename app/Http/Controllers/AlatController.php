<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlatController extends Controller
{
    public function index()
    {
        try {
            $alat = Alat::with('kategori')->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get alat data',
                'data' => $alat->isEmpty() ? null : $alat,
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
            $alat = Alat::with('kategori')->find($id);
            if (! $alat) {
                return response()->json(['success' => false, 'message' => 'Alat tidak ditemukan!', 'data' => null], 404);
            }

            return response()->json(['success' => true, 'message' => 'Successfully get alat data', 'data' => $alat], 200);
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
                'alat_kategori_id' => 'required|integer|exists:kategori,kategori_id',
                'alat_nama' => 'required|string|max:150',
                'alat_deskripsi' => 'required|string',
                'alat_hargaperhari' => 'required|integer|min:0',
                'alat_stok' => 'required|integer|min:0',
            ], [
                'alat_kategori_id.required' => 'Kategori wajib dipilih',
                'alat_kategori_id.exists' => 'Kategori tidak ditemukan',
                'alat_nama.required' => 'Nama alat wajib diisi',
                'alat_nama.max' => 'Nama alat maksimal 150 karakter',
                'alat_deskripsi.required' => 'Deskripsi alat wajib diisi',
                'alat_hargaperhari.required' => 'Harga per hari wajib diisi',
                'alat_hargaperhari.integer' => 'Harga per hari harus berupa angka',
                'alat_hargaperhari.min' => 'Harga per hari tidak boleh negatif',
                'alat_stok.required' => 'Stok wajib diisi',
                'alat_stok.integer' => 'Stok harus berupa angka',
                'alat_stok.min' => 'Stok tidak boleh negatif',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan data alat!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $alat = Alat::create($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan data alat!', 'data' => $alat], 201);
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
            $alat = Alat::find($id);
            if (! $alat) {
                return response()->json(['success' => false, 'message' => 'Alat tidak ditemukan!', 'data' => null], 404);
            }

            $validator = Validator::make($request->all(), [
                'alat_kategori_id' => 'sometimes|required|integer|exists:kategori,kategori_id',
                'alat_nama' => 'sometimes|required|string|max:150',
                'alat_deskripsi' => 'sometimes|required|string',
                'alat_hargaperhari' => 'sometimes|required|integer|min:0',
                'alat_stok' => 'sometimes|required|integer|min:0',
            ], [
                'alat_kategori_id.exists' => 'Kategori tidak ditemukan',
                'alat_nama.max' => 'Nama alat maksimal 150 karakter',
                'alat_hargaperhari.integer' => 'Harga per hari harus berupa angka',
                'alat_hargaperhari.min' => 'Harga per hari tidak boleh negatif',
                'alat_stok.integer' => 'Stok harus berupa angka',
                'alat_stok.min' => 'Stok tidak boleh negatif',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah data alat!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $alat->update($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data alat!', 'data' => $alat], 200);
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
            $alat = Alat::find($id);
            if (! $alat) {
                return response()->json(['success' => false, 'message' => 'Alat tidak ditemukan!', 'data' => null], 404);
            }

            $alat->delete();

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus data alat!', 'data' => null], 200);
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
