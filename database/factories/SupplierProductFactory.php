<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierProduct>
 */
class SupplierProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->word(),
            'category' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'stock' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'score' => $this->faker->numberBetween(1, 100),
        ];
    }
}
