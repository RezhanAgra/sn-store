@props(['item'])

@php
    $isReady = filled($item['route']);
    $isActive = $isReady && request()->routeIs($item['active']);
@endphp

@if ($isReady)
    <a href="{{ route($item['route']) }}" @if ($isActive) aria-current="page" @endif
        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $isActive ? 'bg-red-600 text-white shadow-md shadow-red-600/30' : 'hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
        {{ $item['label'] }}
    </a>
@else
    <span class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-500" title="Segera hadir">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
        {{ $item['label'] }}
        <span class="ml-auto rounded-full bg-white/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-gray-500">Soon</span>
    </span>
@endif
