<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserMedal extends Pivot
{
    use HasFactory;

    protected $fillable = ['user_id', 'medal_id'];

    protected $table = 'user_medals';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function medal()
    {
        return $this->belongsTo(Medal::class, 'medal_id');
    }
}
