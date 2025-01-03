<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' =>  Uuid::uuid(),
            'name' => $this->faker->word,
            'category' => $this->faker->word,
            'description' => $this->faker->optional()->paragraph,
            'stock' => $this->faker->numberBetween(1, 100), 
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'score' => $this->faker->numberBetween(1, 100),
        ];
    }
}
