<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganDataController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\PenyewaanDetailController;
use Illuminate\Support\Facades\Route;

// Auth routes (public)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Alat
    Route::get('/alat', [AlatController::class, 'index']);
    Route::get('/alat/{id}', [AlatController::class, 'show']);
    Route::post('/alat', [AlatController::class, 'store']);
    Route::patch('/alat/{id}', [AlatController::class, 'update']);
    Route::delete('/alat/{id}', [AlatController::class, 'destroy']);

    // Kategori
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/kategori/{id}', [KategoriController::class, 'show']);
    Route::post('/kategori', [KategoriController::class, 'store']);
    Route::patch('/kategori/{id}', [KategoriController::class, 'update']);
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

    // Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index']);
    Route::get('/pelanggan/{id}', [PelangganController::class, 'show']);
    Route::post('/pelanggan', [PelangganController::class, 'store']);
    Route::patch('/pelanggan/{id}', [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

    // Pelanggan Data
    Route::get('/pelanggan-data', [PelangganDataController::class, 'index']);
    Route::get('/pelanggan-data/{id}', [PelangganDataController::class, 'show']);
    Route::post('/pelanggan-data', [PelangganDataController::class, 'store']);
    Route::patch('/pelanggan-data/{id}', [PelangganDataController::class, 'update']);
    Route::delete('/pelanggan-data/{id}', [PelangganDataController::class, 'destroy']);

    // Penyewaan
    Route::get('/penyewaan', [PenyewaanController::class, 'index']);
    Route::get('/penyewaan/{id}', [PenyewaanController::class, 'show']);
    Route::post('/penyewaan', [PenyewaanController::class, 'store']);
    Route::patch('/penyewaan/{id}', [PenyewaanController::class, 'update']);
    Route::delete('/penyewaan/{id}', [PenyewaanController::class, 'destroy']);

    // Penyewaan Detail
    Route::get('/penyewaan-detail', [PenyewaanDetailController::class, 'index']);
    Route::get('/penyewaan-detail/{id}', [PenyewaanDetailController::class, 'show']);
    Route::post('/penyewaan-detail', [PenyewaanDetailController::class, 'store']);
    Route::patch('/penyewaan-detail/{id}', [PenyewaanDetailController::class, 'update']);
    Route::delete('/penyewaan-detail/{id}', [PenyewaanDetailController::class, 'destroy']);
});
