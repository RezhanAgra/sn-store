@props(['label', 'value', 'change', 'icon'])

<div class="rounded-2xl bg-white p-5 shadow-xl shadow-gray-900/5">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-semibold text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-extrabold tracking-tight text-gray-900">{{ $value }}</p>
        </div>
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
        </span>
    </div>
    <p class="mt-4 flex items-center gap-1 text-xs font-semibold {{ $change >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
        <svg class="h-4 w-4 {{ $change >= 0 ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/></svg>
        {{ abs($change) }}%
        <span class="font-medium text-gray-400">dibanding bulan lalu</span>
    </p>
</div>
