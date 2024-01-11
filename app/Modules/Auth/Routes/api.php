<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::get('check-account', [AuthController::class, 'checkAccount'])->name('checkAccount');
    Route::post('create-account', [AuthController::class, 'storeAccount'])->name('auth.account.store');
    Route::post('create-role', [AuthController::class, 'storeRole'])->name('auth.role.store');
    Route::post('create-permission', [AuthController::class, 'storePermission'])->name('auth.permission.store');
});

Route::prefix('account')->group(function () {
    Route::post('create', [AuthController::class, 'createAccount'])->name('createAccount');
    Route::post('create-role', [AuthController::class, 'createRole'])->name('createRole');
    Route::post('create-permission', [AuthController::class, 'createPermission'])->name('createPermission');
});
