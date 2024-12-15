<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['id','type', 'title', 's3_path'];
    protected $table = 'reports';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

}
