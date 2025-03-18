<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\AdminBadgeController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\AdminCheckoutController;
use App\Http\Controllers\AdminClientOrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminEventCalendarController;
use App\Http\Controllers\AdminInventoryTransactionController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminQuizAnswerController;
use App\Http\Controllers\AdminQuizController;
use App\Http\Controllers\AdminQuizManagerController;
use App\Http\Controllers\AdminQuizQuestionController;
use App\Http\Controllers\AdminReportGeneratorsController;
use App\Http\Controllers\AdminShoppingCartController;
use App\Http\Controllers\AdminSupplierController;
use App\Http\Controllers\AdminSupplierOrderController;
use App\Http\Controllers\AdminSupplierProductController;
use App\Http\Controllers\AdminUserQuizRemarkController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])
        ->name('dashboard');
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

Route::prefix('user_quiz')->group(function () {
    Route::get('/', [AdminQuizController::class, 'index'])->name('user_quiz.index');
    Route::get('/create_quiz', [AdminQuizController::class, 'create'])->name('user_quiz.create');
    Route::post('/create_quiz', [AdminQuizController::class, 'store'])->name('user_quiz.store');
    Route::put('/update_quiz/{quizId}', [AdminQuizController::class, 'update'])->name('user_quiz.update');
    Route::get('/update_quiz/{id}', [AdminQuizController::class, 'edit'])->name('user_quiz.edit');
    Route::delete('/{quizId}', [AdminQuizController::class, 'destroy'])->name('user_quiz.destroy');
    Route::delete('/questions/{id}', [AdminQuizQuestionController::class, 'destroy'])->name('questions.destroy');
    Route::delete('/answers/{id}', [AdminQuizAnswerController::class, 'destroy'])->name('answers.destroy');

});

Route::prefix('quiz_manager')->group(function () {
    Route::get('/{id}', [AdminQuizManagerController::class, 'show'])->name('quiz_manager.show');
    Route::post('/{quizId}/add_question', [AdminQuizManagerController::class, 'addQuestion'])->name('quiz_add_questions.store');
    Route::put('/{quizId}/update_question', [AdminQuizManagerController::class, 'updateQuestion'])->name('quiz_update_question.update');
    Route::get('/quiz_remarks/{quizId}', [AdminUserQuizRemarkController::class, 'show'])->name('quiz_remarks.show');
});

Route::prefix('calendar')->group(function () {
    Route::get('/', [AdminEventCalendarController::class, 'index'])->name('calendar.index');
    Route::post('/create_event', [AdminEventCalendarController::class, 'store'])->name('calendar.event.store');
    Route::put('/update_event/{id}', [AdminEventCalendarController::class, 'update'])->name('calendar.event.update');
    Route::delete('/delete/{id}', [AdminEventCalendarController::class, 'destroy'])->name('calendar.event.destroy');
    Route::put('/update_event/favorites/{id}', [AdminEventCalendarController::class, 'updateFavorites'])->name('calendar.event.updateFavorites');
    Route::put('/stop_reccurence/{id}', [AdminEventCalendarController::class, 'stopRecurrence'])->name('calendar.event.stopRecurrence');
});

Route::prefix('notifications')->group(function () {
    Route::get('/', [AdminNotificationController::class, 'getNotifications'])->name('notifications.getNotifications');
    Route::post('/markAsRead', [AdminNotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/{id}/handle', [AdminNotificationController::class, 'handleNotification'])->name('notifications.handle');
});
Route::get('/events/{id}/participants-pdf-preview', [EventController::class, 'generateParticipantsPdfPreview'])->name('events.generateParticipantsPdfPreview');

Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/qr-codes', [ReportController::class, 'showQRCodeReports'])->name('reports.showQRCodeReports');
    Route::get('/participants-list', [ReportController::class, 'showParticipantsList'])->name('reports.showParticipantsList');
    Route::get('/invoices-list', [ReportController::class, 'showSupplierInvoicesList'])->name('reports.showSupplierInvoicesList');
});

Route::prefix('nps')->group(function () {
    Route::get('/index', [SurveyController::class, 'index'])->name('nps.survey.index');
    Route::get('/create', [SurveyController::class, 'createSurvey'])->name('nps.survey.create');
    Route::post('/questions/store', [SurveyController::class, 'storeQuestion'])->name('nps.questions.store');
    Route::post('/survey/store', [SurveyController::class, 'storeSurvey'])->name('nps.survey.store');
    Route::put('/questions/update/{questionId}', [SurveyController::class, 'updateQuestion'])->name('nps.question.update');
    Route::delete('/questions/delete/{questionId}', [SurveyController::class, 'deleteQuestion'])->name('nps.question.delete');
    Route::get('/show/survey/{surveyId}', [SurveyController::class, 'showSurvey'])->name('nps.show.survey');
    Route::delete('/delete/{surveyId}', [SurveyController::class, 'deleteSurvey'])->name('nps.delete.survey');
    Route::put('/surveys/update/{surveyId}', [SurveyController::class, 'updateSurvey'])->name('nps.update.survey');

});
Route::prefix('chart')->group(function () {
    Route::get('/nps/monthly', [ChartController::class, 'getMonthlyNps'])->name('chart.nps.monthly');
});

Route::prefix('inventory_transaction')->group(function () {
    Route::get('/', [AdminInventoryTransactionController::class, 'index'])->name('inventory.index');
});

Route::prefix('reports_generators')->group(function () {
    Route::get('/', [AdminReportGeneratorsController::class, 'index'])->name('reports_generators.index');
});