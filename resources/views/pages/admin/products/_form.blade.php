@php
    $inputClass = 'mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500';
@endphp

<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="name" class="text-sm font-bold text-gray-700">Nama Produk</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" required placeholder="Contoh: Smartphone Nova X12 256GB" class="{{ $inputClass }}">
        @error('name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="category_id" class="text-sm font-bold text-gray-700">Kategori</label>
        <select id="category_id" name="category_id" required class="{{ $inputClass }}">
            <option value="" disabled @selected(! old('category_id', $product?->category_id))>Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="price" class="text-sm font-bold text-gray-700">Harga (Rp)</label>
        <input id="price" name="price" type="number" min="0" value="{{ old('price', $product?->price) }}" required placeholder="0" class="{{ $inputClass }}">
        @error('price') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="stock" class="text-sm font-bold text-gray-700">Stok</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product?->stock ?? 0) }}" required class="{{ $inputClass }}">
        @error('stock') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div x-data="{ preview: @js($product?->image ? $product->image_url : null) }">
        <label for="image" class="text-sm font-bold text-gray-700">Foto Produk @if ($product) <span class="font-medium text-gray-400">(kosongkan jika tidak diganti)</span> @endif</label>
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" @required(! $product)
            @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
            class="{{ $inputClass }} file:mr-3 file:rounded-l-xl file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-red-600">
        @error('image') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
        <img x-show="preview" x-cloak :src="preview" alt="Pratinjau foto produk" class="mt-3 h-24 w-24 rounded-xl bg-gray-100 object-cover">
    </div>
    <div class="sm:col-span-2">
        <label for="description" class="text-sm font-bold text-gray-700">Deskripsi <span class="font-medium text-gray-400">(opsional)</span></label>
        <textarea id="description" name="description" rows="4" class="{{ $inputClass }}">{{ old('description', $product?->description) }}</textarea>
        @error('description') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.products.index') }}" class="rounded-full border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
    <button type="submit" class="rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Simpan</button>
</div>
