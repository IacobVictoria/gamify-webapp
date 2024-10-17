<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewCommentLike extends Model
{
    use HasFactory;


    protected $fillable = ['id', 'user_id', 'review_comment_id'];
    protected $table = 'comment_likes';

    protected $primaryKey = 'id';

    protected $keyType = 'string';
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewComment()
    {
        return $this->belongsTo(ReviewComment::class);
    }
}
