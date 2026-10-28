<x-admin-layout>
    <x-slot name="title">Produk</x-slot>

    <x-admin.page-header title="Produk" subtitle="Kelola katalog produk SN Store.">
        <x-slot name="action">
            <x-admin.add-button :href="route('admin.products.create')">Tambah Produk</x-admin.add-button>
        </x-slot>
    </x-admin.page-header>

    @if (session('status'))
        <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
    @endif

    <section x-data="{ query: '' }" class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-gray-900/5">
        <div class="px-6 py-5">
            <x-admin.search-input placeholder="Cari nama atau kategori..." />
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th scope="col" class="px-6 py-3">Produk</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-right">Harga</th>
                        <th scope="col" class="px-6 py-3 text-right">Stok</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        <tr data-search="{{ strtolower($product->name.' '.$product->category?->name) }}" x-show="$el.dataset.search.includes(query.toLowerCase())" class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="h-12 w-12 shrink-0 rounded-xl bg-gray-100 object-cover">
                                    <span class="font-bold text-gray-900">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $product->category?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-gray-700">{{ $product->stock }}</td>
                            <td class="px-6 py-4"><x-admin.status-badge :status="$product->stock > 0 ? 'active' : 'empty'" /></td>
                            <td class="px-6 py-4"><x-admin.row-actions :label="$product->name" modal="delete-product" :edit-url="route('admin.products.edit', $product)" :delete-url="route('admin.products.destroy', $product)" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="border-t border-gray-100 px-6 py-4 text-xs font-medium text-gray-500">Menampilkan {{ $products->count() }} produk</p>
    </section>
</x-admin-layout>
