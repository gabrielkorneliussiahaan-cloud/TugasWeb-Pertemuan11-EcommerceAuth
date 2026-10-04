<?php

// ====================================================================
// REQUIREMENT 1: tabel #6 — order_items, FK ke orders & products.
// Harga disimpan ulang di sini (price snapshot) supaya riwayat pesanan
// tidak berubah kalau harga produk di masa depan berubah.
// ====================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2); // snapshot harga saat pesan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
