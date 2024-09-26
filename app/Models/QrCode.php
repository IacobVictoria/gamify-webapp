<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'product_id',
        'image_url'
    ];

    protected $table = 'qr_codes';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
