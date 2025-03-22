<?php

namespace App\Models;

use App\Enums\RatingTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_id',
        'user_id',
        'title',
        'rating',
        'description',
        'likes',
    ];

    protected $table = 'reviews';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getRatingTitleAttribute()
    {
        return match ($this->rating) {
            $this->rating === 0 => RatingTitle::ZERO,
            $this->rating === 0.5 => RatingTitle::HALF,
            $this->rating === 1 => RatingTitle::ONE,
            $this->rating === 1.5 => RatingTitle::ONE_HALF,
            $this->rating === 2 => RatingTitle::TWO,
            $this->rating === 2.5 => RatingTitle::TWO_HALF,
            $this->rating === 3 => RatingTitle::THREE,
            $this->rating === 3.5 => RatingTitle::THREE_HALF,
            $this->rating === 4 => RatingTitle::FOUR,
            $this->rating === 4.5 => RatingTitle::FOUR_HALF,
            $this->rating === 5 => RatingTitle::FIVE,
            default => null,
        };
    }

    public function reviewLikes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    public function reviewComments()
    {
        return $this->hasMany(ReviewComment::class);
    }

    public function reviewMedia()
    {
        return $this->hasMany(ReviewMedia::class);
    }
}
