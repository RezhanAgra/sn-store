@props(['status'])

@php
    $styles = [
        'paid' => ['Dibayar', 'bg-emerald-50 text-emerald-700'],
        'pending' => ['Menunggu', 'bg-amber-50 text-amber-700'],
        'shipped' => ['Dikirim', 'bg-sky-50 text-sky-700'],
        'cancelled' => ['Dibatalkan', 'bg-red-50 text-red-700'],
        'active' => ['Aktif', 'bg-emerald-50 text-emerald-700'],
        'verified' => ['Terverifikasi', 'bg-emerald-50 text-emerald-700'],
        'unverified' => ['Belum Verifikasi', 'bg-amber-50 text-amber-700'],
        'empty' => ['Stok Habis', 'bg-red-50 text-red-700'],
    ];
    [$label, $class] = $styles[$status];
@endphp

<span class="rounded-full px-3 py-1 text-xs font-bold {{ $class }}">{{ $label }}</span>
