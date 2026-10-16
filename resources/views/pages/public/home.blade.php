<x-public-layout>
    <x-slot name="title">Beranda</x-slot>

    <x-public.hero-slider :slides="$slides" />

    {{-- Fitur singkat --}}
    <section class="mx-auto -mt-8 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative grid gap-4 rounded-2xl bg-white p-6 shadow-xl shadow-gray-900/5 sm:grid-cols-3">
            @foreach ([['Produk Original', 'Garansi resmi 100%'], ['Pengiriman Cepat', 'Ke seluruh Indonesia'], ['Pembayaran Aman', 'Banyak metode pembayaran']] as [$title, $desc])
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $title }}</p>
                        <p class="text-xs text-gray-500">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Produk terbaru --}}
    <section id="produk" class="mx-auto max-w-7xl scroll-mt-20 px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <span class="text-sm font-bold uppercase tracking-wider text-red-600">Terbaru</span>
                <h2 class="mt-1 text-2xl font-extrabold text-gray-900 sm:text-3xl">Produk Terbaru</h2>
            </div>
            <a href="#" class="text-sm font-semibold text-red-600 hover:text-red-700">Lihat semua &rarr;</a>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5 lg:gap-5">
            @foreach ($products as $product)
                <x-public.product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <x-public.cta />
</x-public-layout>
