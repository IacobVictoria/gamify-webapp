<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'survey_id',
        'responses',
    ];
  
    protected $table = 'survey_results';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;
}
