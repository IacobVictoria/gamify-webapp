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
    ];

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function orders()
    {
        return $this->belongsToMany(ClientOrder::class)
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

}
