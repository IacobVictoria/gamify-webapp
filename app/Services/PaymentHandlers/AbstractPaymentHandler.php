<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;

abstract class AbstractPaymentHandler implements PaymentHandlerInterface
{
    private ?PaymentHandlerInterface $nextHandler = null;

    public function setNext(PaymentHandlerInterface $handler): PaymentHandlerInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(ClientOrder $order, array $paymentData): void
    {
        if ($this->nextHandler) {
            $this->nextHandler->handle($order, $paymentData);
        }
    }
}
