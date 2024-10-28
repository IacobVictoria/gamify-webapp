<?php

namespace Database\Factories;

use App\Models\UserQuizQuestion;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserQuizAnswer>
 */
class UserQuizAnswerFactory extends Factory
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
            'question_id' => UserQuizQuestion::inRandomOrder()->first()->id,
            'answer' => $this->faker->sentence(5),
            'is_correct' => $this->faker->boolean(),
        ];
    }
}
