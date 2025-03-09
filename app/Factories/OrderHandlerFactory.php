<?php
namespace App\Factories;

use App\Services\OrderHandlers\CreateOrderHandler;
use App\Services\OrderHandlers\UpdateStockHandler;
use App\Services\OrderHandlers\PlaceOrderHandler;

class OrderHandlerFactory
{
    public static function create()
    {
        $createOrder = new CreateOrderHandler();
        $updateStock = new UpdateStockHandler();
        $placeOrder = new PlaceOrderHandler();

        // Construim lanțul de handler-e
        $createOrder->setNext($updateStock)->setNext($placeOrder);

        return $createOrder;
    }
}
