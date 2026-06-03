<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $kategori = Kategori::all();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get kategori data',
                'data' => $kategori->isEmpty() ? null : $kategori,
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
            $kategori = Kategori::find($id);
            if (! $kategori) {
                return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan!', 'data' => null], 404);
            }

            return response()->json(['success' => true, 'message' => 'Successfully get kategori data', 'data' => $kategori], 200);
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
                'kategori_nama' => 'required|string|max:100',
            ], [
                'kategori_nama.required' => 'Nama kategori wajib diisi',
                'kategori_nama.string' => 'Nama kategori harus berupa teks',
                'kategori_nama.max' => 'Nama kategori maksimal 100 karakter',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan data kategori!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $kategori = Kategori::create($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan data kategori!', 'data' => $kategori], 201);
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
            $kategori = Kategori::find($id);
            if (! $kategori) {
                return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan!', 'data' => null], 404);
            }

            $validator = Validator::make($request->all(), [
                'kategori_nama' => 'sometimes|required|string|max:100',
            ], [
                'kategori_nama.required' => 'Nama kategori wajib diisi',
                'kategori_nama.string' => 'Nama kategori harus berupa teks',
                'kategori_nama.max' => 'Nama kategori maksimal 100 karakter',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah data kategori!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $kategori->update($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data kategori!', 'data' => $kategori], 200);
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
            $kategori = Kategori::find($id);
            if (! $kategori) {
                return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan!', 'data' => null], 404);
            }

            $kategori->delete();

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus data kategori!', 'data' => null], 200);
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
