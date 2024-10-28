<?php

namespace Database\Factories;

use App\Enums\UserQuizDifficulty;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserQuiz>
 */
class UserQuizFactory extends Factory
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
            'title' => $this->faker->sentence(3),
            'difficulty' => $this->faker->randomElement(UserQuizDifficulty::cases())->value,
        ];
    }
}
