<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\Village;

class ShippingCostRequest extends FormRequest
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
            'village_code' => ['required', 'string', Rule::exists(Village::class, 'code')],
            'postal_code' => ['required', 'digits:5'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['village_code' => 'desa/kelurahan', 'postal_code' => 'kode pos'];
    }
}
