<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Kasir\CartController;

// 1. Homepage
Route::get('/', function () {
    return view('welcome');
});

// 2. Group Khusus ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Fitur Menu (Tambah, Update Stok, & Hapus)
    Route::post('/admin/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::patch('/admin/menu/{id}/update-stok', [MenuController::class, 'updateStok'])->name('admin.menu.updateStok');
    Route::delete('/admin/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy'); // BARU
});

// 3. Group Khusus KASIR
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/dashboard', [CartController::class, 'index'])->name('kasir.dashboard');

    // Fitur Keranjang
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('kasir.cart.add');
    Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('kasir.cart.decrease');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('kasir.cart.remove');
    Route::get('/cart/clear', [CartController::class, 'clear'])->name('kasir.cart.clear');

    // Fitur Transaksi
    Route::post('/transaction', [CartController::class, 'storeTransaction'])->name('kasir.transaction.store');
});

// 4. Route Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
