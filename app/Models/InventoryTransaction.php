<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'transaction_type',
        'supplier_order_id',
        'product_id',
        'client_order_id',
        'transaction_date',
        'quantity',
        'description'
    ];

    protected $table = 'inventory_transactions';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function supplierOrder()
    {
        return $this->belongsTo(SupplierOrder::class, 'supplier_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function clientOrder()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id');
    }

}
