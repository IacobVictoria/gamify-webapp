<?php
namespace App\Factories;

use App\Services\Reports\SupplierInvoiceReportService;
use App\Services\SupplierOrderHandlers\CloseSupplierEventHandler;
use App\Services\SupplierOrderHandlers\CreateSupplierOrderHandler;
use App\Services\SupplierOrderHandlers\CreateSupplierOrderProductsHandler;
use App\Services\SupplierOrderHandlers\FetchSupplierOrdersHandler;
use App\Services\SupplierOrderHandlers\GenerateSupplierInvoiceHandler;
use App\Services\SupplierOrderHandlers\RegisterInventoryTransactionHandler;
use App\Services\SupplierOrderHandlers\UpdateSupplierStockHandler;
use App\Services\SupplierOrderHandlers\ValidateEventHandler;
use App\Services\SupplierOrderHandlers\ValidateSupplierStockHandler;
use App\Services\SupplierOrderNotificationService;

class SupplierOrderHandlerFactory
{
    public static function create($app)
    {
        $fetchOrders = new FetchSupplierOrdersHandler();
        $validateEvent = new ValidateEventHandler();
        $createOrder = new CreateSupplierOrderHandler($app->make(SupplierOrderNotificationService::class));
        $generateInvoice = new GenerateSupplierInvoiceHandler($app->make(SupplierInvoiceReportService::class));
        $validateStockSupplier = new ValidateSupplierStockHandler($app->make(SupplierOrderNotificationService::class));
        $createProductsSupplierOrder = new CreateSupplierOrderProductsHandler();
        $updateStock = new UpdateSupplierStockHandler($app->make(SupplierOrderNotificationService::class));
        $finalizeEvent = new CloseSupplierEventHandler();
        $registerInvetory= new RegisterInventoryTransactionHandler();

        $fetchOrders->setNext($validateEvent)
            ->setNext($finalizeEvent)
            ->setNext($createOrder)
            ->setNext($validateStockSupplier)
            ->setNext($createProductsSupplierOrder)
            ->setNext($updateStock)
            ->setNext($registerInvetory)
            ->setNext($generateInvoice);


        return $fetchOrders;
    }
}
