<?php

namespace Database\Factories;

use App\Models\UserQuiz;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserQuizQuestion>
 */
class UserQuizQuestionFactory extends Factory
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
            'question' => $this->faker->sentence(10),
            'quiz_id' => UserQuiz::inRandomOrder()->first()->id,
            'score' => $this->faker->numberBetween(1, 10),
        ];
    }
}
