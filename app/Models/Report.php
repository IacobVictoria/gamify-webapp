<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'report_category_id', 'title', 's3_path', 'meeting_id'];
    protected $table = 'reports';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'report_category_id');
    }

}
