<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RuanganController;


Route::get('/ruangan-terpakai', [RuanganController::class, 'ruanganTerpakai']); // mobile
Route::get('/ruangan-tersedia', [RuanganController::class, 'ruanganTersedia']); // admin
