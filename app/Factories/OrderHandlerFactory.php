<?php
namespace App\Factories;

use App\Services\OrderHandlers\CreateOrderHandler;
use App\Services\OrderHandlers\CreateProductsOrderHandler;
use App\Services\OrderHandlers\GenerateProductsQrCodesHandler;
use App\Services\OrderHandlers\PlaceOrderHandler;
use App\Services\QrCodes\QrCodeService;

class OrderHandlerFactory
{
    public static function create($app)
    {
        $createOrder = new CreateOrderHandler();
        $createProductsOrder = new CreateProductsOrderHandler();
        $placeOrder = new PlaceOrderHandler();
        $generateQrCodes = new GenerateProductsQrCodesHandler($app->make(QrCodeService::class));


        // Construim lanțul de handler-e
        $createOrder->setNext($createProductsOrder)->setNext($placeOrder)->setNext($generateQrCodes);

        return $createOrder;
    }
}
