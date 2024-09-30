<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\QrScannerController;
use App\Http\Controllers\ReviewController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/{productsId}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/{productId}/reviews/create', [ReviewController::class, 'create'])->name('products.reviews.create');
    Route::post('/{productId}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');
    Route::get('/{productId}/reviews/{reviewId}/edit', [ReviewController::class, 'edit'])->name('products.reviews.edit');
    Route::put('/{productId}/reviews/{reviewId}', [ReviewController::class, 'update'])->name('products.reviews.update');
    Route::delete('/{productId}/reviews/{reviewId}', [ReviewController::class, 'destroy'])->name('products.reviews.destroy');
});

Route::get('/qr-scanner', [QrScannerController::class, 'index'])->name('qrscanner.index');
Route::post('/qr-scanner/scan', [QrScannerController::class, 'scan'])->name('qrscanner.scan');
Route::patch('/qr-scanner', [QrScannerController::class, 'updateScore'])->name('qrscanner.updateScore');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('achievements')->group(function () {
    Route::get('/', [AchievementController::class, 'index'])->name('achievements.index');
});

require __DIR__ . '/auth.php';
