<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'category',
        'description',
        'supplier_id',
        'price',
        'stock',
        'calories',
        'protein',
        'carbs',
        'fats',
        'fiber',
        'sugar',
        'ingredients',
        'allergens',
        'product_sku'
    ];

    protected $table = 'supplier_products';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;


    public function orders()
    {
        return $this->belongsToMany(SupplierOrder::class, 'supplier_order_products');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_sku', 'product_sku');
    }

}
