<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'question_id',
        'choice_id',
        'scale',
        'answer',
    ];
  
    protected $table = 'survey_results';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function choice()
    {
        return $this->belongsTo(SurveyChoice::class);
    }
}
