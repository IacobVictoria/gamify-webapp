<?php
namespace App\Services;

use App\Events\SupplierOrderErrorEvent;
use App\Events\SupplierOrderSuccessEvent;
use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Report;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderProduct;
use App\Models\SupplierProduct;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class OrderProcessingService
{
    

}