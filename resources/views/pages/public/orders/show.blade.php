<x-public-layout>
    <x-slot name="title">Pesanan {{ $order->code }}</x-slot>

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <span class="text-sm font-bold uppercase tracking-wider text-red-600">Pesanan</span>
                <h1 class="mt-1 text-2xl font-extrabold text-gray-900">{{ $order->code }}</h1>
            </div>
            <x-admin.status-badge :status="$order->status->value" />
        </div>

        <section class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="font-extrabold text-gray-900">Rincian Produk</h2>
            <ul class="mt-4 divide-y divide-gray-100 text-sm">
                @foreach ($order->items as $item)
                    <li class="flex justify-between gap-3 py-3">
                        <span class="text-gray-700">{{ $item->product_name }} <span class="text-gray-400">&times; {{ $item->quantity }}</span></span>
                        <span class="shrink-0 font-semibold text-gray-900">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <dl class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                <dt class="text-sm text-gray-500">Total</dt>
                <dd class="text-lg font-extrabold text-gray-900">Rp{{ number_format($order->total, 0, ',', '.') }}</dd>
            </dl>
        </section>

        <section class="mt-6 rounded-2xl border border-gray-100 bg-white p-6 text-sm shadow-sm">
            <h2 class="font-extrabold text-gray-900">Data Penerima</h2>
            <dl class="mt-4 space-y-2 text-gray-700">
                <div><dt class="inline font-semibold">Nama:</dt> <dd class="inline">{{ $order->name }}</dd></div>
                <div><dt class="inline font-semibold">Nomor HP:</dt> <dd class="inline">{{ $order->phone }}</dd></div>
                <div><dt class="inline font-semibold">Email:</dt> <dd class="inline">{{ $order->email }}</dd></div>
                <div><dt class="inline font-semibold">Alamat:</dt> <dd class="inline">{{ $order->address }}</dd></div>
            </dl>
        </section>

        @if ($order->status === \App\Enums\OrderStatus::Pending)
            <section class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-900">
                @if ($order->payment_url)
                    <p class="font-semibold">Selesaikan pembayaran Anda untuk memproses pesanan.</p>
                    <a href="{{ $order->payment_url }}" class="mt-4 inline-block rounded-full bg-red-600 px-6 py-2.5 font-semibold text-white shadow-md shadow-red-600/25 hover:bg-red-700">Bayar Sekarang</a>
                @else
                    <p class="font-semibold">Menunggu pembayaran.</p>
                    <p class="mt-1">Link pembayaran belum tersedia. Silakan coba buat ulang.</p>
                    <form method="POST" action="{{ route('orders.pay', $order) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="rounded-full bg-red-600 px-6 py-2.5 font-semibold text-white shadow-md shadow-red-600/25 hover:bg-red-700">Coba Bayar Lagi</button>
                    </form>
                @endif
            </section>
        @elseif ($order->status === \App\Enums\OrderStatus::Paid)
            <section class="mt-6 rounded-2xl bg-emerald-50 p-6 text-sm font-semibold text-emerald-800">Pembayaran diterima. Pesanan Anda sedang diproses.</section>
        @elseif ($order->status === \App\Enums\OrderStatus::Cancelled)
            <section class="mt-6 rounded-2xl bg-red-50 p-6 text-sm font-semibold text-red-800">Pesanan dibatalkan atau pembayaran kedaluwarsa. Stok produk telah dikembalikan.</section>
        @endif

        <a href="{{ route('products.index') }}" class="mt-8 inline-block text-sm font-semibold text-red-600 hover:text-red-700">&larr; Lanjut belanja</a>
    </div>
</x-public-layout>
