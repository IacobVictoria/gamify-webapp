<?php
namespace App\Factories;

use App\Services\PaymentHandlers\AuthorizedPaymentHandler;
use App\Services\PaymentHandlers\ClearCartHandler;
use App\Services\PaymentHandlers\GenerateInvoiceHandler;
use App\Services\PaymentHandlers\ExpediteOrderHandler;
use App\Services\PaymentHandlers\ApplyBadgeHandler;
use App\Services\PaymentHandlers\ApplyDiscountHandler;
use App\Services\Badges\ShoppingBadgeService;
use App\Services\DiscountService;
use App\Services\PaymentHandlers\InventoryTransactionHandler;
use App\Services\PaymentHandlers\UpdateStockHandler;
use App\Services\Reports\ClientInvoiceReportService;

class PaymentHandlerFactory
{
    public static function create($app)
    {
        $authorizePayment = new AuthorizedPaymentHandler();
        $updateStock = new UpdateStockHandler();
        $generateInvoice = new GenerateInvoiceHandler($app->make(ClientInvoiceReportService::class));
        $expediteOrder = new ExpediteOrderHandler();
        $applyBadges = new ApplyBadgeHandler($app->make(ShoppingBadgeService::class));
        $applyDiscount = new ApplyDiscountHandler($app->make(DiscountService::class));
        $inventory = new InventoryTransactionHandler();
        $clearCart = new ClearCartHandler();

        // Construim lanțul de handler-e
        $authorizePayment->setNext($updateStock)
            ->setNext($inventory)
            ->setNext($applyDiscount)
            ->setNext($generateInvoice)
            ->setNext($expediteOrder)
            ->setNext($applyBadges)
            ->setNext($clearCart);

        return $authorizePayment;
    }
}
