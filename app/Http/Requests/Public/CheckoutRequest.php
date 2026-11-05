<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

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
            'province_code' => ['required', 'string', Rule::exists(Province::class, 'code')],
            'city_code' => ['required', 'string', Rule::exists(City::class, 'code')->where('province_code', $this->input('province_code'))],
            'district_code' => ['required', 'string', Rule::exists(District::class, 'code')->where('city_code', $this->input('city_code'))],
            'village_code' => ['required', 'string', Rule::exists(Village::class, 'code')->where('district_code', $this->input('district_code'))],
            'postal_code' => ['required', 'digits:5'],
            'shipping_option' => ['required', 'string', 'regex:/^[a-z]+\|[A-Za-z0-9 ]+$/'],
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
            'province_code' => 'provinsi',
            'city_code' => 'kabupaten/kota',
            'district_code' => 'kecamatan',
            'village_code' => 'desa/kelurahan',
            'postal_code' => 'kode pos',
            'shipping_option' => 'layanan pengiriman',
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
