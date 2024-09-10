<?php

namespace Database\Factories;

use App\Models\ClientOrder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProduct>
 */
class OrderProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Uuid::uuid(),
            'order_id' => ClientOrder::inRandomOrder()->first()->id, 
            'product_id' => Product::inRandomOrder()->first()->id, 
            'quantity' => $this->faker->numberBetween(1, 100), 
            'price' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
