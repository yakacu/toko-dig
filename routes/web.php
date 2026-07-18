<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

// Halaman publik
Route::get('/', [ProdukController::class, 'index'])->name('home');
Route::get('/produk', [ProdukController::class, 'search'])->name('produk.search');
Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');

// Admin panel
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('kategori', KategoriController::class)->except(['index', 'show']);
        Route::resource('produk', AdminProdukController::class)->except(['index', 'show']);
        Route::resource('users', UserController::class)->except(['index', 'show']);
    });
});
