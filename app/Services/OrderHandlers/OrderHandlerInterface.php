<?php

namespace App\Services\OrderHandlers;

use App\Models\ClientOrder;

interface OrderHandlerInterface
{
    public function setNext(OrderHandlerInterface $handler): OrderHandlerInterface;
    public function handle(ClientOrder $order, array $validatedData): void;
}
