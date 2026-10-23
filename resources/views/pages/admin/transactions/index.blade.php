<x-admin-layout>
    <x-slot name="title">Transaksi</x-slot>

    <x-admin.page-header title="Transaksi" subtitle="Pantau seluruh transaksi pelanggan.">
        <x-slot name="action">
            <x-admin.add-button :href="route('admin.transactions.create')">Tambah Transaksi</x-admin.add-button>
        </x-slot>
    </x-admin.page-header>

    <section x-data="{ query: '', status: '' }" class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-gray-900/5">
        <div class="flex flex-col gap-3 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <x-admin.search-input placeholder="Cari kode atau pelanggan..." />
            <select x-model="status" aria-label="Filter status" class="rounded-full border-gray-200 bg-gray-100 py-2 pl-4 pr-10 text-sm font-medium text-gray-700 focus:border-red-500 focus:ring-red-500">
                <option value="">Semua status</option>
                @foreach (['paid' => 'Dibayar', 'pending' => 'Menunggu', 'shipped' => 'Dikirim', 'cancelled' => 'Dibatalkan'] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
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
                    @foreach ($transactions as $transaction)
                        <tr data-search="{{ strtolower($transaction['code'].' '.$transaction['customer']) }}" data-status="{{ $transaction['status'] }}"
                            x-show="(status === '' || $el.dataset.status === status) && $el.dataset.search.includes(query.toLowerCase())" class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $transaction['code'] }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $transaction['customer'] }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $transaction['date'] }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">Rp {{ number_format($transaction['total'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4"><x-admin.status-badge :status="$transaction['status']" /></td>
                            <td class="px-6 py-4"><x-admin.row-actions :label="$transaction['code']" modal="delete-transaction" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="border-t border-gray-100 px-6 py-4 text-xs font-medium text-gray-500">Menampilkan {{ count($transactions) }} transaksi</p>
    </section>

    <x-admin.delete-modal name="delete-transaction" title="Hapus transaksi?" />
</x-admin-layout>
