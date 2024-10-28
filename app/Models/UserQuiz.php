<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'difficulty'
    ];
    protected $table = 'user_quizzes';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function questions()
    {
        return $this->hasMany(UserQuizQuestion::class, 'quiz_id', 'id');
    }

    public function quizResults()
    {
        return $this->hasMany(UserQuizResult::class, 'quiz_id', 'id'); // asigură-te că există un foreign key în UserQuizResult
    }

    public function quizRemarks()
    {
        return $this->hasMany(UserQuizRemark::class, 'quiz_id', 'id'); // asigură-te că există un foreign key în UserQuizRemark
    }
}
