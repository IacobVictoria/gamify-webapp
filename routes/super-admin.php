<?php

use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\SuperAdminController;

Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

Route::prefix('accounts')->group(function () {
    Route::get('/', [AdminAccountController::class, 'index'])
        ->name('accounts.index');
    Route::get('/create-account', [AdminAccountController::class, 'create'])->name('accounts.create');
    Route::post('/create-account', [AdminAccountController::class, 'store'])->name('accounts.store');
    Route::delete('/accounts/{accountId}', [AdminAccountController::class, 'destroy'])->name('accounts.destroy');
    Route::get('/update-account/{accountId}', [AdminAccountController::class, 'edit'])->name('accounts.edit');
    Route::put('/update-account/{accountId}', [AdminAccountController::class, 'update'])->name('accounts.update');
});

Route::prefix('roles')->group(function () {
    Route::get('/', [AdminRoleController::class, 'index'])
        ->name('roles.index');
    Route::get('/create-role', [AdminRoleController::class, 'create'])->name('roles.create');
    Route::post('/create-role', [AdminRoleController::class, 'store'])->name('roles.store');
    Route::delete('/roles/{roleId}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/update-role/{roleId}', [AdminRoleController::class, 'edit'])->name('roles.edit');
    Route::put('/update-role/{roleId}', [AdminRoleController::class, 'update'])->name('roles.update');

});