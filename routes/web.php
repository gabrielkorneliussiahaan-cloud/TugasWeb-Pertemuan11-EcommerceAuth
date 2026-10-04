<?php

// ====================================================================
// routes/web.php
// REQUIREMENT 8: route protection berbasis role.
// - Siapa saja (termasuk guest) boleh lihat katalog produk.
// - Tambah/edit produk: hanya admin & editor (dicek lagi oleh Policy
//   di dalam controller, middleware di sini jadi lapis pertama).
// - Hapus produk: khusus admin (middleware + Policy, double-check).
// ====================================================================

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

// Katalog produk: publik, siapa saja boleh lihat.
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// REQUIREMENT 5 & 8: butuh login (Breeze) + role admin/editor.
Route::middleware(['auth', 'role:admin,editor'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
});

// REQUIREMENT 8: hapus produk cuma admin (lebih ketat dari edit).
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

/*
|--------------------------------------------------------------------------
| REQUIREMENT 5: route login/register/logout bawaan Breeze.
| Baris di bawah ini WAJIB ada setelah "php artisan breeze:install" --
| itu sebabnya di README, langkah install Breeze dilakukan SEBELUM
| menimpa routes/web.php dengan file ini (supaya routes/auth.php sudah
| ter-generate dan baris require di bawah tidak error "file not found").
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
