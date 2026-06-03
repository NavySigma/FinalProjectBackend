<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index()
    {
        try {
            $pelanggan = Pelanggan::with(['pelangganData', 'penyewaan'])->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get pelanggan data',
                'data' => $pelanggan->isEmpty() ? null : $pelanggan,
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
            $pelanggan = Pelanggan::with(['pelangganData', 'penyewaan'])->find($id);
            if (! $pelanggan) {
                return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan!', 'data' => null], 404);
            }

            return response()->json(['success' => true, 'message' => 'Successfully get pelanggan data', 'data' => $pelanggan], 200);
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
                'pelanggan_nama' => 'required|string|max:150',
                'pelanggan_alamat' => 'required|string|max:200',
                'pelanggan_notelp' => 'required|string|max:13|regex:/^[0-9]+$/',
                'pelanggan_email' => 'required|email|max:100|unique:pelanggan,pelanggan_email',
            ], [
                'pelanggan_nama.required' => 'Nama pelanggan wajib diisi',
                'pelanggan_nama.max' => 'Nama pelanggan maksimal 150 karakter',
                'pelanggan_alamat.required' => 'Alamat pelanggan wajib diisi',
                'pelanggan_alamat.max' => 'Alamat pelanggan maksimal 200 karakter',
                'pelanggan_notelp.required' => 'Nomor telepon wajib diisi',
                'pelanggan_notelp.max' => 'Nomor telepon maksimal 13 karakter',
                'pelanggan_notelp.regex' => 'Nomor telepon hanya boleh berisi angka',
                'pelanggan_email.required' => 'Email pelanggan wajib diisi',
                'pelanggan_email.email' => 'Format email tidak valid',
                'pelanggan_email.unique' => 'Email sudah terdaftar',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan data pelanggan!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $pelanggan = Pelanggan::create($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan data pelanggan!', 'data' => $pelanggan], 201);
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
            $pelanggan = Pelanggan::find($id);
            if (! $pelanggan) {
                return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan!', 'data' => null], 404);
            }

            $validator = Validator::make($request->all(), [
                'pelanggan_nama' => 'sometimes|required|string|max:150',
                'pelanggan_alamat' => 'sometimes|required|string|max:200',
                'pelanggan_notelp' => 'sometimes|required|string|max:13|regex:/^[0-9]+$/',
                'pelanggan_email' => 'sometimes|required|email|max:100|unique:pelanggan,pelanggan_email,'.$id.',pelanggan_id',
            ], [
                'pelanggan_nama.max' => 'Nama pelanggan maksimal 150 karakter',
                'pelanggan_alamat.max' => 'Alamat pelanggan maksimal 200 karakter',
                'pelanggan_notelp.max' => 'Nomor telepon maksimal 13 karakter',
                'pelanggan_notelp.regex' => 'Nomor telepon hanya boleh berisi angka',
                'pelanggan_email.email' => 'Format email tidak valid',
                'pelanggan_email.unique' => 'Email sudah terdaftar',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah data pelanggan!',
                    'data' => null,
                    'errors' => collect($validator->errors()->messages())
                        ->map(fn ($messages) => $messages[0])
                        ->map(fn ($msg, $key) => [$key => $msg])
                        ->values()->toArray(),
                ], 422);
            }

            $pelanggan->update($validator->validated());

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data pelanggan!', 'data' => $pelanggan], 200);
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
            $pelanggan = Pelanggan::find($id);
            if (! $pelanggan) {
                return response()->json(['success' => false, 'message' => 'Pelanggan tidak ditemukan!', 'data' => null], 404);
            }

            $pelanggan->delete();

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus data pelanggan!', 'data' => null], 200);
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
