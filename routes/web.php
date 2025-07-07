<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminPeminjamanController;

// =====================================================================
// REDIRECT ROOT KE LOGIN
// =====================================================================
Route::get('/', function () {
    return redirect('/admin/login');
});

// =====================================================================
// AUTH (Login untuk admin)
// =====================================================================
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login.submit');

// =====================================================================
// DASHBOARD ADMIN (Dikelola oleh AdminPeminjamanController)
// =====================================================================
Route::get('/admin/dashboard', [AdminPeminjamanController::class, 'dashboard'])->name('admin.dashboard');

// =====================================================================
// AKSI ADMIN - SETUJUI / TOLAK / FILTER
// =====================================================================
Route::post('/peminjaman/{id}/setujui', [AdminPeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
Route::post('/peminjaman/{id}/tolak', [AdminPeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
Route::get('/peminjaman/filter', [AdminPeminjamanController::class, 'filter']);


// =====================================================================
// OPSIONAL: Fitur Admin Tambahan (ruangan, jadwal, dll)
// =====================================================================
// Route::get('/admin/ruangan', [AdminController::class, 'ruangan']);
// Route::get('/admin/jadwal', [AdminController::class, 'jadwal']);
