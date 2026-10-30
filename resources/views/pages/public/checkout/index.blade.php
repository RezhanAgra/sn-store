<x-public-layout>
    <x-slot name="title">Checkout</x-slot>

    @php
        $inputClass = 'mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500';
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('cart.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">&larr; Kembali ke keranjang</a>
        <h1 class="mb-8 mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Checkout</h1>

        <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
            <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                @csrf
                <h2 class="text-lg font-extrabold text-gray-900">Data Penerima</h2>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="text-sm font-bold text-gray-700">Nama</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="{{ $inputClass }}">
                        @error('name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="text-sm font-bold text-gray-700">Nomor HP</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" required placeholder="081234567890" class="{{ $inputClass }}">
                        @error('phone') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="email" class="text-sm font-bold text-gray-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="{{ $inputClass }}">
                        @error('email') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="address" class="text-sm font-bold text-gray-700">Alamat Pengiriman</label>
                        <textarea id="address" name="address" rows="4" required placeholder="Jalan, nomor, kecamatan, kota, kode pos" class="{{ $inputClass }}">{{ old('address', $user->address) }}</textarea>
                        @error('address') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </form>

            <aside class="h-fit rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                <h2 class="text-lg font-extrabold text-gray-900">Pesanan Anda</h2>
                <ul class="mt-4 divide-y divide-gray-100 text-sm">
                    @foreach ($items as $item)
                        <li class="flex justify-between gap-3 py-3">
                            <span class="text-gray-700">{{ $item->product->name }} <span class="text-gray-400">&times; {{ $item->quantity }}</span></span>
                            <span class="shrink-0 font-semibold text-gray-900">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <dl class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                    <dt class="text-sm text-gray-500">Total</dt>
                    <dd class="text-lg font-extrabold text-gray-900">Rp{{ number_format($total, 0, ',', '.') }}</dd>
                </dl>
                <button type="submit" form="checkout-form" class="mt-6 w-full rounded-full bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Buat Pesanan &amp; Bayar</button>
                <p class="mt-3 text-center text-xs text-gray-400">Pembayaran diproses melalui payment gateway.</p>
            </aside>
        </div>
    </div>
</x-public-layout>
