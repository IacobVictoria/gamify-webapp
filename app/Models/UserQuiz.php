<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserQuiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'description',
        'difficulty',
        'slug',
        'is_published',
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($quiz) {
            if (empty($quiz->slug)) {
                $quiz->slug = Str::slug($quiz->title, '-');
            }
        });

        static::updating(function ($quiz) {
            if ($quiz->isDirty('title')) {
                $quiz->slug = Str::slug($quiz->title, '-');
            }
        });
    }
    protected $casts = [
        'is_published' => 'boolean',
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
