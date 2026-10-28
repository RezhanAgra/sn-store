<?php

namespace App\Http\Requests\Admin;

class UpdateProductRequest extends StoreProductRequest
{
    /**
     * Foto bersifat opsional saat memperbarui produk.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
