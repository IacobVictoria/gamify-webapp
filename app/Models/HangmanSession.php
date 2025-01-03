<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HangmanSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'creator_id',
        'opponent_id',
        'word_for_creator',
        'word_for_opponent',
        'hint_for_creator',
        'hint_for_opponent',
        'guessed_letters_creator',
        'guessed_letters_opponent',
        'mistakes_creator',
        'mistakes_opponent',
        'turn',
        'completed',
        'scores',
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;
    protected $table = 'hangman_sessions';
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function opponent()
    {
        return $this->belongsTo(User::class, 'opponent_id');
    }
}
