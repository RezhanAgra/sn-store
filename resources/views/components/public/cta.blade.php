<section id="cta" class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="relative mx-auto max-w-7xl overflow-hidden rounded-3xl bg-gradient-to-br from-red-600 via-red-700 to-red-900 px-6 py-14 text-center shadow-2xl shadow-red-900/20 sm:px-12">
        <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-white/10"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-10 h-72 w-72 rounded-full bg-white/10"></div>

        <div class="relative">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">Siap Upgrade Gadget Anda?</h2>
            <p class="mx-auto mt-4 max-w-2xl text-red-100">Daftar sekarang dan dapatkan voucher belanja untuk pembelian pertama, plus info promo eksklusif langsung ke akun Anda.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @guest
                    <a href="{{ route('register') }}" class="rounded-full bg-white px-8 py-3 text-sm font-bold text-red-700 shadow-lg transition hover:bg-red-50">Daftar Gratis</a>
                @endguest
                <a href="#produk" class="rounded-full border border-white/60 px-8 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Lihat Produk</a>
            </div>
        </div>
    </div>
</section>
