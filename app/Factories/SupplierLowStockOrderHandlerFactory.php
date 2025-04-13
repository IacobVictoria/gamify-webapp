<?php
namespace App\Factories;

use App\Services\NotificationService;
use App\Services\Reports\SupplierInvoiceReportService;
use App\Services\SupplierLowStockOrderHandlers\CreateSupplierLowStockOrderHandler;
use App\Services\SupplierLowStockOrderHandlers\CreateSupplierLowStockOrderProductsHandler;
use App\Services\SupplierLowStockOrderHandlers\FetchLowStockProductsHandler;
use App\Services\SupplierLowStockOrderHandlers\GenerateSupplierLowStockInvoiceHandler;
use App\Services\SupplierLowStockOrderHandlers\RegisterLowStockInventoryTransactionHandler;
use App\Services\SupplierLowStockOrderHandlers\UpdateLowStockHandler;
use App\Services\SupplierOrderNotificationService;

class SupplierLowStockOrderHandlerFactory
{
    public static function create($app)
    {
        $fetchOrders = new FetchLowStockProductsHandler($app->make(SupplierOrderNotificationService::class));
        $createOrder = new CreateSupplierLowStockOrderHandler($app->make(SupplierOrderNotificationService::class), $app->make(NotificationService::class));
        $createProductsSupplierOrder = new CreateSupplierLowStockOrderProductsHandler();
        $generateInvoice = new GenerateSupplierLowStockInvoiceHandler($app->make(SupplierInvoiceReportService::class));
        $updateStock = new UpdateLowStockHandler();
        $registerTransactionHandler = new RegisterLowStockInventoryTransactionHandler(); 


        // Construim lanțul de procesare
        $fetchOrders->setNext($createOrder)
            ->setNext($createProductsSupplierOrder)
            ->setNext($updateStock)
            ->setNext($registerTransactionHandler)
            ->setNext($generateInvoice);

        return $fetchOrders;
    }
}
