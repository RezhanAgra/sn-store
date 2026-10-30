@props(['product'])

<article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-600/10">
    <div class="relative aspect-square overflow-hidden bg-gray-100">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @if ($product->stock < 1)
            <span class="absolute left-3 top-3 rounded-full bg-gray-900 px-2.5 py-1 text-xs font-bold text-white shadow">Stok Habis</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-4">
        <span class="text-xs font-semibold uppercase tracking-wider text-red-600">{{ $product->category?->name }}</span>
        <h3 class="mt-1 line-clamp-2 min-h-10 text-sm font-semibold text-gray-900">
            <a href="#">{{ $product->name }}</a>
        </h3>

        <div class="mt-3">
            <p class="text-lg font-extrabold text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
        </div>

        <form method="POST" action="{{ route('cart.store') }}" class="mt-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" aria-label="Tambah {{ $product->
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.4a1 1 0 0 1 .97.76L5.1 6m0 0 1.6 7.2a1 1 0 0 0 .98.8h9.6a1 1 0 0 0 .96-.72L20 6H5.1ZM9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm9 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                Tambah ke Keranjang
            </button>
        </form>
    </div>
</article>
