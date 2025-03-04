<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'category',
        'description',
        'price',
        'stock',
        'score',
        'calories',
        'protein',
        'carbs',
        'fats',
        'fiber',
        'sugar',
        'ingredients',
        'allergens',
        'old_price'
    ];

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function orders()
    {
        return $this->belongsToMany(ClientOrder::class, 'order_products', 'product_id', 'order_id')
            ->using(OrderProduct::class);
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class);
    }

    public function qrCodesScan()
    {
        return $this->has(QrCodeScan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

}
