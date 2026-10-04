@extends('layouts.app')

@section('title', $product->name . ' - Toko Online')

@section('content')
  <a href="{{ route('products.index') }}" class="text-sm text-slate-400 hover:text-pink-400">&larr; Kembali</a>

  <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-6 mt-4">
    {{-- Foto produk: konvensi nama file "{id}.webp" di public/images/products/ --}}
    <div class="w-full max-w-sm aspect-square bg-slate-800 rounded-lg overflow-hidden mb-6">
      <img
        src="{{ asset('images/products/' . $product->id . '.webp') }}"
        alt="{{ $product->name }}"
        class="w-full h-full object-cover"
        onerror="this.closest('div').classList.add('flex','items-center','justify-center'); this.replaceWith(Object.assign(document.createElement('span'), {className:'text-slate-600 text-sm', textContent:'Tidak ada foto'}));"
      />
    </div>

    <p class="text-xs text-pink-400 uppercase tracking-wide mb-2">{{ $product->category->name }}</p>
    <h1 class="text-2xl font-extrabold mb-2">{{ $product->name }}</h1>
    <p class="text-lime-400 text-xl font-bold mb-4">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
    <p class="text-slate-300 mb-4">{{ $product->description }}</p>
    <p class="text-sm text-slate-500 mb-6">Stok: {{ $product->stock }} &middot; Rating rata-rata: {{ $product->averageRating() }} / 5 ({{ $product->reviews->count() }} ulasan)</p>

    @can('update', $product)
      <div class="flex gap-3 pt-4 border-t border-slate-800">
        <a href="{{ route('products.edit', $product) }}" class="rounded-lg border border-slate-700 px-4 py-2 text-sm">Edit</a>
        @can('delete', $product)
          <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-lg border border-red-500/50 text-red-400 px-4 py-2 text-sm">Hapus</button>
          </form>
        @endcan
      </div>
    @endcan
  </div>
@endsection
