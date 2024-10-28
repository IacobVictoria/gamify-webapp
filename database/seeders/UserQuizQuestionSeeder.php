<?php

namespace Database\Seeders;

use App\Models\UserQuizQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserQuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserQuizQuestion::factory(20)->create();
    }
}
