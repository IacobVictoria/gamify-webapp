<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewMedia extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'review_id', 'filename', 'type', 'url'];
    protected $table = 'review_media';

    protected $primaryKey = 'id';

    protected $keyType = 'string';
    public $incrementing = false;

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
