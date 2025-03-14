<?php
namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\SupplierOrder;
use Illuminate\Support\Facades\Log;

class CloseSupplierEventHandler extends AbstractSupplierOrderHandler
{
    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        if (!$event) {
            return;
        }

        $event->status = 'CLOSED';
        $event->save();

        $this->nextHandler?->handle($event, $order);
    }
}
