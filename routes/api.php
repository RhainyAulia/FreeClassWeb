<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RuanganController;
use App\Http\Controllers\PeminjamanController;  

Route::get('/ruangan', [RuanganController::class, 'index']);
Route::get('/ruangan-terpakai', [RuanganController::class, 'ruanganTerpakai']); // mobile
Route::get('/ruangan-tersedia', [RuanganController::class, 'ruanganTersedia']); // admin
Route::post('/peminjaman', [PeminjamanController::class, 'store']);
Route::get('/peminjaman/{kode}', [PeminjamanController::class, 'showByKode']);
Route::put('/peminjaman/{kode}/batal', [PeminjamanController::class, 'batalkan']);
