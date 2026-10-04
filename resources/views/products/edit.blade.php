@extends('layouts.app')

@section('title', 'Edit Produk - Toko Online')

@section('content')
  <h1 class="text-2xl font-extrabold mb-6">Edit Produk</h1>

  <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
    <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-5">
      @csrf
      @method('PUT')
      @include('products._form', ['product' => $product])
      <div class="flex gap-3">
        <a href="{{ route('products.index') }}" class="rounded-lg border border-slate-700 px-4 py-2 text-sm">Batal</a>
        <button type="submit" class="rounded-lg bg-pink-600 hover:bg-pink-500 px-5 py-2 text-sm font-semibold">Simpan Perubahan</button>
      </div>
    </form>
  </div>
@endsection
