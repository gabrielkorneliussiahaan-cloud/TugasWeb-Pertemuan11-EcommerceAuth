<!doctype html>
{{-- Layout master sederhana untuk halaman produk (di luar area Breeze). --}}
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Toko Online - Tugas Rutin 11')</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">
  <nav class="border-b border-slate-800 bg-slate-900/60 backdrop-blur">
    <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
      <a href="{{ route('products.index') }}" class="font-bold text-lg tracking-tight">
        <span class="text-pink-500">Toko</span>Online<span class="text-lime-400">.</span>
      </a>
      <div class="flex items-center gap-4 text-sm">
        @auth
          <span class="text-slate-400">{{ auth()->user()->name }} <span class="text-xs text-lime-400">({{ auth()->user()->role }})</span></span>
          @can('create', \App\Models\Product::class)
            <a href="{{ route('products.create') }}" class="rounded-lg bg-pink-600 hover:bg-pink-500 px-4 py-2 font-semibold transition">+ Produk</a>
          @endcan
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-slate-400 hover:text-pink-400">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="text-slate-300 hover:text-pink-400">Login</a>
          <a href="{{ route('register') }}" class="text-slate-300 hover:text-pink-400">Daftar</a>
        @endauth
      </div>
    </div>
  </nav>

  <main class="max-w-5xl mx-auto px-6 py-10">
    @if (session('flash_success'))
      <div class="rounded-lg border border-lime-500/40 bg-lime-500/10 text-lime-300 px-4 py-3 mb-6 text-sm">
        {{ session('flash_success') }}
      </div>
    @endif

    @yield('content')
  </main>
</body>
</html>
