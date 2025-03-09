<?php
namespace App\Services\OrderHandlers;

use App\Models\ClientOrder;

abstract class AbstractOrderHandler implements OrderHandlerInterface
{
    private ?OrderHandlerInterface $nextHandler = null;

    public function setNext(OrderHandlerInterface $handler): OrderHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(ClientOrder $order, array $validatedData): void
    {
        if ($this->nextHandler) {
            $this->nextHandler->handle($order, $validatedData);
        }
    }
}
