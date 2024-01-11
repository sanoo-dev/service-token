<?php

use App\Modules\Token\Http\Controllers\Api\ApiEndpointController;
use App\Modules\Token\Http\Controllers\Api\ApiServiceController;
use App\Modules\Token\Http\Controllers\Api\ApiTokenController;
use Illuminate\Support\Facades\Route;

// Endpoints
Route::get('endpoints/list', [ApiEndpointController::class, 'list'])->name('api.endpoints.list');
Route::get('endpoints/{id}', [ApiEndpointController::class, 'detail'])->name('api.endpoints.detail');
Route::patch('endpoints/{id}', [ApiEndpointController::class, 'update'])->name('api.endpoints.update');

// Service
Route::get('services/list', [ApiServiceController::class, 'list'])->name('api.services.list');
Route::get('services/{id}', [ApiServiceController::class, 'detail'])->name('api.services.detail');
Route::patch('services/{id}', [ApiServiceController::class, 'update'])->name('api.services.update');

// Token
Route::post('token/generate', [ApiTokenController::class, 'generate'])->name('api.token.generate');
Route::post('token/verify', [ApiTokenController::class, 'verify'])->name('api.token.verify');


//Route::post('/deldata', [App\Modules\Token\Http\Controllers\ApiTokenController::class,'delData'])->name('createAsccount');
//Route::post('/addservice', [App\Modules\Token\Http\Controllers\ApiTokenController::class,'addServicetoEP'])->name('cresateAccount');


Route::get('memcached-test', function () {
    Cache::put('SV-192.163.13', 'OK!', 60);
    return Cache::get('SV-192.163.13');
});
