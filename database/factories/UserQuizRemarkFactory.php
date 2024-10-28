<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserQuiz;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserQuizRemark>
 */
class UserQuizRemarkFactory extends Factory
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
            'description' => $this->faker->text(200), 
            'quiz_id' => UserQuiz::factory(),
            'user_id' => User::factory(),
        ];
    }
}
