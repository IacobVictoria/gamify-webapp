<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\QrScannerController;
use App\Http\Controllers\ReviewCommentController;
use App\Http\Controllers\ReviewCommentLikeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\ReviewMediaController;
use App\Http\Controllers\UserWishlistController;
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
    Route::post('/{productId}/reviews/{reviewId}', [ReviewController::class, 'update'])->name('products.reviews.update');
    Route::delete('/{productId}/reviews/{reviewId}', [ReviewController::class, 'destroy'])->name('products.reviews.destroy');
    Route::post('/{productId}/like', [UserWishlistController::class, 'like'])->name('wishlist.products.like');
    Route::post('/{productId}/dislike', [UserWishlistController::class, 'dislike'])->name('wishlist.products.dislike');
});


Route::get('/qr-scanner', [QrScannerController::class, 'index'])->name('qrscanner.index');
Route::post('/qr-scanner/scan', [QrScannerController::class, 'scan'])->name('qrscanner.scan');
Route::patch('/qr-scanner', [QrScannerController::class, 'updateScore'])->name('qrscanner.updateScore');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('reviews')->group(function () {
    Route::post('/{reviewId}/like', [ReviewLikeController::class, 'like'])->name('reviews.like');
    Route::post('/{reviewId}/unlike', [ReviewLikeController::class, 'unlike'])->name('reviews.unlike');
    Route::post('/{reviewId}/comment', [ReviewCommentController::class, 'store'])->name('review_comments.store');
    Route::put('/comment/{commentId}', [ReviewCommentController::class, 'update'])->name('review_comments.update');
    Route::delete('/comment/{commentId}', [ReviewCommentController::class, 'destroy'])->name('review_comments.destroy');
    Route::post('/comment/{commentId}/like', [ReviewCommentLikeController::class, 'like'])->name('review_comment.like');
    Route::post('/comment/{commentId}/unlike', [ReviewCommentLikeController::class, 'unlike'])->name('review_comment.unlike');
    Route::post('/{reviewId}/create-media', [ReviewMediaController::class, 'store'])->name('review_media.store');
    Route::delete('/review_media/{reviewMediaId}', [ReviewMediaController::class, 'destroy'])->name('review_media.destroy');
});

require __DIR__ . '/auth.php';
