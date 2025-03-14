<?php

namespace App\Interfaces;

use App\Models\Event;
use App\Models\SupplierOrder;

interface SupplierOrderHandlerInterface
{
    public function setNext(SupplierOrderHandlerInterface $next): SupplierOrderHandlerInterface;
    public function handle(?Event $event = null, ?SupplierOrder $order = null);
}