<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'phone' => 'nomor HP',
            'email' => 'email',
            'address' => 'alamat',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['phone.regex' => 'Format nomor HP tidak valid (contoh: 081234567890).'];
    }
}
