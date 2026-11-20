<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name', 'SN Store') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen">
            {{-- Panel merek (desktop) --}}
            <aside class="relative hidden w-1/2 overflow-hidden bg-gradient-to-br from-red-600 via-red-700 to-red-900 lg:flex lg:flex-col lg:justify-between lg:p-12 xl:p-16">
                <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute -bottom-32 -left-20 h-[28rem] w-[28rem] rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.12)_1px,transparent_1px)] bg-[size:22px_22px] [mask-image:linear-gradient(to_bottom,black,transparent_70%)]"></div>

                <a href="{{ route('home') }}" class="relative flex items-center gap-2" aria-label="SN Store">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-lg font-extrabold tracking-tight text-red-600 shadow-lg">SN</span>
                    <span class="text-xl font-extrabold tracking-tight text-white">store<span class="text-red-200">.</span></span>
                </a>

                <div class="relative flex justify-center py-10">
                    <div class="relative aspect-square w-full max-w-sm">
                        <div class="absolute inset-0 rounded-full bg-white/10 ring-1 ring-white/20"></div>
                        <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=900&q=80&auto=format&fit=crop" alt="Jam tangan pintar"
                            class="absolute inset-[8%] h-[84%] w-[84%] rounded-full object-cover shadow-2xl shadow-red-950/40">
                        <span class="absolute -right-2 top-10 rounded-full bg-white px-4 py-2 text-xs font-bold text-red-600 shadow-xl">Garansi Resmi</span>
                        <span class="absolute -left-4 bottom-14 rounded-full bg-gray-900 px-4 py-2 text-xs font-bold text-white shadow-xl">Gratis Ongkir</span>
                    </div>
                </div>

                <div class="relative text-white">
                    <h2 class="text-3xl font-extrabold leading-tight xl:text-4xl">Gadget impian,<br>satu klik lagi.</h2>
                    <p class="mt-3 max-w-md text-red-100">Smartphone, laptop, dan aksesori original dengan pembayaran aman dan pengiriman cepat ke seluruh Indonesia.</p>
                </div>
            </aside>

            {{-- Form --}}
            <main class="relative flex w-full flex-col justify-center px-6 py-12 sm:px-12 lg:w-1/2 xl:px-24">
                <div class="pointer-events-none absolute right-0 top-0 h-72 w-72 rounded-full bg-red-50 blur-3xl lg:hidden"></div>

                <div class="relative mx-auto w-full max-w-md">
                    <a href="{{ route('home') }}" class="mb-10 flex items-center gap-2 lg:hidden" aria-label="SN Store">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-lg font-extrabold tracking-tight text-white shadow-md shadow-red-600/30">SN</span>
                        <span class="text-xl font-extrabold tracking-tight text-gray-900">store<span class="text-red-600">.</span></span>
                    </a>

                    {{ $slot }}
                </div>

                <a href="{{ route('home') }}" class="relative mx-auto mt-10 inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 transition hover:text-red-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Kembali ke beranda
                </a>
            </main>
        </div>
    </body>
</html>
