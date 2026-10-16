@props(['product'])

@php
    $hasDiscount = ! empty($product['old_price']);
    $discount = $hasDiscount ? (int) round((1 - $product['price'] / $product['old_price']) * 100) : 0;
@endphp

<article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-red-600/10">
    <div class="relative aspect-square overflow-hidden bg-gray-100">
        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="lazy"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @if ($hasDiscount)
            <span class="absolute left-3 top-3 rounded-full bg-red-600 px-2.5 py-1 text-xs font-bold text-white shadow">-{{ $discount }}%</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-4">
        <span class="text-xs font-semibold uppercase tracking-wider text-red-600">{{ $product['category'] }}</span>
        <h3 class="mt-1 line-clamp-2 min-h-10 text-sm font-semibold text-gray-900">
            <a href="#">{{ $product['name'] }}</a>
        </h3>

        <div class="mt-2 flex items-center gap-1 text-xs text-gray-500">
            <svg class="h-4 w-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8L10 14.8l-5.2 2.8 1-5.8L1.5 7.7l5.9-.9L10 1.5Z"/></svg>
            {{ number_format($product['rating'], 1) }}
        </div>

        <div class="mt-3">
            <p class="text-lg font-extrabold text-gray-900">Rp{{ number_format($product['price'], 0, ',', '.') }}</p>
            @if ($hasDiscount)
                <p class="text-xs text-gray-400 line-through">Rp{{ number_format($product['old_price'], 0, ',', '.') }}</p>
            @endif
        </div>

        <button type="button" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah ke Keranjang
        </button>
    </div>
</article>
