<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'total_price',
        'status',
        'email',
        'first_name',
        'last_name',
        'address',
        'apartment',
        'state',
        'city',
        'country',
        'zip_code',
        'phone',
        'placed_at',
        'expedited_at',
        'delivered_at',
        'archived_at',
        'is_archived',
        'invoice_url',
        'promo_code',
        'discount_amount',
        'report_id'

    ];
    protected $table = 'client_orders';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')
            ->using(OrderProduct::class)
            ->withPivot('quantity', 'price');
        ;
    }



}
