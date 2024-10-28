<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'question_id',
        'is_correct',
        'answer',

    ];
    protected $table = 'user_quiz_answers';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;
    public function question()
    {
        return $this->belongsTo(UserQuizQuestion::class);
    }
}
