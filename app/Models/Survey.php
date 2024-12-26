<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'description',
        'is_published',
    ];

    protected $table = 'surveys';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;


    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class);
    }
}
