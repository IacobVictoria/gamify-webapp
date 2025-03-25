<?php

use App\Http\Controllers\SuperAdminController;

Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');