<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('token')->group(function () {
    Route::get('/loading', [AuthController::class, 'loadingLogin'])->name('loadingLogin');
    Route::get('viewWelcome', [AuthController::class, 'viewWelcome'])->name('viewWelcome');
});
