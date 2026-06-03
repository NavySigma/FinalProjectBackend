<?php

namespace App\Http\Controllers;

use App\Models\PelangganData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PelangganDataController extends Controller
{
    public function index()
    {
        try {
            $data = PelangganData::with('pelanggan')->get();

            return response()->json([
                'success' => true,
                'message' => 'Successfully get pelanggan data',
                'data' => $data->isEmpty() ? null : $data,
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
            $data = PelangganData::with('pelanggan')->find($id);
            if (! $data) {
                return response()->json(['success' => false, 'message' => 'Data pelanggan tidak ditemukan!', 'data' => null], 404);
            }

            return response()->json(['success' => true, 'message' => 'Successfully get pelanggan data', 'data' => $data], 200);
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
                'pelanggan_data_pelanggan_id' => 'required|integer|exists:pelanggan,pelanggan_id|unique:pelanggan_data,pelanggan_data_pelanggan_id',
                'pelanggan_data_jenis' => 'required|in:KTP,SIM',
                'pelanggan_data_file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ], [
                'pelanggan_data_pelanggan_id.required' => 'Pelanggan wajib dipilih',
                'pelanggan_data_pelanggan_id.exists' => 'Pelanggan tidak ditemukan',
                'pelanggan_data_pelanggan_id.unique' => 'Pelanggan sudah memiliki data identitas',
                'pelanggan_data_jenis.required' => 'Jenis identitas wajib dipilih',
                'pelanggan_data_jenis.in' => 'Jenis identitas harus KTP atau SIM',
                'pelanggan_data_file.required' => 'File identitas wajib diunggah',
                'pelanggan_data_file.file' => 'Upload harus berupa file',
                'pelanggan_data_file.mimes' => 'File harus memiliki format .jpg, .png, atau .jpeg',
                'pelanggan_data_file.max' => 'Ukuran file maksimal 2MB',
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

            $filePath = $request->file('pelanggan_data_file')->store('pelanggan_data', 'public');

            $data = PelangganData::create([
                'pelanggan_data_pelanggan_id' => $request->pelanggan_data_pelanggan_id,
                'pelanggan_data_jenis' => $request->pelanggan_data_jenis,
                'pelanggan_data_file' => $filePath,
            ]);

            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan data pelanggan!', 'data' => $data], 201);
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
            $data = PelangganData::find($id);
            if (! $data) {
                return response()->json(['success' => false, 'message' => 'Data pelanggan tidak ditemukan!', 'data' => null], 404);
            }

            $validator = Validator::make($request->all(), [
                'pelanggan_data_jenis' => 'sometimes|required|in:KTP,SIM',
                'pelanggan_data_file' => 'sometimes|required|file|mimes:jpg,jpeg,png|max:2048',
            ], [
                'pelanggan_data_jenis.in' => 'Jenis identitas harus KTP atau SIM',
                'pelanggan_data_file.file' => 'Upload harus berupa file',
                'pelanggan_data_file.mimes' => 'File harus memiliki format .jpg, .png, atau .jpeg',
                'pelanggan_data_file.max' => 'Ukuran file maksimal 2MB',
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

            $updateData = [];

            if ($request->has('pelanggan_data_jenis')) {
                $updateData['pelanggan_data_jenis'] = $request->pelanggan_data_jenis;
            }

            if ($request->hasFile('pelanggan_data_file')) {
                Storage::disk('public')->delete($data->pelanggan_data_file);
                $updateData['pelanggan_data_file'] = $request->file('pelanggan_data_file')->store('pelanggan_data', 'public');
            }

            $data->update($updateData);

            return response()->json(['success' => true, 'message' => 'Berhasil mengubah data pelanggan!', 'data' => $data], 200);
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
            $data = PelangganData::find($id);
            if (! $data) {
                return response()->json(['success' => false, 'message' => 'Data pelanggan tidak ditemukan!', 'data' => null], 404);
            }

            Storage::disk('public')->delete($data->pelanggan_data_file);
            $data->delete();

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
