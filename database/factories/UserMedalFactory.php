<?php

namespace Database\Factories;

use App\Models\Medal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserMedal>
 */
class UserMedalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       
        return [
            'user_id' => User::inRandomOrder()->first()->id,  
            'medal_id' => Medal::inRandomOrder()->first()->id, 
        ];
    }
}
