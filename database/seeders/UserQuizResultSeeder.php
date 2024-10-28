<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserQuiz;
use App\Models\UserQuizResult;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserQuizResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $quizzes = UserQuiz::all();

        foreach ($users as $user) {
            foreach ($quizzes as $quiz) {
                for ($attempt = 1; $attempt <= 3; $attempt++) {
                    $exists = UserQuizResult::where([
                        'user_id' => $user->id,
                        'quiz_id' => $quiz->id,
                        'attempt_number' => $attempt,
                    ])->exists();

                    if (!$exists) {
                        UserQuizResult::factory()->create([
                            'user_id' => $user->id,
                            'quiz_id' => $quiz->id,
                            'attempt_number' => $attempt,
                        ]);
                    }
                }
            }
        }
    }
}
