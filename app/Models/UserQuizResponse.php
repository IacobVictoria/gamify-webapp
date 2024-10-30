<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizResponse extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'user_id', 'quiz_id', 'question_id', 'answer_id', 'is_correct'];
    protected $table = 'user_quiz_responses';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(UserQuiz::class, 'quiz_id');
    }

    public function question()
    {
        return $this->belongsTo(UserQuizQuestion::class, 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo(UserQuizAnswer::class, 'answer_id');
    }
}
