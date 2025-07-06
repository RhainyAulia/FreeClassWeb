<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;

// Halaman utama (redirect ke login)
Route::get('/', function () {
    return redirect('/profile/login');
});

// Halaman login
Route::get('/profile/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/profile/login', [LoginController::class, 'login'])->name('login.submit');

// Halaman dashboard
Route::get('/profile/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

// Halaman data ruangan & jadwal (opsional)
// Route::get('/admin/ruangan', [AdminController::class, 'ruangan']);
// Route::get('/admin/jadwal', [AdminController::class, 'jadwal']);
