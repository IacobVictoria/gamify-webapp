<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title','description','calendarId', 'period', 'start', 'end','status','report_category_ids'];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'meetings';
    public $timestamps = true;
    protected $casts = [
        'report_category_ids' => 'array'
    ];


    /**
     *  Un meeting poate avea mai multe rapoarte.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
