<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
