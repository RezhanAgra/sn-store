<x-guest-layout>
    <x-slot name="title">Masuk</x-slot>

    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Selamat datang kembali 👋</h1>
        <p class="mt-2 text-sm text-gray-500">Masuk ke akun Anda untuk melanjutkan belanja.</p>
    </div>

    <x-auth-session-status class="mt-6 rounded-xl bg-green-50 px-4 py-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5" x-data="{ showPassword: false }">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com"
                    @class([
                        'block w-full rounded-xl bg-gray-50 py-3 pl-12 pr-4 text-sm text-gray-900 placeholder:text-gray-400 transition focus:bg-white focus:ring-4',
                        'border-red-300 focus:border-red-500 focus:ring-red-100' => $errors->has('email'),
                        'border-gray-200 focus:border-red-500 focus:ring-red-100' => ! $errors->has('email'),
                    ])>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">Lupa password?</a>
                @endif
            </div>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                </span>
                <input id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                    :type="showPassword ? 'text' : 'password'" type="password"
                    @class([
                        'block w-full rounded-xl bg-gray-50 py-3 pl-12 pr-12 text-sm text-gray-900 placeholder:text-gray-400 transition focus:bg-white focus:ring-4',
                        'border-red-300 focus:border-red-500 focus:ring-red-100' => $errors->has('password'),
                        'border-gray-200 focus:border-red-500 focus:ring-red-100' => ! $errors->has('password'),
                    ])>
                <button type="button" @click="showPassword = ! showPassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 transition hover:text-red-600"
                    :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'" aria-label="Tampilkan password">
                    <svg x-show="! showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Ingat saya --}}
        <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
            <span class="text-sm text-gray-600">Ingat saya</span>
        </label>

        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-red-600/25 transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-200">
            Masuk
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
        </button>
    </form>

    @if (Route::has('register'))
        <p class="mt-8 text-center text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-red-600 hover:text-red-700">Daftar sekarang</a>
        </p>
    @endif
</x-guest-layout>
