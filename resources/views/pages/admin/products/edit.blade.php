<x-admin-layout>
    <x-slot name="title">Ubah Produk</x-slot>

    <div class="mb-8">
        <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">&larr; Kembali ke produk</a>
        <h2 class="mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Ubah Produk</h2>
        <p class="mt-1 text-sm text-gray-500">Perbarui data produk {{ $product->name }}.</p>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="max-w-3xl rounded-2xl bg-white p-6 shadow-xl shadow-gray-900/5 sm:p-8">
        @csrf
        @method('PUT')
        @include('pages.admin.products._form', ['product' => $product])
    </form>
</x-admin-layout>
