@props(['slides' => []])

<section
    x-data="{
        active: 0,
        total: {{ count($slides) }},
        timer: null,
        next() { this.active = (this.active + 1) % this.total },
        prev() { this.active = (this.active - 1 + this.total) % this.total },
        go(index) { this.active = index },
        start() { this.timer = setInterval(() => this.next(), 6000) },
        stop() { clearInterval(this.timer) },
    }"
    x-init="start()"
    @mouseenter="stop()" @mouseleave="start()"
    class="relative isolate overflow-hidden bg-gradient-to-b from-white to-gray-50"
    aria-roledescription="carousel" aria-label="Promo utama"
>
    {{-- Aksen latar halus --}}
    <div class="pointer-events-none absolute -top-40 right-0 -z-10 h-[36rem] w-[36rem] rounded-full bg-red-100/60 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(rgba(220,38,38,0.08)_1px,transparent_1px)] bg-[size:22px_22px] [mask-image:linear-gradient(to_left,black,transparent_60%)]"></div>

    <div class="relative mx-auto h-[620px] max-w-7xl sm:h-[600px] lg:h-[560px]">
        @foreach ($slides as $index => $slide)
            <div x-show="active === {{ $index }}" @if ($index > 0) x-cloak @endif
                x-transition:enter="transition duration-700 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="absolute inset-0 transition duration-300 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 flex flex-col justify-center gap-6 px-4 pb-20 pt-8 sm:px-6 lg:grid lg:grid-cols-2 lg:items-center lg:gap-12 lg:px-8 lg:pb-16"
                role="group" aria-roledescription="slide" aria-label="{{ $index + 1 }} dari {{ count($slides) }}">

                {{-- Foto produk --}}
                <div class="relative mx-auto flex aspect-square h-52 items-center justify-center sm:h-64 lg:order-2 lg:h-[420px]">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-red-200/70 via-red-50 to-white"></div>
                    <div class="absolute inset-[6%] rounded-full border border-dashed border-red-200"></div>
                    <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }} {{ $slide['highlight'] }}"
                        class="relative aspect-square h-[82%] rounded-full object-cover [mask-image:radial-gradient(closest-side,black_78%,transparent)]"
                        x-show="active === {{ $index }}"
                        x-transition:enter="transition duration-1000 ease-out" x-transition:enter-start="translate-x-8 scale-95 opacity-0" x-transition:enter-end="translate-x-0 scale-100 opacity-100"
                        @if ($index > 0) loading="lazy" @endif>
                    <span class="absolute bottom-[8%] right-0 rounded-full bg-red-600 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-red-600/30 lg:right-[-4%]">{{ $slide['tag'] }}</span>
                </div>

                {{-- Teks --}}
                <div class="max-w-xl lg:order-1">
                    <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-red-600 ring-1 ring-inset ring-red-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                        {{ $slide['badge'] }}
                    </span>

                    @php($headingTag = $index === 0 ? 'h1' : 'h2')
                    <{{ $headingTag }} class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                        {{ $slide['title'] }}
                        <span class="block text-red-600">{{ $slide['highlight'] }}</span>
                    </{{ $headingTag }}>

                    <p class="mt-4 text-base text-gray-600 sm:text-lg">{{ $slide['subtitle'] }}</p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="#produk" class="inline-flex items-center gap-2 rounded-full bg-red-600 px-7 py-3 text-sm font-bold text-white shadow-lg shadow-red-600/25 transition hover:bg-red-700">
                            Belanja Sekarang
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                        <a href="#cta" class="rounded-full border border-gray-200 bg-white px-7 py-3 text-sm font-semibold text-gray-700 transition hover:border-red-200 hover:text-red-600">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Kontrol --}}
    <div class="absolute inset-x-0 bottom-12">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex gap-2">
                @foreach ($slides as $index => $slide)
                    <button type="button" @click="go({{ $index }})" aria-label="Ke slide {{ $index + 1 }}"
                        class="h-2 rounded-full transition-all duration-500" :class="active === {{ $index }} ? 'w-8 bg-red-600' : 'w-2 bg-gray-300 hover:bg-gray-400'"></button>
                @endforeach
            </div>

            <div class="flex gap-2">
                <button type="button" @click="prev()" class="rounded-full border border-gray-200 bg-white p-2.5 text-gray-600 shadow-sm transition hover:border-red-200 hover:text-red-600" aria-label="Slide sebelumnya">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/></svg>
                </button>
                <button type="button" @click="next()" class="rounded-full border border-gray-200 bg-white p-2.5 text-gray-600 shadow-sm transition hover:border-red-200 hover:text-red-600" aria-label="Slide berikutnya">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>
