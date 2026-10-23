@props(['placeholder' => 'Cari...'])

<div class="relative w-full sm:max-w-xs">
    <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/></svg>
    <input type="search" x-model="query" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}"
        class="w-full rounded-full border-gray-200 bg-gray-100 py-2 pl-10 pr-4 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500">
</div>
