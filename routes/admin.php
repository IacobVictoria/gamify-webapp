<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\AdminBadgeController;
use App\Http\Controllers\AdminCheckoutController;
use App\Http\Controllers\AdminClientOrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminShoppingCartController;
use App\Http\Controllers\AdminSupplierController;
use App\Http\Controllers\AdminSupplierOrderController;
use App\Http\Controllers\AdminSupplierProductController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])
        ->name('dashboard');
});

Route::prefix('qrcodes')->group(function () {
    Route::get('/', [GameController::class, 'index'])->name('qrcodes');
    Route::patch('/update-score', [GameController::class, 'updateScore'])->name('updateScore');
    Route::post('/generate-codes', [QrCodeController::class, 'store'])->name('generateCodes');
    Route::get('/qrCodes/{productId}', [QrCodeController::class, 'show'])->name('codes.show');
});

Route::prefix('accounts')->group(function () {
    Route::get('/', [AdminAccountController::class, 'index'])
        ->name('accounts.index');
    Route::get('/create-account', [AdminAccountController::class, 'create'])->name('accounts.create');
    Route::post('/create-account', [AdminAccountController::class, 'store'])->name('accounts.store');
    Route::delete('/accounts/{accountId}', [AdminAccountController::class, 'destroy'])->name('accounts.destroy');
    Route::get('/update-account/{accountId}', [AdminAccountController::class, 'edit'])->name('accounts.edit');
    Route::put('/update-account/{accountId}', [AdminAccountController::class, 'update'])->name('accounts.update');

});

Route::prefix('products')->group(function () {
    Route::get('/', [AdminProductController::class, 'index'])
        ->name('products.index');
    Route::get('/create-product', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/create-product', [AdminProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{productId}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/update-product/{productId}', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/update-product/{productId}', [AdminProductController::class, 'update'])->name('products.update');

});

Route::prefix('badges')->group(function () {
    Route::get('/', [AdminBadgeController::class, 'index'])
        ->name('badges.index');
    Route::get('/create-badge', [AdminBadgeController::class, 'create'])->name('badges.create');
    Route::post('/create-badge', [AdminBadgeController::class, 'store'])->name('badges.store');
    Route::get('/update-badge/{badgeId}', [AdminBadgeController::class, 'edit'])->name('badges.edit');
    Route::put('/update-badge/{badgeId}', [AdminBadgeController::class, 'update'])->name('badges.update');
    Route::delete('/delete/{badgeId}', [AdminBadgeController::class, 'destroy'])->name('badges.destroy');
});

Route::prefix('suppliers')->group(function () {
    Route::get('/', [AdminSupplierController::class, 'index'])
        ->name('suppliers.index');
    Route::get('/create-supplier', [AdminSupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/create-supplier', [AdminSupplierController::class, 'store'])->name('suppliers.store');
    Route::delete('/suppliers/{supplierId}', [AdminSupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/update-supplier/{supplierId}', [AdminSupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/update-supplier/{supplierId}', [AdminSupplierController::class, 'update'])->name('suppliers.update');

});

Route::prefix('clients_orders')->group(function () {
    Route::get('/', [AdminClientOrderController::class, 'index'])->name('clients_orders.index');
});

Route::prefix('suppliers_orders')->group(function () {
    Route::get('/', [AdminSupplierOrderController::class, 'index'])->name('suppliers_orders.index');
});

Route::prefix('suppliers_products')->group(function () {
    Route::get('/', [AdminSupplierProductController::class, 'index'])->name('suppliers_products.index');
    Route::get('/{supplierId}', [AdminSupplierProductController::class, 'show'])->name('suppliers_products.show');

});

Route::prefix('purchase_suppliers')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('purchase_suppliers.index');
});

Route::prefix('shopping-cart')->group(function () {
    Route::get('/', [AdminShoppingCartController::class, 'index'])->name('shopping-cart.index');
    Route::post('/add', [AdminShoppingCartController::class, 'store'])->name('shopping-cart.store');
    Route::post('/{productId}', [AdminShoppingCartController::class, 'update'])
        ->name('shopping-cart.update');
    Route::delete('/{productId}', [AdminShoppingCartController::class, 'destroy'])->name('shopping-cart.destroy');
});

Route::prefix('checkout')->group(function () {
    Route::get('/', [AdminCheckoutController::class, 'index'])
        ->name('checkout.index');
    Route::post('/', [AdminCheckoutController::class, 'store'])
        ->name('checkout.store');
});