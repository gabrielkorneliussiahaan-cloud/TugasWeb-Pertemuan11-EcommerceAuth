<?php

// ====================================================================
// app/Policies/ProductPolicy.php
// Dibuat dengan: php artisan make:policy ProductPolicy --model=Product
//
// CATATAN: rubrik di gambar menyebut "PostPolicy" (kemungkinan sisa
// template dari tugas blog sebelumnya / TR10 yang memang punya Model
// Post). Karena aplikasi TR11 ini e-commerce (tidak ada Model Post),
// policy yang dibuat untuk entitas yang relevan di sini: Product.
// Pola otorisasinya sama persis — tinggal pindah nama kalau ternyata
// dosen memang minta nama class "PostPolicy" literal.
//
// REQUIREMENT 7: otorisasi untuk update & delete.
// ====================================================================

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Semua role boleh melihat produk (index/show tidak dibatasi policy,
     * cukup lewat route publik).
     */
    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    /**
     * Hanya admin & editor yang boleh menambah produk baru.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'editor');
    }

    /**
     * REQUIREMENT 7: hanya admin & editor yang boleh mengedit produk.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('admin', 'editor');
    }

    /**
     * REQUIREMENT 7: hanya admin yang boleh menghapus produk
     * (lebih ketat dari update -> editor tidak boleh hapus).
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('admin');
    }
}
