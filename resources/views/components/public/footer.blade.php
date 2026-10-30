@props(['categories' => []])

<footer class="bg-gray-950 text-gray-400">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-lg font-extrabold text-white">SN</span>
                <span class="text-xl font-extrabold text-white">store<span class="text-red-500">.</span></span>
            </a>
            <p class="mt-4 text-sm leading-relaxed">SN Store adalah toko elektronik online terpercaya. Kami menyediakan smartphone, laptop, audio, dan aksesoris original dengan harga bersaing dan garansi resmi.</p>
        </div>

        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-white">Navigasi</h3>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-red-500">Beranda</a></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-red-500">Produk</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="hover:text-red-500">Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-red-500">Daftar</a></li>
                @endguest
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-white">Kategori</h3>
            <ul class="mt-4 space-y-2 text-sm">
                @foreach ($categories as $category)
                    <li><a href="{{ route('products.index', ['category' => $category->slug]) }}" class="hover:text-red-500">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-bold uppercase tracking-wider text-white">Kontak</h3>
            <ul class="mt-4 space-y-3 text-sm">
                <li>Jl. Teknologi No. 88, Jakarta, Indonesia</li>
                <li><a href="mailto:halo@snstore.test" class="hover:text-red-500">halo@snstore.test</a></li>
                <li><a href="tel:+6281234567890" class="hover:text-red-500">+62 812-3456-7890</a></li>
                <li>Senin - Sabtu, 09.00 - 18.00 WIB</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10 py-5 text-center text-xs">
        &copy; {{ date('Y') }} {{ config('app.name', 'SN Store') }}. Hak cipta dilindungi.
    </div>
</footer>
