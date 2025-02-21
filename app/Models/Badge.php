<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'description', 'image_path'];
    protected $table = 'badges';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')->withPivot('awarded_at');
    }
}
