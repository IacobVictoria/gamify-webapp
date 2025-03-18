<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];

    protected $table = 'report_categories';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;
}
