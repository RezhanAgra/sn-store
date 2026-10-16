@props(['slides' => []])

<section
    x-data="{
        active: 0,
        total: {{ count($slides) }},
        timer: null,
        next() { this.active = (this.active + 1) % this.total },
        prev() { this.active = (this.active - 1 + this.total) % this.total },
        start() { this.timer = setInterval(() => this.next(), 5000) },
        stop() { clearInterval(this.timer) },
    }"
    x-init="start()"
    @mouseenter="stop()" @mouseleave="start()"
    class="relative overflow-hidden bg-gray-900"
    aria-roledescription="carousel" aria-label="Promo utama"
>
    <div class="relative h-[420px] sm:h-[480px] lg:h-[560px]">
        @foreach ($slides as $index => $slide)
            <div x-show="active === {{ $index }}" x-cloak
                x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-700 absolute inset-0" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0">
                <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="h-full w-full object-cover" @if ($index > 0) loading="lazy" @endif>
                <div class="absolute inset-0 bg-gradient-to-r from-red-950/90 via-red-900/60 to-transparent"></div>

                <div class="absolute inset-0 mx-auto flex max-w-7xl items-center px-4 sm:px-6 lg:px-8">
                    <div class="max-w-xl text-white">
                        <span class="inline-block rounded-full bg-red-600 px-4 py-1 text-xs font-bold uppercase tracking-wider shadow-lg">{{ $slide['badge'] }}</span>
                        <h1 class="mt-4 text-3xl font-extrabold leading-tight sm:text-5xl">{{ $slide['title'] }}</h1>
                        <p class="mt-4 text-base text-red-50 sm:text-lg">{{ $slide['subtitle'] }}</p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="#produk" class="rounded-full bg-white px-7 py-3 text-sm font-bold text-red-700 shadow-lg transition hover:bg-red-50">Belanja Sekarang</a>
                            <a href="#cta" class="rounded-full border border-white/60 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Kontrol --}}
    <button type="button" @click="prev()" class="absolute left-3 top-1/2 hidden -translate-y-1/2 rounded-full bg-white/20 p-2 text-white backdrop-blur transition hover:bg-white/40 sm:block" aria-label="Slide sebelumnya">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/></svg>
    </button>
    <button type="button" @click="next()" class="absolute right-3 top-1/2 hidden -translate-y-1/2 rounded-full bg-white/20 p-2 text-white backdrop-blur transition hover:bg-white/40 sm:block" aria-label="Slide berikutnya">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
    </button>

    <div class="absolute bottom-5 left-1/2 flex -translate-x-1/2 gap-2">
        @foreach ($slides as $index => $slide)
            <button type="button" @click="active = {{ $index }}" aria-label="Ke slide {{ $index + 1 }}"
                class="h-2.5 rounded-full transition-all" :class="active === {{ $index }} ? 'w-8 bg-white' : 'w-2.5 bg-white/50'"></button>
        @endforeach
    </div>
</section>
