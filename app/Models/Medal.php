<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medal extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tier',
        'threshold',
        'discount',
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'medals';
    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_medals');

    }
}
