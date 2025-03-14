<?php
namespace App\Factories;

use App\Services\SupplierLowStockOrderHandlers\CreateSupplierLowStockOrderHandler;
use App\Services\SupplierLowStockOrderHandlers\CreateSupplierLowStockOrderProductsHandler;
use App\Services\SupplierLowStockOrderHandlers\FetchLowStockProductsHandler;
use App\Services\SupplierLowStockOrderHandlers\GenerateSupplierLowStockInvoiceHandler;
use App\Services\SupplierLowStockOrderHandlers\UpdateLowStockHandler;
use App\Services\SupplierOrderNotificationService;

class SupplierLowStockOrderHandlerFactory
{
    public static function create($app)
    {
        // Creăm instanțele pentru fiecare handler
        $fetchOrders = new FetchLowStockProductsHandler($app->make(SupplierOrderNotificationService::class));
        $createOrder = new CreateSupplierLowStockOrderHandler($app->make(SupplierOrderNotificationService::class));
        $createProductsSupplierOrder = new CreateSupplierLowStockOrderProductsHandler();
        $generateInvoice = new GenerateSupplierLowStockInvoiceHandler();
        $updateStock = new UpdateLowStockHandler();

        // Construim lanțul de procesare
        $fetchOrders->setNext($createOrder)
            ->setNext($createProductsSupplierOrder)
            ->setNext($updateStock)
            ->setNext($generateInvoice);

        return $fetchOrders;
    }
}
