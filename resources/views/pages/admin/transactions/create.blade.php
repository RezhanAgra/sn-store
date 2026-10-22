<x-admin-layout>
    <x-slot name="title">Tambah Transaksi</x-slot>

    @php
        $inputClass = 'mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500';
    @endphp

    <div class="mb-8">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">&larr; Kembali ke dashboard</a>
        <h2 class="mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Tambah Transaksi</h2>
        <p class="mt-1 text-sm text-gray-500">Isi data transaksi baru di bawah ini.</p>
    </div>

    <form x-data="{ saved: false }" @submit.prevent="saved = true" class="max-w-3xl rounded-2xl bg-white p-6 shadow-xl shadow-gray-900/5 sm:p-8">
        <div x-show="saved" x-cloak x-transition class="mb-6 rounded-xl bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
            Tampilan saja: data belum disimpan karena database transaksi belum tersedia.
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="customer" class="text-sm font-bold text-gray-700">Nama Pelanggan</label>
                <input id="customer" name="customer" type="text" required placeholder="Contoh: Budi Santoso" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="date" class="text-sm font-bold text-gray-700">Tanggal</label>
                <input id="date" name="date" type="date" required value="{{ now()->toDateString() }}" class="{{ $inputClass }}">
            </div>
            <div class="sm:col-span-2">
                <label for="product" class="text-sm font-bold text-gray-700">Produk</label>
                <input id="product" name="product" type="text" required placeholder="Nama produk yang dibeli" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="quantity" class="text-sm font-bold text-gray-700">Jumlah</label>
                <input id="quantity" name="quantity" type="number" min="1" value="1" required class="{{ $inputClass }}">
            </div>
            <div>
                <label for="total" class="text-sm font-bold text-gray-700">Total (Rp)</label>
                <input id="total" name="total" type="number" min="0" required placeholder="0" class="{{ $inputClass }}">
            </div>
            <div class="sm:col-span-2">
                <label for="status" class="text-sm font-bold text-gray-700">Status</label>
                <select id="status" name="status" class="{{ $inputClass }}">
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label for="note" class="text-sm font-bold text-gray-700">Catatan <span class="font-medium text-gray-400">(opsional)</span></label>
                <textarea id="note" name="note" rows="3" class="{{ $inputClass }}"></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Simpan</button>
        </div>
    </form>
</x-admin-layout>
