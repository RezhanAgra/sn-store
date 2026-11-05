<x-public-layout>
    <x-slot name="title">Checkout</x-slot>

    @php
        $inputClass = 'mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500';
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('cart.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">&larr; Kembali ke keranjang</a>
        <h1 class="mb-8 mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Checkout</h1>

        <div
            class="grid gap-8 lg:grid-cols-[1fr_22rem]"
            x-data="regionSelect({
                province: @js(old('province_code', '')),
                city: @js(old('city_code', '')),
                district: @js(old('district_code', '')),
                village: @js(old('village_code', '')),
                postalCode: @js(old('postal_code', '')),
                subtotal: @js($total),
                urls: {
                    cities: @js(route('regions.cities', '__code__')),
                    districts: @js(route('regions.districts', '__code__')),
                    villages: @js(route('regions.villages', '__code__')),
                    shipping: @js(route('checkout.shipping-cost')),
                },
                csrf: @js(csrf_token()),
            })"
        >
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
                    <div class="grid gap-6 sm:col-span-2 sm:grid-cols-2">
                        <div>
                            <label for="province_code" class="text-sm font-bold text-gray-700">Provinsi</label>
                            <select id="province_code" name="province_code" x-model="province" x-on:change="loadCities()" required class="{{ $inputClass }}">
                                <option value="">Pilih provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->code }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                            @error('province_code') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="city_code" class="text-sm font-bold text-gray-700">Kabupaten/Kota</label>
                            <select id="city_code" name="city_code" x-model="city" x-on:change="loadDistricts()" x-bind:disabled="!province" required class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="">Pilih kabupaten/kota</option>
                                <template x-for="option in cities" x-bind:key="option.code">
                                    <option x-bind:value="option.code" x-text="option.name" x-bind:selected="option.code === city"></option>
                                </template>
                            </select>
                            @error('city_code') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="district_code" class="text-sm font-bold text-gray-700">Kecamatan</label>
                            <select id="district_code" name="district_code" x-model="district" x-on:change="loadVillages()" x-bind:disabled="!province || !city" required class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="">Pilih kecamatan</option>
                                <template x-for="option in districts" x-bind:key="option.code">
                                    <option x-bind:value="option.code" x-text="option.name" x-bind:selected="option.code === district"></option>
                                </template>
                            </select>
                            @error('district_code') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="village_code" class="text-sm font-bold text-gray-700">Desa/Kelurahan</label>
                            <select id="village_code" name="village_code" x-model="village" x-on:change="loadShipping()" x-bind:disabled="!province || !city || !district" required class="{{ $inputClass }} disabled:cursor-not-allowed disabled:opacity-60">
                                <option value="">Pilih desa/kelurahan</option>
                                <template x-for="option in villages" x-bind:key="option.code">
                                    <option x-bind:value="option.code" x-text="option.name" x-bind:selected="option.code === village"></option>
                                </template>
                            </select>
                            @error('village_code') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="postal_code" class="text-sm font-bold text-gray-700">Kode Pos</label>
                        <input id="postal_code" name="postal_code" type="text" inputmode="numeric" maxlength="5" x-model="postalCode" x-on:input="loadShipping()" required placeholder="12345" class="{{ $inputClass }}">
                        @error('postal_code') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-sm font-bold text-gray-700">Pengiriman (dari Makassar)</span>
                        <p x-show="!village || postalCode.length !== 5" class="mt-1 text-sm text-gray-400">Lengkapi wilayah dan kode pos untuk melihat ongkos kirim.</p>
                        <p x-show="loadingShipping" x-cloak class="mt-1 text-sm text-gray-500">Menghitung ongkos kirim...</p>
                        <p x-show="shippingError" x-cloak x-text="shippingError" class="mt-1 text-sm font-medium text-red-600"></p>
                        <div class="mt-2 space-y-2">
                            <template x-for="option in shippingOptions" x-bind:key="option.key">
                                <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border border-gray-200 p-3 text-sm has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="shipping_option" x-bind:value="option.key" x-model="shippingOption" class="text-red-600 focus:ring-red-500">
                                        <span>
                                            <span class="font-semibold text-gray-900" x-text="option.courier_name + ' ' + option.service"></span>
                                            <span class="block text-xs text-gray-500" x-text="option.description + (option.etd ? ' · ' + option.etd : '')"></span>
                                        </span>
                                    </span>
                                    <span class="font-semibold text-gray-900" x-text="formatRupiah(option.cost)"></span>
                                </label>
                            </template>
                        </div>
                        @error('shipping_option') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="address" class="text-sm font-bold text-gray-700">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="4" required placeholder="Nama jalan, nomor rumah, RT/RW, patokan" class="{{ $inputClass }}">{{ old('address', $user->address) }}</textarea>
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
                <dl class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4 text-sm">
                    <dt class="text-gray-500">Subtotal</dt>
                    <dd class="font-semibold text-gray-900" x-text="formatRupiah(subtotal)">Rp{{ number_format($total, 0, ',', '.') }}</dd>
                </dl>
                <dl class="mt-2 flex items-center justify-between text-sm">
                    <dt class="text-gray-500">Ongkos kirim</dt>
                    <dd class="font-semibold text-gray-900" x-text="selectedShipping ? formatRupiah(selectedShipping.cost) : '-'">-</dd>
                </dl>
                <dl class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                    <dt class="text-sm text-gray-500">Total</dt>
                    <dd class="text-lg font-extrabold text-gray-900" x-text="formatRupiah(grandTotal)">Rp{{ number_format($total, 0, ',', '.') }}</dd>
                </dl>
                <button type="submit" form="checkout-form" x-bind:disabled="!selectedShipping" class="mt-6 disabled:cursor-not-allowed disabled:opacity-60 w-full rounded-full bg-red-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Buat Pesanan &amp; Bayar</button>
                <p class="mt-3 text-center text-xs text-gray-400">Pembayaran diproses melalui payment gateway.</p>
            </aside>
        </div>
    </div>
</x-public-layout>
