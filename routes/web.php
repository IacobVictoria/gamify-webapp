<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExportDataController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OpenAiController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProductComparisonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\QrScannerController;
use App\Http\Controllers\RecommandationPythonController;
use App\Http\Controllers\ReviewCommentController;
use App\Http\Controllers\ReviewCommentLikeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\ReviewMediaController;
use App\Http\Controllers\UserWishlistController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::post('/feedback', [FeedbackController::class, 'sendFeedback']);
Route::get('/feedback/get', [FeedbackController::class, 'index']);

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


Route::get('/recommendations/{userId}', [RecommandationPythonController::class, 'index']);

Route::prefix('comparison')->group(function () {
    Route::post('/add', [ProductComparisonController::class, 'addToComparison']);
    Route::delete('/remove/{productId}', [ProductComparisonController::class, 'removeFromComparison']);
    Route::get('/', [ProductComparisonController::class, 'getProductsComparison']);
    Route::get('/{ids}', [ProductComparisonController::class, 'getComparisonByIds'])->name('comparison.show');
    ;
    Route::post('/reset', [ProductComparisonController::class, 'resetComparison']);

});
//Route::post('/send-whatsapp', [WhatsAppController::class, 'sendWhatsAppMessage'])->name('web.send-whatsapp');
Route::post('/send-promotion', [WhatsappController::class, 'sendPromotion']);

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');
    Route::get('/{id}', [EventController::class, 'show'])->name('event.show');
    Route::post('/{eventId}/participant/store', [ParticipantController::class, 'store'])->name('event.participant.store');
});
Broadcast::routes(['middleware' => ['web', 'auth']]);

require __DIR__ . '/auth.php';
