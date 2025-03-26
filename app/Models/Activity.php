<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'score',
        'slug'
    ];

    protected $casts = [
        'details' => 'array',
        'is_published' => 'boolean',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            $activity->slug = Str::slug($activity->title);
        });

        static::updating(function ($activity) {
            if ($activity->isDirty('title')) {
                $activity->slug = Str::slug($activity->title);
            }
        });
    }
    
    protected $table = 'activities';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
