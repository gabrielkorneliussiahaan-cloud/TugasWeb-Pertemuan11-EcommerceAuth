<?php

// ====================================================================
// app/Http/Controllers/ProductController.php
// REQUIREMENT 7: otorisasi lewat ProductPolicy ($this->authorize()).
// BONUS: eager loading demo di index() -> with('category').
// ====================================================================

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * BONUS (eager loading demo): tanpa with('category'), setiap produk
     * di halaman ini akan memicu 1 query tambahan ke tabel categories
     * (classic N+1 problem). Dengan with('category'), cuma 2 query total
     * berapa pun jumlah produknya. Coba bandingkan lewat Tinker, lihat
     * README bagian "Eager Loading Demo".
     */
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));

        $products = Product::query()
            ->with('category') // eager load, hindari N+1
            ->when($keyword !== '', fn ($q) => $q->search($keyword))
            ->inStock()
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('products.index', ['products' => $products, 'keyword' => $keyword]);
    }

    public function create(): View
    {
        $this->authorize('create', Product::class);

        return view('products.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('flash_success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'reviews.user']); // eager load relasi untuk halaman detail

        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        return view('products.edit', [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('flash_success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('products.index')->with('flash_success', 'Produk berhasil dihapus.');
    }
}
