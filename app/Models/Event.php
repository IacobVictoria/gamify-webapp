<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;
    protected $table = 'events';

    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'start',
        'end',
        'type',
        'details',
        'calendarId'
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
