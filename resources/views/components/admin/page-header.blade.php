@props(['title', 'subtitle' => null])

<div class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <span class="text-sm font-bold uppercase tracking-wider text-red-600">Kelola</span>
        <h2 class="mt-1 text-2xl font-extrabold text-gray-900 sm:text-3xl">{{ $title }}</h2>
        @if ($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($action)
        {{ $action }}
    @endisset
</div>
