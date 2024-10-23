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
        'category' => $this->faker->randomElement(array_column(\App\Enums\ProductCategory::cases(), 'value')),
        'description' => $this->faker->sentence(),
        'score' => $this->faker->numberBetween(1, 100),
        'price' => $this->faker->randomFloat(2, 1, 1000),
        'stock' => $this->faker->numberBetween(1, 100),
        'calories' => $this->faker->numberBetween(50, 800),
        'protein' => $this->faker->randomFloat(2, 0, 50),
        'carbs' => $this->faker->randomFloat(2, 0, 100),
        'fats' => $this->faker->randomFloat(2, 0, 50),
        'fiber' => $this->faker->randomFloat(2, 0, 30),
        'sugar' => $this->faker->randomFloat(2, 0, 50),
        'ingredients' => $this->faker->words(5, true),
        'allergens' => $this->faker->optional()->words(3, true),
        'supplier_id' => Supplier::inRandomOrder()->first()->id,
        ];
    }
}
