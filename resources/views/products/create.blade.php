@extends('layouts.app')

@section('title', 'Tambah Produk - Toko Online')

@section('content')
  <h1 class="text-2xl font-extrabold mb-6">Tambah Produk</h1>

  <div class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
    <form method="POST" action="{{ route('products.store') }}" class="space-y-5">
      @csrf
      @include('products._form', ['product' => null])
      <div class="flex gap-3">
        <a href="{{ route('products.index') }}" class="rounded-lg border border-slate-700 px-4 py-2 text-sm">Batal</a>
        <button type="submit" class="rounded-lg bg-pink-600 hover:bg-pink-500 px-5 py-2 text-sm font-semibold">Simpan</button>
      </div>
    </form>
  </div>
@endsection
