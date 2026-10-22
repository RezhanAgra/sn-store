@props(['categories' => []])

<header x-data="{ mobileOpen: false, categoryOpen: false }" class="sticky top-0 z-50 border-b border-gray-100 bg-white/90 shadow-sm backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2" aria-label="SN Store">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-lg font-extrabold tracking-tight text-white shadow-md shadow-red-600/30">SN</span>
            <span class="hidden text-xl font-extrabold tracking-tight text-gray-900 sm:block">store<span class="text-red-600">.</span></span>
        </a>

        {{-- Menu desktop --}}
        <nav class="hidden items-center gap-1 lg:flex" aria-label="Menu utama">
            <a href="{{ route('home') }}" class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }}">Beranda</a>
            <a href="#produk" class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:text-red-600">Produk</a>

            <div class="relative" @click.outside="categoryOpen = false" @keydown.escape.window="categoryOpen = false">
                <button type="button" @click="categoryOpen = !categoryOpen" :aria-expanded="categoryOpen"
                    class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:text-red-600">
                    Kategori
                    <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': categoryOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
                </button>
                <div x-show="categoryOpen" x-cloak x-transition.origin.top
                    class="absolute left-0 mt-2 w-60 overflow-hidden rounded-xl border border-gray-100 bg-white p-2 shadow-xl">
                    @foreach ($categories as $category)
                        <a href="#produk" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600">{{ $category['name'] }}</a>
                    @endforeach
                </div>
            </div>
        </nav>

        {{-- Search desktop --}}
        <form action="#" method="GET" role="search" class="hidden max-w-md flex-1 md:block">
            <label for="search-desktop" class="sr-only">Cari produk</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/></svg>
                <input id="search-desktop" type="search" name="q" placeholder="Cari smartphone, laptop, headphone..."
                    class="w-full rounded-full border-gray-200 bg-gray-100 py-2 pl-10 pr-4 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500">
            </div>
        </form>

        {{-- Aksi --}}
        <div class="flex items-center gap-2">
            <a href="#" class="relative rounded-full p-2 text-gray-700 hover:bg-red-50 hover:text-red-600" aria-label="Keranjang">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.4a1 1 0 0 1 .97.76L5.1 6m0 0 1.6 7.2a1 1 0 0 0 .98.8h9.6a1 1 0 0 0 .96-.72L20 6H5.1ZM9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm9 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                <span class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[11px] font-bold text-white">0</span>
            </a>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="hidden rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700 sm:inline-flex">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hidden rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700 sm:inline-flex">Login</a>
            @endauth

            <button type="button" @click="mobileOpen = !mobileOpen" class="rounded-lg p-2 text-gray-700 hover:bg-gray-100 lg:hidden" aria-label="Buka menu" :aria-expanded="mobileOpen">
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div x-show="mobileOpen" x-cloak x-transition class="border-t border-gray-100 bg-white px-4 pb-4 pt-3 lg:hidden">
        <form action="#" method="GET" role="search" class="mb-3 md:hidden">
            <label for="search-mobile" class="sr-only">Cari produk</label>
            <input id="search-mobile" type="search" name="q" placeholder="Cari produk..."
                class="w-full rounded-full border-gray-200 bg-gray-100 px-4 py-2 text-sm focus:border-red-500 focus:bg-white focus:ring-red-500">
        </form>
        <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-red-600">Beranda</a>
        <a href="#produk" class="block rounded-lg px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-red-600">Produk</a>
        <p class="px-3 pb-1 pt-3 text-xs font-bold uppercase tracking-wider text-gray-400">Kategori</p>
        @foreach ($categories as $category)
            <a href="#produk" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600">{{ $category['name'] }}</a>
        @endforeach
        @auth
            <a href="{{ route('admin.dashboard') }}" class="mt-3 block rounded-full bg-red-600 px-5 py-2.5 text-center text-sm font-semibold text-white sm:hidden">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="mt-3 block rounded-full bg-red-600 px-5 py-2.5 text-center text-sm font-semibold text-white sm:hidden">Login</a>
        @endauth
    </div>
</header>
