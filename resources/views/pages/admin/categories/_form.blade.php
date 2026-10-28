@php
    $inputClass = 'mt-1 w-full rounded-xl border-gray-200 bg-gray-50 text-sm placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-red-500';
@endphp

<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="name" class="text-sm font-bold text-gray-700">Nama Kategori</label>
        <input id="name" name="name" type="text" value="{{ old('name', $category?->name) }}" required placeholder="Contoh: Smartphone" class="{{ $inputClass }}">
        @error('name') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="sm:col-span-2">
        <label for="description" class="text-sm font-bold text-gray-700">Deskripsi <span class="font-medium text-gray-400">(opsional)</span></label>
        <textarea id="description" name="description" rows="3" class="{{ $inputClass }}">{{ old('description', $category?->description) }}</textarea>
        @error('description') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('admin.categories.index') }}" class="rounded-full border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
    <button type="submit" class="rounded-full bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-red-600/25 transition hover:bg-red-700">Simpan</button>
</div>
