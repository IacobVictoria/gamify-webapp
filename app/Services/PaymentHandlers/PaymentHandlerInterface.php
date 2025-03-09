<?php
namespace App\Services\PaymentHandlers;

use App\Models\ClientOrder;

interface PaymentHandlerInterface
{
    public function setNext(PaymentHandlerInterface $handler): PaymentHandlerInterface;
    public function handle(ClientOrder $order, array $paymentData): void;
}
