<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProductComparisonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrScannerController;
use App\Http\Controllers\RecommandationPythonController;
use App\Http\Controllers\ReviewCommentController;
use App\Http\Controllers\ReviewCommentLikeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\ReviewMediaController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserSurveyController;
use App\Http\Controllers\UserWishlistController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [WelcomeController::class, 'index'])->name('home');


Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/{productId}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store');
    Route::post('/{productId}/reviews/{reviewId}', [ReviewController::class, 'update'])->name('products.reviews.update');
    Route::delete('/{productId}/reviews/{reviewId}', [ReviewController::class, 'destroy'])->name('products.reviews.destroy');
});

Route::group(['prefix' => 'activities'], function () {
    Route::get('/', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/{slug}', [ActivityController::class, 'show'])->name('activities.show');
});


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

Route::prefix('comparison')->group(function () {
    Route::post('/add', [ProductComparisonController::class, 'addToComparison']);
    Route::delete('/remove/{productId}', [ProductComparisonController::class, 'removeFromComparison']);
    Route::get('/', [ProductComparisonController::class, 'getProductsComparison']);
    Route::get('/{productSlugs}', [ProductComparisonController::class, 'getComparisonBySlugs'])->name('comparison.show');
    Route::post('/reset', [ProductComparisonController::class, 'resetComparison']);

});

Route::prefix('nps')->group(function () {
    Route::get('/form', [UserSurveyController::class, 'index'])->name('nps.form.index');
    Route::post('/form/store', [UserSurveyController::class, 'storeResults'])->name('nps.form.storeResults');
});
Broadcast::routes(['middleware' => ['web', 'auth']]);

//STRIPE CASHIER
Route::get('stripe',[StripeController::class,'index'])->name('stripe.index');
Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent'])->name('stripe.payment');
Route::post('/confirm-payment', [StripeController::class, 'confirmPayment'])->name('stripe.confirm');
Route::post('/cancel-payment', [StripeController::class, 'cancelPayment'])->name('stripe.cancel');

//Suppliers
Route::get('/suppliers',[SupplierController::class,'web_view'])->name('suppliers.web_view');

Route::post('/scan_product',[QrScannerController::class,'scanProduct'])->name('scan.product.find');
Route::post('/scan_product/points',[QrScannerController::class,'scanProductEarnPoints'])->name('scan.product.earn');


require __DIR__ . '/auth.php';
