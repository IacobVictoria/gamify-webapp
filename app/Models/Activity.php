<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'type', // diet, article, tip
        'description',
        'details',
        'is_published',
        'score'
    ];

    protected $casts = [
        'details' => 'array',
        'is_published' => 'boolean',
    ];

    protected $table = 'activities';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
