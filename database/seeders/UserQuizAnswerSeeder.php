<?php

namespace Database\Seeders;

use App\Models\UserQuizAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserQuizAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserQuizAnswer::factory(20)->create();
    }
}
