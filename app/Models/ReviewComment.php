<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewComment extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'user_id', 'review_id', 'description'];

    protected $table = 'review_comments';

    protected $primaryKey = 'id';

    protected $keyType = 'string';
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function commentLikes()
    {
        return $this->hasMany(ReviewCommentLike::class);
    }
}
