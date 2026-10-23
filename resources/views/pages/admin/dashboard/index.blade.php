<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $maxRevenue = max(array_column($weeklyRevenue, 'amount'));
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
            <x-admin.add-button :href="route('admin.transactions.create')">Tambah Transaksi</x-admin.add-button>
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
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $transaction['code'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $transaction['customer'] }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $transaction['date'] }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">Rp {{ number_format($transaction['total'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4"><x-admin.status-badge :status="$transaction['status']" /></td>
                            <td class="px-6 py-4">
                                <x-admin.row-actions :label="$transaction['code']" modal="delete-transaction" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <x-admin.delete-modal name="delete-transaction" title="Hapus transaksi?" />
</x-admin-layout>
