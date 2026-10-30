<x-public-layout>
    <x-slot name="title">Produk</x-slot>

    @php
        $linkClass = 'flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition';
        $categoryUrl = fn (?string $slug): string => route('products.index', array_filter(['category' => $slug, 'q' => $search]));
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8">
            <span class="text-sm font-bold uppercase tracking-wider text-red-600">Katalog</span>
            <h1 class="mt-1 text-2xl font-extrabold text-gray-900 sm:text-3xl">Semua Produk</h1>
        </div>

        <div class="grid gap-8 lg:grid-cols-[16rem_1fr]">
            {{-- Sidebar filter kategori --}}
            <aside class="lg:sticky lg:top-24 lg:self-start" aria-label="Filter kategori">
                <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                    <h2 class="px-3 pb-2 text-xs font-bold uppercase tracking-wider text-gray-400">Kategori</h2>
                    <nav class="space-y-1">
                        <a href="{{ $categoryUrl(null) }}" @if (! $activeCategory) aria-current="page" @endif
                            class="{{ $linkClass }} {{ ! $activeCategory ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                            Semua Kategori
                        </a>
                        @foreach ($categories as $category)
                            <a href="{{ $categoryUrl($category->slug) }}" @if ($activeCategory === $category->slug) aria-current="page" @endif
                                class="{{ $linkClass }} {{ $activeCategory === $category->slug ? 'bg-red-50 text-red-600' : 'text-gray-700 hover:bg-gray-50 hover:text-red-600' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <div>
                {{-- Pencarian --}}
                <form action="{{ route('products.index') }}" method="GET" role="search" class="mb-6 flex gap-2">
                    @if ($activeCategory)
                        <input type="hidden" name="category" value="{{ $activeCategory }}">
                    @endif
                    <label for="product-search" class="sr-only">Cari produk</label>
                    <input id="product-search" type="search" name="q" value="{{ $search }}" maxlength="100" placeholder="Cari nama produk atau kategori..."
                        class="w-full rounded-full border-gray-200 bg-white px-5 py-2.5 text-sm placeholder:text-gray-400 focus:border-red-500 focus:ring-red-500">
                    <button type="submit" class="shrink-0 rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Cari</button>
                </form>

                <p class="mb-4 text-sm text-gray-500">
                    Menampilkan {{ $products->total() }} produk
                    @if ($search) untuk &ldquo;{{ $search }}&rdquo; @endif
                    @if ($activeCategory && ($selected = $categories->firstWhere('slug', $activeCategory))) di kategori {{ $selected->name }} @endif
                </p>

                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:gap-5">
                    @forelse ($products as $product)
                        <x-public.product-card :product="$product" />
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                            <p class="font-bold text-gray-900">Produk tidak ditemukan</p>
                            <p class="mt-1 text-sm text-gray-500">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block text-sm font-semibold text-red-600 hover:text-red-700">Reset filter</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</x-public-layout>
