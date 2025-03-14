<?php
namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\SupplierOrder;

//Acest handler validează structura datelor evenimentului.
class ValidateEventHandler extends AbstractSupplierOrderHandler
{
    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        $details = json_decode($event->details, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($details['supplier']) || empty($details['productQuantities'])) {
            return; // Ieșim din lanț dacă datele sunt invalide
        }

        $this->nextHandler?->handle($event);
    }
}
