<?php
namespace App\Factories;

use App\Services\OrderHandlers\CreateOrderHandler;
use App\Services\OrderHandlers\CreateProductsOrderHandler;
use App\Services\OrderHandlers\UpdateStockHandler;
use App\Services\OrderHandlers\PlaceOrderHandler;

class OrderHandlerFactory
{
    public static function create()
    {
        $createOrder = new CreateOrderHandler();
        $createProductsOrder = new CreateProductsOrderHandler();
        $placeOrder = new PlaceOrderHandler();


        // Construim lanțul de handler-e
        $createOrder->setNext($createProductsOrder)->setNext($placeOrder);

        return $createOrder;
    }
}
