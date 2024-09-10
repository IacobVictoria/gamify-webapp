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
    ];

    protected $table = 'supplier_products';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;


    public function supplierOrders()
    {
        return $this->belongsToMany(SupplierOrder::class)->using(SupplierOrderProduct::class);
    }

}
