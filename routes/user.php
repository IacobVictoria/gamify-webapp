<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ExploreGamesController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HangmanGameController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NutritionAndWellnessController;
use App\Http\Controllers\OpenAiController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\QrScannerController;
use App\Http\Controllers\ShoppingCenterController;
use App\Http\Controllers\UserChatController;
use App\Http\Controllers\UserCheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFavoriteActivityController;
use App\Http\Controllers\UserGameCenterController;
use App\Http\Controllers\UserOrderHistoryController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserQuizController;
use App\Http\Controllers\UserQuizRemarkController;
use App\Http\Controllers\UserRecommandationController;
use App\Http\Controllers\UserShoppingCartController;
use App\Http\Controllers\UserWishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::get('/', [UserController::class, 'dashboard'])
        ->name('dashboard');
    Route::get('/game-center', [UserGameCenterController::class, 'index'])->name('dashboard.game_center.index');
    Route::get('/game-center/badges', [UserGameCenterController::class, 'index_badges'])->name('dashboard.game_center.badges.index');
    Route::get('/explore-games', [ExploreGamesController::class, 'index'])->name('dashboard.explore-games.index');
    Route::get('/explore-games/history-quizzes', [ExploreGamesController::class, 'historyQuiz'])->name('dashboard.explore-games.historyQuiz');
    Route::get('/explore-games/quiz-results/{quizId}', [ExploreGamesController::class, 'show'])
        ->name('dashboard.explore-games.show');

    Route::get('/explore-games/history-hangman', [ExploreGamesController::class, 'historyHangman'])->name('dashboard.explore-games.historyHangman');
    Route::get('/favorite-activities', [UserFavoriteActivityController::class, 'index'])->name('dashboard.favorite_activities.index');
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
});

Route::prefix('achievements')->group(function () {
    Route::get('/', [AchievementController::class, 'index'])->name('achievements.index');
});

Route::prefix('order_history')->group(function () {
    Route::get('/', [UserOrderHistoryController::class, 'index'])->name('order_history.index');
});

Route::get('profiles/{userId}', [UserProfileController::class, 'show'])->name('profile.show');

Route::prefix('wishlist')->group(function () {
    Route::get('/', [UserWishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/{productId}/like', [UserWishlistController::class, 'like'])->name('wishlist.products.like');
    Route::post('/{productId}/dislike', [UserWishlistController::class, 'dislike'])->name('wishlist.products.dislike');
});

Route::prefix('quizzes')->group(function () {
    Route::get('/', [UserQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/{slug}', [UserQuizController::class, 'show'])->name('quiz.show');
    Route::post('/remarks/{quizId}', [UserQuizRemarkController::class, 'store'])->name('quiz.remark.store');
});

Route::post('/user_quiz/retry', [UserQuizController::class, 'retryQuiz'])->name('user_quiz.retry');
Route::post('/user_quiz/lock', [UserQuizController::class, 'lockQuiz'])->name('user_quiz.lock');


Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'getNotifications'])->name('notifications.getNotifications');
    Route::post('/mark-as-read', [NotificationController::class, 'markNotificationsAsRead'])->name('notifications.markNotificationAsRead');
});

Route::prefix('user_chat')->group(function () {
    Route::get('/', [UserChatController::class, 'index'])->name('user_chat.index');
    Route::get('/messages/{friendId}', [UserChatController::class, 'getConversation'])->name('user_chat.conversation');
    Route::post('/messages/{friendId}', [UserChatController::class, 'sendMessage'])->name('user_chat.sendMessage');
    Route::put('/mark-read/{friendId}', [UserChatController::class, 'markMessagesAsRead']);
    Route::get('/check-status/{friendId}', [UserChatController::class, 'checkUserStatus']);
    Route::get('/searchFriendConversation', [UserChatController::class, 'searchFriendConversation']);
});

Route::prefix('user_friends')->group(function () {
    Route::post('/request', [FriendController::class, 'sendFriendRequest']);
    Route::post('/accept', [FriendController::class, 'acceptFriendRequest']);
    Route::get('/search', [FriendController::class, 'searchUsers']);
    Route::post('/{notifId}/respond', [NotificationController::class, 'handleRequestNotification']);
});

Route::prefix('wellness')->group(function () {
    Route::get('/', [NutritionAndWellnessController::class, 'index'])->name('wellness.index');
    Route::post('/count_calories', [NutritionAndWellnessController::class, 'countCalories'])->name('wellness.count_calories');
    Route::post('/openai/respond-messages', [OpenAiController::class, 'respondMessages']);
});

Route::prefix('scanEvents')->group(function () {
    Route::post('/', [QrScannerController::class, 'scanEvent'])->name('scanEvent.post');
});

Route::prefix('hangmanGame')->group(function () {
    Route::get('/', [HangmanGameController::class, 'index'])->name('hangman.index');
    Route::get('/search_friends', [FriendController::class, 'searchFriendsHangMan'])->name('hangman.searchFriends');
    Route::post('/generate_session', [HangmanGameController::class, 'generateGameSession'])->name('generateGameSession');
    Route::get('/game/{sessionId}', [HangmanGameController::class, 'show'])->name('hangman.game.show');
    Route::post('/{sessionId}/join', [HangmanGameController::class, 'joinSession'])->name('hangman.game.join');
    Route::post('/{sessionId}/start', [HangmanGameController::class, 'startGame'])->name('hangman.game.start');
    Route::post('/{sessionId}/submitWord', [HangmanGameController::class, 'submitWord'])->name('hangman.game.submitWord');
    Route::post('/{sessionId}/guess', [HangmanGameController::class, 'handleGuess'])->name('hangman.game.handleGuess');
    Route::get('/word-options', [HangmanGameController::class, 'getWordOptions'])->name('hangman.game.getWordOptions');
});

//Shopping Center
Route::get('shopping-center', [ShoppingCenterController::class, 'index'])->name('shopping-center.index');

//Discount Center
Route::get('/discounts', [DiscountController::class, 'getUserDiscounts']);
Route::post('/validate-discount', [DiscountController::class, 'validatePromo']);

Route::post('/participants/{activityId}/done', [ParticipantController::class, 'store'])
    ->name('participant.store');

Route::post('/participants/{activityId}/toggleFavorite', [ParticipantController::class, 'toggleFavorite'])
    ->name('participant.toggleFavorite');


Route::get('/recommendations', [UserRecommandationController::class, 'index'])
    ->name('recommendations.index');
