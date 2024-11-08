<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable=[
        'id',
        'message',
        'type',
        'user_id',
        'data',
        'is_read',
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'notifications';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
