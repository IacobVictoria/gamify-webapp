<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizLeaderboardHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'rank',
        'date',
        'points',
    ];

    protected $table = 'quiz_leaderboard_history';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
