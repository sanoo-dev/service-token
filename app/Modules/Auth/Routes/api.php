<?php


use Illuminate\Support\Facades\Route;

Route::prefix('/api/auth')->group(function () {
    Route::get('/check-account', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'checkAccount'])->name('checkAccount');
});
Route::prefix('/account')->group(function () {
    Route::post('/create', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'createAccount'])->name('createAccount');
    Route::post('/create-role', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'createRole'])->name('createRole');
    Route::post('/create-permission', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'createPermission'])->name('createPermission');
});
