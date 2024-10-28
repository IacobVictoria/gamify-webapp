<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'quiz_id',
        'date',
        'total_score',
        'attempt_number',
    ];

    protected $table = 'user_quiz_results';
    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(UserQuiz::class);
    }
    public function remarkForUserAndQuiz($userId, $quizId)
    {
        return UserQuizRemark::where('user_id', $userId)
            ->where('user_quiz_id', $quizId)
            ->first()->description;
    }

}
