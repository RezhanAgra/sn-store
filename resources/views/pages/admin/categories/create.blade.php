<x-admin-layout>
    <x-slot name="title">Tambah Kategori</x-slot>

    <div class="mb-8">
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">&larr; Kembali ke kategori</a>
        <h2 class="mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Tambah Kategori</h2>
        <p class="mt-1 text-sm text-gray-500">Isi data kategori baru di bawah ini.</p>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-3xl rounded-2xl bg-white p-6 shadow-xl shadow-gray-900/5 sm:p-8">
        @csrf
        @include('pages.admin.categories._form', ['category' => null])
    </form>
</x-admin-layout>
