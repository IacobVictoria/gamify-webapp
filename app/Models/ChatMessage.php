<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'sender_id',
        'receiver_id',
        'content',
        'is_read',
        'sent_at',
        'read_at',
        'message_type',
        'attachment_url',
        'reply_to_message_id'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;
    protected $table = 'chat_messages';

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // mesajele la care se răspunde
    public function replyToMessage()
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_message_id');
    }

    // un mesaj poate avea multe răspunsuri
    public function replies()
    {
        return $this->hasMany(ChatMessage::class, 'reply_to_message_id');
    }


    public function setSentAtAttribute($value)
    {
        $this->attributes['sent_at'] = $value ? \Carbon\Carbon::parse($value) : null;
    }


    public function setReadAtAttribute($value)
    {
        $this->attributes['read_at'] = $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function getAttachmentUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
