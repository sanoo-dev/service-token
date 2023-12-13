<?php


use Illuminate\Support\Facades\Route;

Route::middleware(['auth.cookie','auth.account'])->prefix('token')->group( function () {
    Route::get('/loading', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'loadingLogin'])->name('loadingLogin');
//    Route::get('viewWelcome',  [\App\Modules\Auth\Http\Controllers\AuthController::class,'viewWelcome'])->name('viewWelcome');
}
);

Route::prefix('token')->group( function () {

    Route::get('viewWelcome',  [\App\Modules\Auth\Http\Controllers\AuthController::class,'viewWelcome'])->name('viewWelcome');
}
);

Route::prefix('/account')->group(function () {
    Route::post('/create', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'createAccount'])->name('createAccount');
    Route::post('/create-role', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'createRole'])->name('createRole');
    Route::post('/create-permission', [\App\Modules\Auth\Http\Controllers\AuthController::class, 'createPermission'])->name('createPermission');
});
