<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = ['user_id', 'code', 'name', 'phone', 'email', 'address', 'province', 'city', 'district', 'village', 'postal_code', 'shipping_courier', 'shipping_service', 'shipping_cost', 'total', 'status', 'payment_url'];

    /**
     * Alamat lengkap: jalan, desa, kecamatan, kabupaten/kota, provinsi, dan kode pos.
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::get(fn (): string => collect([
            $this->address,
            $this->village,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code,
        ])->filter()->implode(', '));
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['status' => OrderStatus::class];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
