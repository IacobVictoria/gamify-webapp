<?php
namespace App\Services\SupplierOrderHandlers;

use App\Models\Event;
use App\Models\SupplierOrder;
use App\Models\SupplierProduct;
use App\Models\Product;
use App\Services\SupplierOrderNotificationService;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class UpdateSupplierStockHandler extends AbstractSupplierOrderHandler
{
    protected SupplierOrderNotificationService $notificationService;

    public function __construct(SupplierOrderNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(?Event $event = null, ?SupplierOrder $order = null)
    {
        if (!$event || !$order) {
            return;
        }

        $details = json_decode($event->details, true) ?? [];
        $productQuantities = $details['productQuantities'] ?? [];

        foreach ($productQuantities as $productData) {
            $supplierProduct = SupplierProduct::find($productData['productId']);

            if (!$supplierProduct) {
                Log::error("Produsul cu ID {$productData['productId']} nu a fost găsit la furnizor.");
                continue;
            }

            // Verificăm dacă produsul este în stoc înainte de a scădea cantitatea
            $wasOutOfStock = $supplierProduct->stock === 0;

            // Scădem stocul furnizorului
            $supplierProduct->stock -= $productData['quantity'];
            $supplierProduct->save();

            // Căutăm produsul în baza noastră
            $product = Product::where('name', $supplierProduct->name)
                ->where('category', $supplierProduct->category)
                ->first();

            if ($product) {
                //Actualizăm stocul local
                $product->stock += $productData['quantity'];
                $product->save();

                // Notificăm utilizatorii dacă produsul era epuizat
                if ($wasOutOfStock && $product->stock > 0) {
                    $this->notificationService->notifyUserForRestockedProductWishlist($product);
                }

            } else {
                // Dacă produsul nu există, îl creăm
                $newProduct = Product::create([
                    'id' => Uuid::uuid(),
                    'name' => $supplierProduct->name,
                    'price' => $supplierProduct->price,
                    'category' => $supplierProduct->category,
                    'description' => $supplierProduct->description,
                    'stock' => $productData['quantity'],
                    'score' => $supplierProduct->score,
                    'calories' => $supplierProduct->calories,
                    'protein' => $supplierProduct->protein,
                    'carbs' => $supplierProduct->carbs,
                    'fats' => $supplierProduct->fats,
                    'fiber' => $supplierProduct->fiber,
                    'sugar' => $supplierProduct->sugar,
                    'ingredients' => $supplierProduct->ingredients,
                    'allergens' => $supplierProduct->allergens
                ]);

                // Notificăm utilizatorii despre noul produs
                $this->notificationService->notifyUserForNewProduct($newProduct);
            }
        }


        $this->nextHandler?->handle($event, $order);
    }
}
