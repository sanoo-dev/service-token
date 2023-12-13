<?php


use Illuminate\Support\Facades\Route;


Route::post('/deldata', [App\Modules\Token\Http\Controllers\ApiTokenController::class,'delData'])->name('createAsccount');
Route::post('/addservice', [App\Modules\Token\Http\Controllers\ApiTokenController::class,'addServicetoEP'])->name('cresateAccount');
Route::prefix('/api/token')->group(function () {

    Route::post('/create-token',[ \App\Modules\Token\Http\Controllers\ApiTokenController::class,'createToken'])->name('createToken');
    Route::post('/verify-token', [App\Modules\Token\Http\Controllers\ApiTokenController::class,'verifyToken'])->name('verifyToken');
//    Route::post('/create-service', 'ServiceController@saveService')->name('saveService');
    Route::post('/create-account', [App\Modules\Token\Http\Controllers\ApiTokenController::class,'createAccount'])->name('createAccount');

//    Route::post('/create-info-transfer', 'ServiceController@saveInfoTransfer')->name('saveInfoTransfer');
//    Route::post('/get-list-pending', 'ServiceController@getListPending')->name('getListPending');
//    Route::post('/update-info-transfer', 'ServiceController@updateTransfer')->name('updateTransfer');
//    Route::post('/update-service', 'ServiceController@updateService')->name('updateService');
//    Route::post('/accept-end-point', 'ServiceController@acceptEndPoint')->name('acceptEndPoint');
//    Route::post('/extend-key', 'ServiceController@extendKey')->name('extendKey');

});


Route::get('/memcached-test', function () {
    // Store a value in the cache
    Cache::put('key', 'value', 60);

    // Retrieve the value from the cache
    $value = Cache::get('SV-192.163.13');

    // Display the retrieved value
    return $value;
});
