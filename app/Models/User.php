<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\CityRomania;
use App\Enums\Gender;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'score',
        'location',
        'birthdate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'gender' => Gender::class,
            'location' => CityRomania::class
        ];
    }

    public function orders()
    {
        return $this->hasMany(ClientOrder::class, 'user_id');
    }

    public function medals()
    {
        return $this->belongsToMany(Medal::class, 'user_medals', 'user_id', 'medal_id')
            ->withPivot('created_at');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function permissiions()
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    // Un utilizator poate avea mai multe scanări de coduri QR.
    public function qrCodeScans()
    {
        return $this->hasMany(QrCodeScan::class);
    }

    public function reviewLikes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    public function hasLiked(Review $review)
    {
        return $this->reviewLikes()->where('review_id', $review->id)->exists();
    }

    public function like(Review $review)
    {
        if (!$this->hasLiked($review)) {

            $this->reviewLikes()->create([
                'id' => Uuid::uuid(),
                'review_id' => $review->id
            ]);
        }
    }

    public function unlike(Review $review)
    {
        if ($this->hasLiked($review)) {

            $this->reviewLikes()->where('review_id', $review->id)->delete();
        }
    }

}
