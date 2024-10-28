<?php

namespace Database\Seeders;

use App\Models\UserQuiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserQuiz::factory(20)->create();
    }
}
