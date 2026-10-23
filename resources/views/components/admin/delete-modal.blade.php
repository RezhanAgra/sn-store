@props(['name', 'title'])

{{-- Konfirmasi hapus (tampilan saja, belum terhubung ke database) --}}
<x-modal :name="$name" maxWidth="md">
    <div x-data="{ label: '' }" @set-delete-label.window="label = $event.detail" class="p-6">
        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
        </span>
        <h3 class="mt-4 text-lg font-extrabold text-gray-900">{{ $title }}</h3>
        <p class="mt-1 text-sm text-gray-500"><span class="font-bold text-gray-900" x-text="label"></span> akan dihapus dan tidak dapat dikembalikan.</p>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
            <button type="button" x-on:click="$dispatch('close')" class="rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-red-600/25 hover:bg-red-700">Ya, hapus</button>
        </div>
    </div>
</x-modal>
