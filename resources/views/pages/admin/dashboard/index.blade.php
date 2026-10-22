<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $maxRevenue = max(array_column($weeklyRevenue, 'amount'));
        $statusStyles = [
            'paid' => ['Dibayar', 'bg-emerald-50 text-emerald-700'],
            'pending' => ['Menunggu', 'bg-amber-50 text-amber-700'],
            'shipped' => ['Dikirim', 'bg-sky-50 text-sky-700'],
            'cancelled' => ['Dibatalkan', 'bg-red-50 text-red-700'],
        ];
    @endphp

    {{-- Sambutan --}}
    <div class="mb-8">
        <span class="text-sm font-bold uppercase tracking-wider text-red-600">Ringkasan</span>
        <h2 class="mt-1 text-2xl font-extrabold text-gray-900 sm:text-3xl">Halo, {{ Auth::user()->name }} 👋</h2>
        <p class="mt-1 text-sm text-gray-500">Berikut performa SN Store hari ini.</p>
    </div>

    {{-- Statistik --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <x-admin.stat-card :label="$stat['label']" :value="$stat['value']" :change="$stat['change']" :icon="$stat['icon']" />
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        {{-- Grafik pendapatan --}}
        <section class="rounded-2xl bg-white p-6 shadow-xl shadow-gray-900/5 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-gray-900">Pendapatan Minggu Ini</h3>
                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-600">7 hari terakhir</span>
            </div>
            <div class="mt-6 flex h-56 items-end gap-3 sm:gap-5" role="img" aria-label="Grafik batang pendapatan 7 hari terakhir">
                @foreach ($weeklyRevenue as $point)
                    <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <span class="text-[11px] font-bold text-gray-500 opacity-0 transition group-hover:opacity-100">{{ number_format($point['amount'] / 1000000, 1, ',', '.') }} jt</span>
                        <div class="w-full rounded-t-lg bg-gradient-to-t from-red-600 to-red-400 transition group-hover:from-red-700 group-hover:to-red-500" style="height: {{ round($point['amount'] / $maxRevenue * 100) }}%"></div>
                        <span class="text-xs font-semibold text-gray-500">{{ $point['day'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Produk terlaris --}}
        <section class="rounded-2xl bg-white p-6 shadow-xl shadow-gray-900/5">
            <h3 class="text-lg font-extrabold text-gray-900">Produk Terlaris</h3>
            <ul class="mt-5 space-y-4">
                @foreach ($topProducts as $product)
                    <li class="flex items-center gap-3">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="lazy" class="h-12 w-12 shrink-0 rounded-xl bg-gray-100 object-cover">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-gray-900">{{ $product['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $product['category'] }}</p>
                        </div>
                        <span class="text-sm font-extrabold text-red-600">{{ $product['sold'] }}<span class="ml-0.5 text-xs font-semibold text-gray-400">terjual</span></span>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>

    {{-- Transaksi terbaru --}}
    <section class="mt-8 overflow-hidden rounded-2xl bg-white shadow-xl shadow-gray-900/5">
        <div class="flex items-center justify-between px-6 py-5">
            <h3 class="text-lg font-extrabold text-gray-900">Transaksi Terbaru</h3>
            <a href="{{ route('admin.transactions.create') }}" class="inline-flex items-center gap-2 rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Tambah Transaksi
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th scope="col" class="px-6 py-3">Kode</th>
                        <th scope="col" class="px-6 py-3">Pelanggan</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-right">Total</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($recentTransactions as $transaction)
                        @php([$statusLabel, $statusClass] = $statusStyles[$transaction['status']])
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $transaction['code'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $transaction['customer'] }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $transaction['date'] }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">Rp {{ number_format($transaction['total'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="#" class="rounded-lg p-2 text-gray-500 transition hover:bg-sky-50 hover:text-sky-600" aria-label="Edit {{ $transaction['code'] }}" title="Edit">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                    </a>
                                    <button type="button" x-data @click="$dispatch('open-modal', 'delete-transaction'); $dispatch('set-delete-code', '{{ $transaction['code'] }}')" class="rounded-lg p-2 text-gray-500 transition hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $transaction['code'] }}" title="Hapus">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Konfirmasi hapus (tampilan saja, belum terhubung ke database) --}}
    <x-modal name="delete-transaction" maxWidth="md">
        <div x-data="{ code: '' }" @set-delete-code.window="code = $event.detail" class="p-6">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-extrabold text-gray-900">Hapus transaksi?</h3>
            <p class="mt-1 text-sm text-gray-500">Transaksi <span class="font-bold text-gray-900" x-text="code"></span> akan dihapus dan tidak dapat dikembalikan.</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="button" x-on:click="$dispatch('close')" class="rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-red-600/25 hover:bg-red-700">Ya, hapus</button>
            </div>
        </div>
    </x-modal>
</x-admin-layout>
