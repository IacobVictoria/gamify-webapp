<?php

use App\Http\Controllers\AdminBadgeController;
use App\Http\Controllers\AdminGamesManagerController;
use App\Http\Controllers\AdminGamificationController;
use App\Http\Controllers\AdminGamificationMedalController;
use App\Http\Controllers\AdminHangmanManagerController;
use App\Http\Controllers\AdminQuizAnswerController;
use App\Http\Controllers\AdminQuizController;
use App\Http\Controllers\AdminQuizManagerController;
use App\Http\Controllers\AdminQuizQuestionController;
use App\Http\Controllers\AdminUserQuizRemarkController;


Route::prefix('dashboard')->group(function () {
    Route::get('/', [AdminGamificationController::class, 'dashboard'])
        ->name('dashboard');
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

Route::prefix('medals')->group(function () {
    Route::get('/', [AdminGamificationMedalController::class, 'index'])
        ->name('medals.index');
    Route::get('/create-medal', [AdminGamificationMedalController::class, 'create'])->name('medals.create');
    Route::post('/create-medal', [AdminGamificationMedalController::class, 'store'])->name('medals.store');
    Route::get('/update-medal/{medalId}', [AdminGamificationMedalController::class, 'edit'])->name('medals.edit');
    Route::put('/update-medal/{medalId}', [AdminGamificationMedalController::class, 'update'])->name('medals.update');
    Route::delete('/delete/{medalId}', [AdminGamificationMedalController::class, 'destroy'])->name('medals.destroy');
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

Route::prefix('games_manager')->group(function () {
    Route::get('/', [AdminGamesManagerController::class, 'index'])->name('games_manager.index');
});

Route::prefix('hangman_manager')->group(function () {
    Route::get('/', [AdminHangmanManagerController::class, 'index'])->name('hangman_manager.index');
    Route::post('/add', [AdminHangmanManagerController::class, 'store'])->name('hangman_manager.store');
    Route::put('/update/{word}', [AdminHangmanManagerController::class, 'update'])->name('hangman_manager.update');
    Route::delete('/delete/{word}', [AdminHangmanManagerController::class, 'destroy'])->name('hangman_manager.destroy');
});