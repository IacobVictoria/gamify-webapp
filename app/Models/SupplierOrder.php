<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'supplier_id',
        'order_date',
        'status',
        'total_price',
        'email',
        'company_name',
        'first_name',
        'last_name',
        'address',
        'apartment',
        'state',
        'city',
        'country',
        'zip_code',
        'phone',
    ];

    protected $table = 'supplier_orders';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->belongsToMany(SupplierProduct::class, 'supplier_order_products', 'order_id', 'product_id')
        ->using(SupplierOrderProduct::class)
        ->withPivot('quantity', 'price');
    ;
    }

}
