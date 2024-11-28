<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiConversation extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'title', 'user_id'];
    protected $table = 'ai_conversations';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ai_messages()
    {
        return $this->hasMany(AiMessage::class, 'conversation_id', 'id');
    }
}

