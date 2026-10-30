<x-admin-layout>
    <x-slot name="title">Pengguna</x-slot>

    <div class="space-y-6">
        <section class="overflow-hidden rounded-2xl bg-white shadow-xl shadow-gray-900/5">
            <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
                <h1 class="font-extrabold text-gray-900">Daftar Pengguna</h1>
                <p class="mt-1 text-xs text-gray-500">{{ $users->count() }} pengguna terdaftar</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Peran</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->roles->pluck('name')->map(fn (string $role): string => ucfirst($role))->join(', ') ?: '-' }}</td>
                                <td class="px-6 py-4"><x-admin.status-badge :status="$user->email_verified_at ? 'verified' : 'unverified'" /></td>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin-layout>
