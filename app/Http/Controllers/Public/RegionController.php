<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class RegionController extends Controller
{
    /**
     * Daftar kabupaten/kota dalam sebuah provinsi.
     */
    public function cities(string $province): JsonResponse
    {
        return $this->options(City::where('province_code', $province));
    }

    /**
     * Daftar kecamatan dalam sebuah kabupaten/kota.
     */
    public function districts(string $city): JsonResponse
    {
        return $this->options(District::where('city_code', $city));
    }

    /**
     * Daftar desa/kelurahan dalam sebuah kecamatan.
     */
    public function villages(string $district): JsonResponse
    {
        return $this->options(Village::where('district_code', $district));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<*>  $query
     */
    private function options($query): JsonResponse
    {
        return response()->json($query->orderBy('name')->get(['code', 'name']));
    }
}
