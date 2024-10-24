<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExportDataController extends Controller
{
    public function exportData()
    {
        $data = DB::table('client_orders')
            ->join('order_products', 'client_orders.id', '=', 'order_products.order_id')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('users', 'client_orders.user_id', '=', 'users.id')
            ->select(
                'client_orders.user_id',
                'users.name as user_name',
                'products.id as product_id',
                'products.category as product_category',
                'order_products.price',
                'products.calories',
                'products.protein',
                'products.ingredients',
                'products.allergens',
                'client_orders.created_at as order_timestamp'
            )
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['error' => 'No data found for export.'], 404);
        }

        // Creează fișierul CSV
        $filePath = storage_path('app/user_orders.csv');
        $fileHandle = fopen($filePath, 'w');

        if ($fileHandle === false) {
            return response()->json(['error' => 'Could not create CSV file.'], 500);
        }

        // Scrie headerul CSV
        fputcsv($fileHandle, [
            'user_id',
            'user_name',
            'product_id',
            'product_category',
            'price',
            'calories',
            'protein',
            'ingredients',
            'allergens',
            'order_timestamp'
        ]);

        // Scrie fiecare rând de date în fișier
        foreach ($data as $row) {
            fputcsv($fileHandle, (array) $row);
        }

        fclose($fileHandle);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }




}
