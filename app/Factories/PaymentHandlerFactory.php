<?php
namespace App\Factories;

use App\Services\PaymentHandlers\AuthorizedPaymentHandler;
use App\Services\PaymentHandlers\GenerateInvoiceHandler;
use App\Services\PaymentHandlers\ExpediteOrderHandler;
use App\Services\PaymentHandlers\ApplyBadgeHandler;
use App\Services\PaymentHandlers\ApplyDiscountHandler;
use App\Services\Badges\ShoppingBadgeService;
use App\Services\DiscountService;
use App\Services\PaymentHandlers\UpdateStockHandler;

class PaymentHandlerFactory
{
    public static function create($app)
    {
        $authorizePayment = new AuthorizedPaymentHandler();
        $updateStock = new UpdateStockHandler();
        $generateInvoice = new GenerateInvoiceHandler();
        $expediteOrder = new ExpediteOrderHandler();
        $applyBadges = new ApplyBadgeHandler($app->make(ShoppingBadgeService::class));
        $applyDiscount = new ApplyDiscountHandler($app->make(DiscountService::class));

        // Construim lanțul de handler-e
        $authorizePayment->setNext($updateStock)
            ->setNext($applyDiscount)
            ->setNext($generateInvoice)
            ->setNext($expediteOrder)
            ->setNext($applyBadges);


        return $authorizePayment;
    }
}
