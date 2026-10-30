<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'code' => 'INV-'.fake()->unique()->numerify('######'),
            'name' => fake()->name(),
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->safeEmail(),
            'address' => fake()->address(),
            'total' => fake()->numberBetween(50, 5000) * 1000,
            'status' => OrderStatus::Pending,
        ];
    }
}
