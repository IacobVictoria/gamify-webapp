<?php
namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\SupplierOrder;
use Carbon\Carbon;

class FetchSupplierOrdersHandler extends AbstractSupplierOrderHandler
{
    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        $today = Carbon::today();
        $events = Event::where('type', 'supplier_order')
            ->where('status', 'OPEN')
            ->whereDate('start', $today)
            ->get();

        if ($events->isEmpty()) {
            return;
        }
        
        foreach ($events as $event) {
            $this->nextHandler?->handle($event);
        }
    }
}
