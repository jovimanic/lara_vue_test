<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('api')->group(function () {

    Route::group(['prefix' => '/users', 'middleware' => 'auth:api'], function () {

//        Route::resource('/', UsersController::class);

        Route::get('/', [UsersController::class, 'index']);
        Route::post('/', [UsersController::class, 'store']);
        Route::get('/{id}', [UsersController::class, 'show']);
        Route::patch('/{id}', [UsersController::class, 'update']);
        Route::delete('/{id}', [UsersController::class, 'destroy']);

        Route::group(['prefix' => '/{id}/payments'], function () {

//            Route::resource('/', PaymentsController::class);

            Route::get('/', [PaymentsController::class, 'index']);
            Route::post('/', [PaymentsController::class, 'store']);
            Route::post('/coinbase', [PaymentsController::class, 'CreateCoinbasePayment']);
            Route::get('/{paymentId}', [PaymentsController::class, 'show']);
            Route::delete('/{paymentId}', [PaymentsController::class, 'destroy']);

        });

    });

});