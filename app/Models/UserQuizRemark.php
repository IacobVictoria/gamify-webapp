<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizRemark extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'description',
        'quiz_id',
        'user_id'
    ];
    protected $table = 'user_quiz_remarks';

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


}
