{{-- Form field yang dipakai bersama create.blade.php & edit.blade.php. --}}
<div>
  <label class="block text-sm font-semibold mb-1" for="category_id">Kategori</label>
  <select id="category_id" name="category_id"
    class="w-full rounded-lg bg-slate-950 border {{ $errors->has('category_id') ? 'border-red-500' : 'border-slate-700' }} px-3 py-2 text-sm">
    <option value="">-- Pilih Kategori --</option>
    @foreach ($categories as $category)
      <option value="{{ $category->id }}" {{ (string) old('category_id', $product->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
        {{ $category->name }}
      </option>
    @endforeach
  </select>
  @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
  <label class="block text-sm font-semibold mb-1" for="name">Nama Produk</label>
  <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}"
    class="w-full rounded-lg bg-slate-950 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-700' }} px-3 py-2 text-sm" />
  @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div>
  <label class="block text-sm font-semibold mb-1" for="description">Deskripsi</label>
  <textarea id="description" name="description" rows="4"
    class="w-full rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-semibold mb-1" for="price">Harga (Rp)</label>
    <input type="number" id="price" name="price" min="0" step="100" value="{{ old('price', $product->price ?? '') }}"
      class="w-full rounded-lg bg-slate-950 border {{ $errors->has('price') ? 'border-red-500' : 'border-slate-700' }} px-3 py-2 text-sm" />
    @error('price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
  </div>
  <div>
    <label class="block text-sm font-semibold mb-1" for="stock">Stok</label>
    <input type="number" id="stock" name="stock" min="0" step="1" value="{{ old('stock', $product->stock ?? '') }}"
      class="w-full rounded-lg bg-slate-950 border {{ $errors->has('stock') ? 'border-red-500' : 'border-slate-700' }} px-3 py-2 text-sm" />
    @error('stock') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
  </div>
</div>
