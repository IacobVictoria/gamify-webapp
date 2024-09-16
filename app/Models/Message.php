<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message 
{
    use HasFactory;

    protected $fillable = [
        'name',
        'medal',
        'email',
        'birthdate',
        'message',
    ];

  
    public function getTheMessageAttribute()
    {
        return "{$this->message}";
    }

}
