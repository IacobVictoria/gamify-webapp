<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface UserQuizInterface
{
    public function retryQuiz(Request $request);
    public function lockQuiz(Request $request);
}
