@extends('layouts.app')

@section('title', 'Semua Produk - Toko Online')

@section('content')
  <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <h1 class="text-2xl font-extrabold">Semua Produk</h1>
    <form method="GET" action="{{ route('products.index') }}" class="flex gap-2">
      <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari produk..."
        class="rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-sm w-48 focus:outline-none focus:border-pink-500" />
      <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-700 px-4 py-2 text-sm font-medium">Cari</button>
    </form>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
    @forelse ($products as $product)
      <a href="{{ route('products.show', $product) }}" class="rounded-xl border border-slate-800 bg-slate-900/60 overflow-hidden hover:border-pink-500/50 transition">
        {{-- Foto produk: konvensi nama file "{id}.webp" di public/images/products/,
             tidak lewat kolom database / upload form. Kalau filenya belum ada,
             otomatis tampil kotak abu-abu placeholder (lihat onerror di bawah). --}}
        <div class="aspect-square bg-slate-800">
          <img
            src="{{ asset('images/products/' . $product->id . '.webp') }}"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover"
            onerror="this.closest('.aspect-square').classList.add('flex','items-center','justify-center'); this.replaceWith(Object.assign(document.createElement('span'), {className:'text-slate-600 text-xs', textContent:'Tidak ada foto'}));"
          />
        </div>
        <div class="p-5">
          <p class="text-xs text-pink-400 uppercase tracking-wide mb-1">{{ $product->category->name }}</p>
          <p class="font-bold mb-1">{{ $product->name }}</p>
          <p class="text-lime-400 text-sm font-semibold">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
          <p class="text-xs text-slate-500 mt-1">Stok: {{ $product->stock }}</p>
        </div>
      </a>
    @empty
      <p class="text-slate-500">Belum ada produk yang cocok.</p>
    @endforelse
  </div>

  <div class="mt-8">{{ $products->links() }}</div>
@endsection
