<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReviewCommentController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\UserCheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserOrderHistoryController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserShoppingCartController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::get('/', [UserController::class, 'dashboard'])
        ->name('dashboard');
});

Route::prefix('shopping-cart')->group(function () {
    Route::get('/', [UserShoppingCartController::class, 'index'])
        ->name('shopping-cart.index');
    Route::post('/add', [UserShoppingCartController::class, 'addToCart'])->name('shopping-cart.add');
    Route::post('/{productId}', [UserShoppingCartController::class, 'update'])
        ->name('shopping-cart.update');
    Route::delete('/{productId}', [UserShoppingCartController::class, 'destroy'])->name('shopping-cart.destroy');

});

Route::prefix('checkout')->group(function () {
    Route::get('/', [UserCheckoutController::class, 'index'])
        ->name('checkout.index');
    Route::post('/order/store', [UserCheckoutController::class, 'store'])->name('checkout.order.store');
    Route::get('/invoice/{orderId}', [InvoiceController::class, 'generateInvoice'])->name('checkout.invoice');
});

Route::prefix('achievements')->group(function () {
    Route::get('/', [AchievementController::class, 'index'])->name('achievements.index');
});

Route::prefix('order_history')->group(function () {
    Route::get('/', [UserOrderHistoryController::class, 'index'])->name('order_history.index');
});

Route::get('profiles/{userId}', [UserProfileController::class, 'show'])->name('profile.show');