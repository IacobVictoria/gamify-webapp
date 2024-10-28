<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserQuiz;
use App\Models\UserQuizRemark;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserQuizRemarkSeeder extends Seeder
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
           
                $exists = UserQuizRemark::where([
                    'user_id' => $user->id,
                    'quiz_id' => $quiz->id,
                ])->exists();

          
                if (!$exists) {
                    UserQuizRemark::factory()->create([
                        'user_id' => $user->id,
                        'quiz_id' => $quiz->id,
                    ]);
                }
            }
        }
    }
}
