@props(['menu' => []])

{{-- Overlay mobile --}}
<div x-show="sidebarOpen" x-cloak x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-950/60 backdrop-blur-sm lg:hidden" aria-hidden="true"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-gray-950 text-gray-400 transition-transform duration-200 lg:translate-x-0" aria-label="Sidebar admin">
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-white/10 px-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2" aria-label="SN Store Admin">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-lg font-extrabold tracking-tight text-white shadow-md shadow-red-600/30">SN</span>
            <span class="text-xl font-extrabold tracking-tight text-white">store<span class="text-red-500">.</span></span>
        </a>
        <button type="button" @click="sidebarOpen = false" class="rounded-lg p-1.5 text-gray-400 hover:bg-white/10 hover:text-white lg:hidden" aria-label="Tutup menu">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
        <p class="px-3 pb-2 text-xs font-bold uppercase tracking-wider text-gray-500">Menu</p>

        @foreach ($menu as $item)
            <x-admin.sidebar-link :item="$item" />
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
        <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-400 transition hover:bg-white/5 hover:text-white">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
            Lihat Toko
        </a>
    </div>
</aside>
