<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Laravolt\Indonesia\Models\Village;
use Throwable;

class ShippingService
{
    /**
     * Hitung ongkos kirim dari Makassar (asal default) ke desa/kelurahan tujuan melalui RajaOngkir.
     *
     * @return list<array{key: string, courier: string, courier_name: string, service: string, description: string, cost: int, etd: string}>
     *
     * @throws ValidationException
     */
    public function options(string $villageCode, string $postalCode, int $weight): array
    {
        try {
            $destinationId = $this->destinationId($villageCode, $postalCode);

            $response = $this->client()->asForm()->post('/calculate/domestic-cost', [
                'origin' => config('services.rajaongkir.origin_id'),
                'destination' => $destinationId,
                'weight' => max($weight, 1),
                'courier' => implode(':', config('services.rajaongkir.couriers')),
                'price' => 'lowest',
            ])->throw();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);

            throw ValidationException::withMessages(['shipping_option' => 'Ongkos kirim tidak dapat dihitung saat ini. Silakan coba lagi.']);
        }

        $options = collect($response->json('data', []))
            ->map(fn (array $row): array => [
                'key' => $row['code'].'|'.$row['service'],
                'courier' => $row['code'],
                'courier_name' => $row['name'],
                'service' => $row['service'],
                'description' => $row['description'] ?? '',
                'cost' => (int) $row['cost'],
                'etd' => (string) ($row['etd'] ?? ''),
            ])
            ->sortBy('cost')
            ->values()
            ->all();

        if ($options === []) {
            throw ValidationException::withMessages(['shipping_option' => 'Tidak ada layanan pengiriman ke alamat tersebut.']);
        }

        return $options;
    }

    /**
     * Cari opsi pengiriman yang dipilih pelanggan; biaya selalu dihitung ulang di server.
     *
     * @return array{key: string, courier: string, courier_name: string, service: string, description: string, cost: int, etd: string}
     *
     * @throws ValidationException
     */
    public function findOption(string $villageCode, string $postalCode, int $weight, string $optionKey): array
    {
        foreach ($this->options($villageCode, $postalCode, $weight) as $option) {
            if ($option['key'] === $optionKey) {
                return $option;
            }
        }

        throw ValidationException::withMessages(['shipping_option' => 'Layanan pengiriman yang dipilih tidak tersedia.']);
    }

    /**
     * Cari ID tujuan RajaOngkir berdasarkan kode pos, lalu dicocokkan dengan nama desa/kelurahan.
     */
    private function destinationId(string $villageCode, string $postalCode): int
    {
        $villageName = (string) Village::where('code', $villageCode)->value('name');

        $candidates = Cache::remember(
            "rajaongkir.destination.{$postalCode}",
            now()->addDay(),
            fn (): array => $this->client()->get('/destination/domestic-destination', ['search' => $postalCode, 'limit' => 50, 'offset' => 0])
                ->throw()
                ->json('data', []),
        );

        $candidates = collect($candidates)->where('zip_code', $postalCode);

        $match = $candidates->first(fn (array $row): bool => strcasecmp($row['subdistrict_name'], $villageName) === 0)
            ?? $candidates->first();

        if (! $match) {
            throw ValidationException::withMessages(['postal_code' => 'Kode pos tidak ditemukan untuk pengiriman.']);
        }

        return (int) $match['id'];
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(config('services.rajaongkir.base_url'))
            ->withHeaders(['key' => (string) config('services.rajaongkir.key')])
            ->acceptJson()
            ->timeout(15);
    }
}
