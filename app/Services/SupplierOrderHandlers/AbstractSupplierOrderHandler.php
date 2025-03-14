<?php
namespace App\Services\SupplierOrderHandlers;

use App\Interfaces\SupplierOrderHandlerInterface;
use App\Models\Event;
use App\Models\SupplierOrder;

abstract class AbstractSupplierOrderHandler implements SupplierOrderHandlerInterface
{
    protected ?SupplierOrderHandlerInterface $nextHandler = null;

    public function setNext(SupplierOrderHandlerInterface $next): SupplierOrderHandlerInterface
    {
        $this->nextHandler = $next;
        return $next;
    }

    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        if ($this->nextHandler) {
            $this->nextHandler->handle($event, $order);
        }
    }
}
