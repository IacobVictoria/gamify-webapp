<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'question_id',
        'text',
        'is_promoter',
    ];


    protected $table = 'survey_choices';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }
}
