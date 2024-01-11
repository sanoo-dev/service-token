<?php

use App\Modules\Token\Http\Controllers\EndpointController;
use App\Modules\Token\Http\Controllers\ServiceController;
use App\Modules\Token\Http\Controllers\ServiceFeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth.account', 'auth.cookie']], function () {
    // Endpoint
    Route::get('endpoints', [EndpointController::class, 'index'])->name('endpoints.index');
    Route::post('endpoints', [EndpointController::class, 'store'])->name('endpoints.store');

// Service
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('services/pending', [ServiceController::class, 'pending'])->name('services.pending');
    Route::patch('services/{id}/accept', [ServiceController::class, 'accept'])->name('services.accept');

    Route::get('login', [ServiceFeController::class, 'viewLogin'])->name('login');
    Route::get('manage-users', [ServiceFeController::class, 'viewManageUser'])->name('viewManageUser');
    Route::get('create-user', [ServiceFeController::class, 'viewCreateUser'])->name('viewCreateUser');
    Route::get('create-user', [ServiceFeController::class, 'viewCreateUser'])->name('viewCreateUser');
    Route::get('detail-user', [ServiceFeController::class, 'viewDetailUser'])->name('viewDetailUser');
});
