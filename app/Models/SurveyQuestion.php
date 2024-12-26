<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'survey_id',
        'text',
        'type',
    ];

    protected $table = 'survey_questions';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function choices()
    {
        return $this->hasMany(SurveyChoice::class, 'question_id');
    }

    public function results()
    {
        return $this->hasMany(SurveyResult::class);
    }
}
