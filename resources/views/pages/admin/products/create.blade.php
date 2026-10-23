<x-admin-layout>
    <x-slot name="title">Tambah Produk</x-slot>

    @php
        $inputClass = 'mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500';
    @endphp

    <div class="mb-8">
        <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">&larr; Kembali ke produk</a>
        <h2 class="mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Tambah Produk</h2>
        <p class="mt-1 text-sm text-gray-500">Isi data produk baru di bawah ini.</p>
    </div>

    <form x-data="{ saved: false }" @submit.prevent="saved = true" class="max-w-3xl rounded-2xl bg-white p-6 shadow-xl shadow-gray-900/5 sm:p-8">
        <div x-show="saved" x-cloak x-transition class="mb-6 rounded-xl bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
            Tampilan saja: data belum disimpan karena database produk belum tersedia.
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="text-sm font-bold text-gray-700">Nama Produk</label>
                <input id="name" name="name" type="text" required placeholder="Contoh: Smartphone Nova X12 256GB" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="category" class="text-sm font-bold text-gray-700">Kategori</label>
                <select id="category" name="category" class="{{ $inputClass }}">
                    @foreach (config('storefront.categories') as $category)
                        <option value="{{ $category['slug'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="price" class="text-sm font-bold text-gray-700">Harga (Rp)</label>
                <input id="price" name="price" type="number" min="0" required placeholder="0" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="stock" class="text-sm font-bold text-gray-700">Stok</label>
                <input id="stock" name="stock" type="number" min="0" required value="0" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="image" class="text-sm font-bold text-gray-700">Foto Produk</label>
                <input id="image" name="image" type="file" accept="image/*" class="{{ $inputClass }} file:mr-3 file:rounded-l-xl file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-red-600">
            </div>
            <div class="sm:col-span-2">
                <label for="description" class="text-sm font-bold text-gray-700">Deskripsi <span class="font-medium text-gray-400">(opsional)</span></label>
                <textarea id="description" name="description" rows="4" class="{{ $inputClass }}"></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="rounded-full border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Simpan</button>
        </div>
    </form>
</x-admin-layout>
