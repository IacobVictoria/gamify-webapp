<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'question',
        'quiz_id',
        'score'
    ];
    protected $table = 'user_quiz_questions';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function answers()
    {
        return $this->hasMany(UserQuizAnswer::class, 'question_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo(UserQuiz::class, 'quiz_id', 'id');
    }

}
