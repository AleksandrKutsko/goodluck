<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\PaymentSystemController;
use App\Http\Controllers\Api\TransactionStatusController;
use App\Http\Controllers\Api\TransactionSubStatusController;
use App\Http\Controllers\Api\TransactionTypeController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\GoodLuckCallbackController;
use App\Http\Middleware\VerifyGoodLuckCallback;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('reference')->group(function(){
    Route::get('/banks', [BankController::class, 'index']);
    Route::get('/transaction-types', [TransactionTypeController::class, 'index']);
    Route::get('/transaction-statuses', [TransactionStatusController::class, 'index']);
    Route::get('/transaction-sub-statuses', [TransactionSubStatusController::class, 'index']);
    Route::get('/payment-systems', [PaymentSystemController::class, 'index']);
});

Route::prefix('transactions')->group(function (){
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/{transaction}', [TransactionController::class, 'show']);
    Route::post('/order', [TransactionController::class, 'createOrder']);
    Route::post('/payout', [TransactionController::class, 'createPayout']);
});

Route::prefix('webhooks')->group(function(){
    Route::post('/goodluck', [GoodLuckCallbackController::class, 'verify'])->middleware(VerifyGoodLuckCallback::class);
});
