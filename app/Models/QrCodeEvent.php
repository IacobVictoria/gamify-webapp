<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodeEvent extends Model
{
    use HasFactory;

    protected $table = 'qr_codes_events';
    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'qr_code',
        'image_url',
        'event_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
