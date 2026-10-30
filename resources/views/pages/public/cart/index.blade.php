<x-public-layout>
    <x-slot name="title">Keranjang</x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-2xl font-extrabold text-gray-900 sm:text-3xl">Keranjang Belanja</h1>

        @if ($items->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                <p class="font-bold text-gray-900">Keranjang Anda masih kosong</p>
                <p class="mt-1 text-sm text-gray-500">Yuk, pilih produk favorit Anda.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-600/25 hover:bg-red-700">Lihat Produk</a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
                <ul class="space-y-4">
                    @foreach ($items as $item)
                        <li class="flex gap-4 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-24 w-24 shrink-0 rounded-xl bg-gray-100 object-cover">
                            <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $item->product->name }}</p>
                                    <p class="mt-1 text-sm text-gray-500">Rp{{ number_format($item->product->price, 0, ',', '.') }} &middot; stok {{ $item->product->stock }}</p>
                                    <p class="mt-1 text-sm font-extrabold text-red-600">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="qty-{{ $item->id }}" class="sr-only">Jumlah {{ $item->product->name }}</label>
                                        <input id="qty-{{ $item->id }}" name="quantity" type="number" min="1" max="{{ min(99, $item->product->stock) }}" value="{{ $item->quantity }}"
                                            class="w-20 rounded-xl border-gray-200 bg-gray-50 text-center text-sm focus:border-red-500 focus:ring-red-500">
                                        <button type="submit" class="rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Perbarui</button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-gray-400 hover:bg-red-50 hover:text-red-600" aria-label="Hapus {{ $item->product->name }}" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <aside class="h-fit rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                    <h2 class="text-lg font-extrabold text-gray-900">Ringkasan</h2>
                    <dl class="mt-4 flex items-center justify-between text-sm">
                        <dt class="text-gray-500">Total ({{ $items->sum('quantity') }} produk)</dt>
                        <dd class="text-lg font-extrabold text-gray-900">Rp{{ number_format($total, 0, ',', '.') }}</dd>
                    </dl>
                    <a href="{{ route('checkout.index') }}" class="mt-6 block rounded-full bg-red-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Lanjut ke Checkout</a>
                    <a href="{{ route('products.index') }}" class="mt-3 block text-center text-sm font-semibold text-red-600 hover:text-red-700">Lanjut belanja</a>
                </aside>
            </div>
        @endif
    </div>
</x-public-layout>
