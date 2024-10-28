<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserQuiz;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserQuizResult>
 */
class UserQuizResultFactory extends Factory
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
            'user_id' => User::factory(), 
            'quiz_id' => UserQuiz::factory(), 
            'date' => $this->faker->dateTime(),
            'total_score' => $this->faker->numberBetween(0, 100),
            'attempt_number' => $this->faker->numberBetween(1, 3),
        ];
    }
}
