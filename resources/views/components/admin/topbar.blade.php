@props(['title' => null])

<header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-100 bg-white/90 px-4 shadow-sm backdrop-blur sm:px-6 lg:px-8">
    <button type="button" @click="sidebarOpen = true" class="rounded-lg p-2 text-gray-700 hover:bg-gray-100 lg:hidden" aria-label="Buka menu">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <h1 class="text-lg font-extrabold tracking-tight text-gray-900">{{ $title }}</h1>

    <div class="relative ml-auto" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
        <button type="button" @click="open = !open" :aria-expanded="open" class="flex items-center gap-3 rounded-full py-1 pl-1 pr-3 hover:bg-gray-100">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-red-500 to-red-700 text-sm font-bold uppercase text-white">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
            <span class="hidden text-left sm:block">
                <span class="block text-sm font-bold leading-tight text-gray-900">{{ Auth::user()->name }}</span>
                <span class="block text-xs leading-tight text-gray-500">Administrator</span>
            </span>
            <svg class="h-4 w-4 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/></svg>
        </button>

        <div x-show="open" x-cloak x-transition.origin.top.right class="absolute right-0 mt-2 w-56 overflow-hidden rounded-xl border border-gray-100 bg-white p-2 shadow-xl">
            <p class="truncate px-3 py-2 text-xs text-gray-500">{{ Auth::user()->email }}</p>
            <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600">Keluar</button>
            </form>
        </div>
    </div>
</header>
