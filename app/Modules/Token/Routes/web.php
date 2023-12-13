<?php
use App\Modules\Token\Http\Controllers\ServiceFeController;
use Illuminate\Support\Facades\Route;



//Route::group([ 'prefix' => 'token'], function () {
    Route::middleware(['auth.cookie','auth.account'])->prefix('token')->group( function () {
    Route::get('login', [ServiceFeController::class, 'viewLogin'])->name('login');
    Route::get('manage-services', [ServiceFeController::class, 'viewManageService'])->name('viewManageService');
    Route::get('padding-services', [ServiceFeController::class, 'viewAcceptService'])->name('viewAcceptService');
    Route::get('manage-endpoints', [ServiceFeController::class, 'viewManageEndPoint'])->name('viewManageEndPoint');
    Route::get('manage-users', [ServiceFeController::class, 'viewManageUser'])->name('viewManageUser');
    Route::get('create-user', [ServiceFeController::class, 'viewCreateUser'])->name('viewCreateUser');
    Route::get('create-user', [ServiceFeController::class, 'viewCreateUser'])->name('viewCreateUser');
    Route::get('create-service', [ServiceFeController::class, 'viewCreateService'])->name('viewCreateService');
//    Route::get('create-endpoint', [ServiceFeController::class, 'viewCreateEndPoint'])->name('viewCreateEndPoint');
    Route::post('create-endpoints', [ServiceFeController::class, 'createEndPoint'])->name('createEndPoint');
    Route::post('create-padding', [ServiceFeController::class, 'createPaddingService'])->name('createPaddingService');


    Route::get('list-endpoints', [\App\Modules\Token\Http\Controllers\ApiTokenController::class, 'getListEndPoint'])->name('getListEndPoint');
    Route::get('list-service', [\App\Modules\Token\Http\Controllers\ApiTokenController::class, 'getListService'])->name('getListEndPoint');
    Route::get('update-transfer', [\App\Modules\Token\Http\Controllers\ApiTokenController::class, 'updateTransfer'])->name('updateTransfer');
    Route::get('new-key', [\App\Modules\Token\Http\Controllers\ApiTokenController::class, 'createNewKey'])->name('createNewKey');

    Route::get('accept-key', [\App\Modules\Token\Http\Controllers\ApiTokenController::class, 'acceptKey'])->name('acceptKey');
    Route::get('update-service', [\App\Modules\Token\Http\Controllers\ApiTokenController::class, 'updateService'])->name('updateService');
    Route::get('extend-key', [\App\Modules\Token\Http\Controllers\ApiTokenController::class, 'extendKey'])->name('extendKey');





    Route::post('accept-service', [ServiceFeController::class, 'acceptPaddingService'])->name('acceptService');
    Route::get('detail-endpoint', [ServiceFeController::class, 'viewDetailEndPoint'])->name('viewCreateEndPoint');
    Route::get('detail-service', [ServiceFeController::class, 'viewDetailService'])->name('viewDetailService');
    Route::get('detail-user', [ServiceFeController::class, 'viewDetailUser'])->name('viewDetailUser');
});
