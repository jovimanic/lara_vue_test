<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => '/coinbase-api'], function () {

    \App\Services\Coinbase\Coinbase::$i->SetAccesses((object)[
        'publicKey' => '76324ae2e056733cf41bdba67324194d',
        'secretKey' => '2vLI+v7i7+s3cfZ8Hjem9YphColPLBWordDuLXDQIUN0ANQwPyc4euDpg/i5wXAamCaGJoIKAQVEvtTNCCXK7w==',
        'passPhrase' => 'api'
    ]);

    Route::get('/profiles', function () {
        dd(\App\Services\Coinbase\Coinbase::$i->GetProfiles());
    });

    Route::get('/accounts', function () {
        dd(\App\Services\Coinbase\Coinbase::$i->GetAccounts());
    });

    Route::get('/payment-methods', function () {
        dd(\App\Services\Coinbase\Coinbase::$i->PaymentMethods());
    });

    Route::get('/deposit-from-payment-methods', function () {
        dd(\App\Services\Coinbase\Coinbase::$i->DepositFromPaymentMethods('8e234d4c-a6a9-4d55-84c4-9168be414cc2', '100', '6a23926d-74b6-4373-8434-9d437c2bafb2', 'USD'));
    });

    Route::get('/withdrawals-from-payment-methods', function () {
        dd(\App\Services\Coinbase\Coinbase::$i->WithdrawalsFromPaymentMethods('8e234d4c-a6a9-4d55-84c4-9168be414cc2', '500', '6a23926d-74b6-4373-8434-9d437c2bafb2', 'USD'));
    });

    Route::get('/transfers', function () {
        dd(\App\Services\Coinbase\Coinbase::$i->Transfers('8e234d4c-a6a9-4d55-84c4-9168be414cc2'));
    });
});

Route::group(['prefix' => '/auth'], function () {
    Route::get('/login', [AuthController::class, 'Index'])->name('login');
    Route::post('/login', [AuthController::class, 'Login']);
    Route::get('/logout', [AuthController::class, 'Logout']);
});

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*')->middleware('auth');

