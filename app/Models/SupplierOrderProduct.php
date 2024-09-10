<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SupplierOrderProduct extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $table = 'supplier_order_products';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function supplierOrder()
    {
        return $this->belongsTo(SupplierOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }
}
