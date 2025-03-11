<?php

namespace App\Http\Controllers;

use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Http\Requests\AdminCheckoutRequest;
use App\Models\Product;
use App\Models\Report;
use App\Models\SupplierOrder;
use App\Models\SupplierOrderProduct;
use App\Models\SupplierProduct;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminCheckoutController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            $cookieCart = json_decode(request()->cookie('cart_' . auth()->id(), '[]'), true);
            if (!empty($cookieCart)) {
                session(['cart' => $cookieCart]);
                $cartItems = $cookieCart; //  cartItems =  produsele din cookie
            }
        }

        $cartItems = array_values($cartItems);

        return Inertia::render('Admin/Purchase/Checkout', [
            "cartItems" => $cartItems,
        ]);
    }

    public function store(AdminCheckoutRequest $request)
    {
        $validatedData = $request->validated();

        $order = SupplierOrder::create([
            'id' => Uuid::uuid(),
            'supplier_id' => $validatedData['supplierId'],
            'total_price' => $this->calculateTotal($validatedData['products']),
            'status' => 'pending',
            'company_name' => $validatedData['companyName'],
            'email' => $validatedData['email'],
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'address' => $validatedData['adress'],
            'apartment' => $validatedData['apartament'],
            'state' => $validatedData['state'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'zip_code' => $validatedData['code'],
            'phone' => $validatedData['phone'],
            'order_date' => now(),
        ]);

        foreach ($validatedData['products'] as $productData) {
            SupplierOrderProduct::create([
                'id' => Uuid::uuid(),
                'order_id' => $order->id,
                'product_id' => $productData['product']['id'],
                'quantity' => $productData['quantity'],
                'price' => $productData['product']['price'],

            ]);
            $supplierProduct = SupplierProduct::find($productData['product']['id']);

            if ($supplierProduct) {
                // Scădem stocul în tabela SupplierProduct
                $supplierProduct->stock = $supplierProduct->stock - $productData['quantity'];
                $supplierProduct->save();
            } else {
                throw new \Exception("Produsul cu ID {$productData['product']['id']} nu a fost găsit la furnizor.");
            }

            $product = Product::where('name', $supplierProduct->name)->first();

            if ($product) {
                $product->stock = $product->stock + $productData['quantity'];
                $product->save();
            } else {
                Product::create([
                    'id' => Uuid::uuid(),
                    'price' => $supplierProduct->price,
                    'category' => $supplierProduct->category,
                    'description' => $supplierProduct->description,
                    'name' => $supplierProduct->name,
                    'score' => $supplierProduct->score,
                    'stock' => $productData['quantity'],
                    'calories' => $supplierProduct->calories,
                    'protein' => $supplierProduct->protein,
                    'carbs' => $supplierProduct->carbs,
                    'fats' => $supplierProduct->fats,
                    'fiber' => $supplierProduct->fiber,
                    'sugar' => $supplierProduct->sugar,
                    'ingredients' => $supplierProduct->ingredients,
                    'allergens' => $supplierProduct->allergens
                ]);
                //  adaug acum in stocul aceluiasi produs din tabela Products sau creez noul produs comandatca am cumparat asta

            }
        }

        $this->generateAndSaveInvoice($order, $validatedData['products']);

    }
    private function generateAndSaveInvoice($order, $products)
    {
        $storageStrategy = StorageStrategyFactory::create('s3');
        $generator = PdfGeneratorFactory::create('supplier_invoice', $storageStrategy);

        $filename = "supplier_invoice_{$order->id}.pdf";

        $invoiceData = [
            'order' => $order,
            'products' => collect($products)->map(function ($product) {
                $productData = SupplierProduct::find($product['product']['id']);
                return [
                    'productData' => $productData,
                    'quantity' => $product['quantity'],
                    'price' => $productData->price,
                ];
            }),
            'supplierName' => $order->supplier->name,
            'filename' => $filename
        ];

        $filePath = $generator->generatePdf($invoiceData);

        Report::create([
            'id' => Uuid::uuid(),
            'type' => 'supplier_invoice',
            'title' => "Factura pentru Comanda {$order->id}",
            's3_path' => $filePath,
        ]);
    }

    private function calculateTotal($products)
    {
        $total = 0;
        foreach ($products as $item) {
            $product = SupplierProduct::findOrFail($item['product']['id']);
            $total += $product->price * $item['quantity'];
        }
        $total += 5; //taxes
        $total += 10; //shipping fee
        return $total;
    }

}
