<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiMessage extends Model
{
    use HasFactory;

    protected $fillable = ['id','conversation_id', 'is_user', 'content'];
    protected $table = 'ai_messages';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function ai_conversation()
    {
        return $this->belongsTo(AiConversation::class, 'conversation_id', 'id');
    }
}
