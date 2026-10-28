<x-admin-layout>
    <x-slot name="title">Kategori</x-slot>

    @if (session('status'))
        <div class="mb-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
    @endif

    <section class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-gray-900/5">
        <div class="flex flex-wrap items-end justify-between gap-4 px-6 py-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-red-600">Katalog</span>
                <h2 class="mt-1 text-xl font-extrabold text-gray-900">Daftar Kategori</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $categories->count() }} kategori produk terdaftar.</p>
            </div>
            <x-admin.add-button :href="route('admin.categories.create')">Tambah Kategori</x-admin.add-button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Slug</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3">Dibuat</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-5 text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-5 font-bold text-gray-900">{{ $category->name }}</td>
                            <td class="px-6 py-5 text-gray-500">{{ $category->slug }}</td>
                            <td class="px-6 py-5 text-gray-700">{{ $category->description ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-5 text-gray-500">{{ $category->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-5"><x-admin.row-actions :label="$category->name" modal="delete-category" :edit-url="route('admin.categories.edit', $category)" :delete-url="route('admin.categories.destroy', $category)" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin-layout>
