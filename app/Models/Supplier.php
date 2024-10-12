<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
    ];
    protected $primaryKey = 'id';
    protected $keyType = 'string'; 
    public $incrementing = false; 
    protected $table = 'suppliers';

    public function orders()
    {
        return $this->hasMany(SupplierOrder::class, 'order_id');
    }

    public function supplierProducts()
    {
        return $this->hasMany(SupplierProduct::class, 'supplier_id', 'id');
    }
    

}
